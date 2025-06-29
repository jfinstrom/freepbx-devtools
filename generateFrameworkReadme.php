#!/usr/bin/env php
<?php
if (class_exists('FreePBX', false) === false) {
	include '/etc/freepbx.conf';
}

class FrameworkReadmeGenerator
{
	private const TRADEMARK_POLICY_LINK = 'https://sangoma.com/wp-content/uploads/Sangoma-Trademark-Policy-1-1.pdf';
	private const CACHE_DIR = __DIR__ . '/cache';
	private const LIBRARIES_IO_RATE_LIMIT = 60; // requests per minute
	private const LIBRARIES_IO_RATE_WINDOW = 60; // seconds
	private readonly FreePBX $FreePBX;
	private readonly string $version;
	private readonly string $majorVersion;
	private readonly string $webroot;
	private readonly string $asteriskVersions;
	private array $librariesIoRequestTimes = [];

	public function __construct(?string $version = null, ?string $asteriskVersions = null, ?string $webroot = null)
	{
		$this->FreePBX = FreePBX::Create();
		$this->webroot = $webroot ?? $this->FreePBX->Config->get("AMPWEBROOT");

		// Use provided version or fetch from get_framework_version
		$this->version = $version ?? get_framework_version(); // e.g., 17.0.1.2
		if (!preg_match('/^\d+\.\d+(\.\d+)*$/', $this->version)) {
			throw new InvalidArgumentException("Invalid version format: {$this->version}");
		}
		$parts = explode('.', $this->version);
		$this->majorVersion = implode('.', array_slice($parts, 0, 2)); // e.g., 17.0

		// Use provided Asterisk versions or fetch from source
		$this->asteriskVersions = $asteriskVersions ?? $this->fetchSupportedAsteriskVersions();
		
		if (!is_dir(self::CACHE_DIR)) {
			mkdir(self::CACHE_DIR, 0755, true);
		}
	}

	public function parseComposerJson(): array
	{
		$composerPath = $this->webroot . '/admin/libraries/Composer/composer.json';
		if (!file_exists($composerPath)) {
			throw new RuntimeException("Composer file not found at: $composerPath");
		}
		$composerJson = json_decode(file_get_contents($composerPath), true, 512, JSON_THROW_ON_ERROR);
		if (!isset($composerJson['require']) || !is_array($composerJson['require'])) {
			throw new RuntimeException("No PHP libraries found in composer.json");
		}
		return $composerJson['require'];
	}

	public function getCachedPackagistData(string $library, string $version): ?array
	{
		$cacheFile = self::CACHE_DIR . '/' . md5($library . $version) . '.json';
		if (file_exists($cacheFile) && (time() - filemtime($cacheFile)) < 86400) {
			return json_decode(file_get_contents($cacheFile), true, 512, JSON_THROW_ON_ERROR);
		}
		return null;
	}

	public function cachePackagistData(string $library, string $version, array $data): void
	{
		$cacheFile = self::CACHE_DIR . '/' . md5($library . $version) . '.json';
		file_put_contents($cacheFile, json_encode($data, JSON_THROW_ON_ERROR));
	}

	public function getCachedLibrariesIoData(string $packageName): ?array
	{
		$cacheFile = self::CACHE_DIR . '/librariesio_' . md5($packageName) . '.json';
		if (file_exists($cacheFile) && (time() - filemtime($cacheFile)) < 86400) {
			return json_decode(file_get_contents($cacheFile), true, 512, JSON_THROW_ON_ERROR);
		}
		return null;
	}

	public function cacheLibrariesIoData(string $packageName, array $data): void
	{
		$cacheFile = self::CACHE_DIR . '/librariesio_' . md5($packageName) . '.json';
		file_put_contents($cacheFile, json_encode($data, JSON_THROW_ON_ERROR));
	}

	public function checkRateLimit(): void
	{
		$now = time();
		// Remove requests older than the rate window
		$this->librariesIoRequestTimes = array_filter(
			$this->librariesIoRequestTimes, 
			fn($time) => ($now - $time) < self::LIBRARIES_IO_RATE_WINDOW
		);

		if (count($this->librariesIoRequestTimes) >= self::LIBRARIES_IO_RATE_LIMIT) {
			$oldestRequest = min($this->librariesIoRequestTimes);
			$waitTime = self::LIBRARIES_IO_RATE_WINDOW - ($now - $oldestRequest) + 1;
			echo sprintf("Rate limit reached. Waiting %d seconds before next libraries.io request...\n", $waitTime);
			sleep($waitTime);
			// Clear old requests after waiting
			$this->librariesIoRequestTimes = array_filter(
				$this->librariesIoRequestTimes, 
				fn($time) => (time() - $time) < self::LIBRARIES_IO_RATE_WINDOW
			);
		}

		$this->librariesIoRequestTimes[] = time();
	}

	public function normalizePackageName(string $packageName): array
	{
		// List of potential package name variations to try
		$variations = [$packageName];
		
		// Remove common suffixes that might not be in the npm package name
		$suffixesToRemove = ['-hi-IN', '-en-US', '-min', '.min', '-latest', '-dist'];
		foreach ($suffixesToRemove as $suffix) {
			if (str_ends_with($packageName, $suffix)) {
				$variations[] = substr($packageName, 0, -strlen($suffix));
			}
		}
		
		// Handle bootstrap-table extensions (they're usually separate packages)
		if (str_starts_with($packageName, 'bootstrap-table-')) {
			// Try the extension as its own package
			$variations[] = $packageName;
			// Also try the base bootstrap-table
			$variations[] = 'bootstrap-table';
		}
		
		// Common package name mappings
		$commonMappings = [
			'jquery-ui' => ['jquery-ui', 'jqueryui'],
			'bootstrap-table' => ['bootstrap-table'],
			'font-awesome' => ['@fortawesome/fontawesome-free', 'font-awesome'],
			'chart.js' => ['chart.js', 'chartjs'],
			'moment.js' => ['moment'],
			'datatables' => ['datatables.net', 'datatables'],
			'select2' => ['select2'],
			'sweetalert' => ['sweetalert2', 'sweetalert'],
			'bootstrap' => ['bootstrap'],
			'jquery' => ['jquery'],
			'lodash' => ['lodash'],
			'underscore' => ['underscore'],
		];
		
		$baseName = strtolower($packageName);
		if (isset($commonMappings[$baseName])) {
			$variations = array_merge($variations, $commonMappings[$baseName]);
		}
		
		// Remove duplicates and return unique variations
		return array_unique($variations);
	}

	public function fetchLibrariesIoData(string $packageName): ?array
	{
		$cachedData = $this->getCachedLibrariesIoData($packageName);
		if ($cachedData) {
			return $cachedData;
		}

		// Try different variations of the package name
		$packageVariations = $this->normalizePackageName($packageName);
		
		foreach ($packageVariations as $variation) {
			// Check rate limit before making request
			$this->checkRateLimit();

			$url = "https://libraries.io/api/npm/" . urlencode($variation);
			echo sprintf("Fetching libraries.io data for: %s (trying variation: %s)\n", $packageName, $variation);
			$librariesIoJson = @file_get_contents($url);

			if ($librariesIoJson === false) {
				echo sprintf("Failed to fetch data for variation: %s\n", $variation);
				continue; // Try next variation
			}

			$librariesIoData = json_decode($librariesIoJson, true, 512, JSON_THROW_ON_ERROR);
			if (!$librariesIoData || !is_array($librariesIoData) || isset($librariesIoData['error'])) {
				echo sprintf("No valid data found for variation: %s\n", $variation);
				continue; // Try next variation
			}

			// Success! Extract the data we need
			$enrichedData = [
				'description' => $librariesIoData['description'] ?? null,
				'homepage' => $librariesIoData['homepage'] ?? null,
				'license' => isset($librariesIoData['normalized_licenses'][0]) ? $librariesIoData['normalized_licenses'][0] : null,
				'found_as' => $variation, // Track which variation worked
			];

			echo sprintf("Successfully found data for %s as %s\n", $packageName, $variation);
			$this->cacheLibrariesIoData($packageName, $enrichedData);
			return $enrichedData;
		}

		// No variation worked, cache empty result to avoid repeated attempts
		echo sprintf("No data found for any variation of: %s\n", $packageName);
		$emptyData = [
			'description' => null,
			'homepage' => null,
			'license' => null,
			'found_as' => null,
		];
		$this->cacheLibrariesIoData($packageName, $emptyData);
		return null;
	}

	public function getPHPLibraries(): string
	{
		$composerRequirements = $this->parseComposerJson();
		$phpLibraries = [];
		$count = count(array_filter(array_keys($composerRequirements), fn($lib) => strpos($lib, 'php') !== 0 && strpos($lib, 'ext-') !== 0));
		echo sprintf("Generating PHP Library output. This may make up to %d HTTP requests to packagist.org\n", $count);
		echo sprintf("Note: Each library may also trigger a libraries.io API call (rate limited to %d/minute)\n", self::LIBRARIES_IO_RATE_LIMIT);

		foreach ($composerRequirements as $library => $version) {
			if (strpos($library, 'php') === 0 || strpos($library, 'ext-') === 0) {
				continue;
			}

			$versionStripped = 'v' . str_replace(['^', '~'], '', $version);
			$cachedData = $this->getCachedPackagistData($library, $versionStripped);

			if ($cachedData) {
				$phpLibraries[] = $cachedData;
				continue;
			}

			$packagistUrl = "https://packagist.org/packages/{$library}.json?version=" . urlencode($versionStripped);
			$packagistJson = @file_get_contents($packagistUrl);

			$libraryData = [
				'name' => $library,
				'description' => 'No description available',
				'version' => $versionStripped,
				'url' => "https://packagist.org/packages/{$library}",
				'license' => 'Unknown',
				'updated' => null,
			];

			if ($packagistJson !== false) {
				$packagistData = json_decode($packagistJson, true, 512, JSON_THROW_ON_ERROR);
				if (isset($packagistData['package'])) {
					$versionData = $packagistData['package']['versions'][$versionStripped] ?? end($packagistData['package']['versions']) ?? null;
					if ($versionData) {
						$libraryData = [
							'name' => $packagistData['package']['name'],
							'description' => $packagistData['package']['description'] ?? 'No description available',
							'version' => $versionStripped,
							'url' => $versionData['source']['homepage'] ?? $packagistData['package']['repository'] ?? "https://packagist.org/packages/{$library}",
							'license' => $versionData['license'][0] ?? 'Unknown',
							'updated' => $versionData['time'] ?? null,
						];
					}
				}
			}

			$this->cachePackagistData($library, $versionStripped, $libraryData);
			$phpLibraries[] = $libraryData;
		}

		return implode(PHP_EOL, array_map(
			fn($lib) => sprintf("- [%s](%s) - %s. License %s", $lib['name'], $lib['url'], $lib['description'], $lib['license']),
			$phpLibraries
		));
	}

	public function getRandomOpenSourceQuote(): string
	{
		$quotes = [
			"Free Software, Hell Yeah!",
			"In open source, we trust!",
			"Code is poetry, share it freely.",
			"Open source: Because knowledge should be free.",
			"Fork it, fix it, share it!",
			"Software is like a recipe: share it, improve it!",
			"Give back to the community that builds the future.",
			"Open source: Where ideas meet collaboration.",
			"Keep it open, keep it free!",
			"Together, we code a better world.",
			"Transparency in code, transparency in progress.",
			"Hack the planet, share the code!",
			"Every bug is a chance to contribute.",
			"Open source: Empowering the many, not the few.",
			"Write code, change the world.",
		];
		return $quotes[array_rand($quotes)];
	}

	public function fetchSupportedAsteriskVersions(): string
	{
		$cacheFile = self::CACHE_DIR . '/asterisk_versions_' . $this->majorVersion . '.txt';
		if (file_exists($cacheFile) && (time() - filemtime($cacheFile)) < 86400) {
			return file_get_contents($cacheFile);
		}

		$url = "https://raw.githubusercontent.com/FreePBX/framework/refs/heads/release/{$this->majorVersion}/installlib/installcommand.class.php";
		$handle = @fopen($url, 'r');
		if ($handle === false) {
			return '';
		}

		$versions = '';
		while (($line = fgets($handle)) !== false) {
			if (preg_match('/Supported Asterisk versions: ([\d,\s]+)/', $line, $matches)) {
				$versions = sprintf("Supported Asterisk versions: %s", implode(', ', array_map('trim', explode(',', $matches[1]))));
				break;
			}
		}
		fclose($handle);

		if ($versions) {
			file_put_contents($cacheFile, $versions);
		}
		return $versions;
	}

	public function getSupportedAsteriskVersions(): string
	{
		return $this->asteriskVersions;
	}

	public function generateReadme(): string
	{
		$templatePath = __DIR__ . '/assets/FRAMEWORK_README.template';
		if (!file_exists($templatePath)) {
			throw new RuntimeException("Template file not found at: $templatePath");
		}
		$libraries = $this->generateAssetsMd();
		$jsLibraries = $libraries['js'] ?? '';
		$styleLibraries = $libraries['styles'] ?? '';

		$template = file_get_contents($templatePath);
		$replacements = [
			'{{MAJOR_VERSION}}' => $this->majorVersion,
			'{{FULL_VERSION}}' => $this->version,
			'{{PHP_LIBRARIES}}' => $this->getPHPLibraries(),
			'{{JS_LIBRARIES}}' => $jsLibraries,
			'{{STYLE_LIBRARIES}}' => $styleLibraries,
			'{{QUOTE}}' => $this->getRandomOpenSourceQuote(),
			'{{ASTERISK_VERSIONS}}' => $this->getSupportedAsteriskVersions(),
		];

		return str_replace(
			array_keys($replacements),
			array_values($replacements),
			$template
		);
	}

	public function getAssetFiles(): array
	{
		$assets = [];
		$assetDirectories = [
			'js' => $this->webroot . '/admin/assets/js/',
			'css' => $this->webroot . '/admin/assets/css/',
			'less' => $this->webroot . '/admin/assets/less/'
		];

		$totalFileCount = 0;
		$allFileList = [];

		// Scan all asset directories
		foreach ($assetDirectories as $type => $assetsDir) {
			if (!is_dir($assetsDir)) {
				echo sprintf("Assets directory not found: %s (skipping)\n", $assetsDir);
				continue;
			}

			$files = new RecursiveIteratorIterator(
				new RecursiveDirectoryIterator($assetsDir, FilesystemIterator::SKIP_DOTS)
			);

			foreach ($files as $file) {
				if ($file->isFile()) {
					$filename = $file->getBasename();
					if ($this->parseAssetFilename($filename)) {
						$totalFileCount++;
						$allFileList[] = $file;
					}
				}
			}
		}

		echo sprintf("Found %d asset files (JS/CSS/LESS). Attempting to fetch library information from libraries.io\n", $totalFileCount);

		// Count how many will need API calls (not cached)
		$apiCallsNeeded = 0;
		foreach ($allFileList as $file) {
			$filename = $file->getBasename();
			$info = $this->parseAssetFilename($filename);
			if ($info) {
				$name = $info['name'];
				if (!$this->getCachedLibrariesIoData($name)) {
					$apiCallsNeeded++;
				}
			}
		}

		if ($apiCallsNeeded > 0) {
			$estimatedTime = ceil($apiCallsNeeded / self::LIBRARIES_IO_RATE_LIMIT) * 60;
			echo sprintf("Need to make %d API calls to libraries.io (rate limit: %d/minute)\n", $apiCallsNeeded, self::LIBRARIES_IO_RATE_LIMIT);
			echo sprintf("Estimated time: %d seconds (%d minutes)\n", $estimatedTime, ceil($estimatedTime / 60));
		}

		$processed = 0;
		foreach ($allFileList as $file) {
			$filename = $file->getBasename();
			$info = $this->parseAssetFilename($filename);
			if ($info) {
				$name = $info['name'];
				$processed++;
				echo sprintf("Processing %d/%d: %s\n", $processed, $totalFileCount, $name);
				
				// Try to fetch additional information from libraries.io
				$librariesIoData = $this->fetchLibrariesIoData($name);
				if ($librariesIoData) {
					$info['description'] = $librariesIoData['description'] ?? null;
					$info['homepage'] = $librariesIoData['homepage'] ?? null;
					$info['license'] = $librariesIoData['license'] ?? null;
					$info['found_as'] = $librariesIoData['found_as'] ?? null;
				}
				
				$assets[$name] = $info;
			}
		}

		ksort($assets);
		return $assets;
	}

	public function generateAssetsMd(){
		$assets = $this->getAssetFiles();
		$jsLines = [];
		$styleLines = [];
		foreach ($assets as $name => $info) {
			$version = $info['version'] ?? '';
			$type = $info['type'] ?? '';
			$description = $info['description'] ?? '';

			if (empty($version) || empty($type) && empty($description)) {
				continue;
			}
			if( strpos($type, 'js') !== false) {
				$jsLines[] = sprintf("- [%s](%s) - Version: %s, %s", 
					$name, 
					$info['homepage'] ?? $info['fullPath'], 
					$version, 
					!empty($description) ? " - {$description}" : ''
				);
			} elseif (strpos($type, 'css') !== false || strpos($type, 'less') !== false) {
				$styleLines[] = sprintf("- [%s](%s) - Version: %s, %s", 
					$name, 
					$info['homepage'] ?? '', 
					$version,
					!empty($description) ? " - {$description}" : ''
				);
			}
		}
		return array(
			'js' => implode(PHP_EOL, $jsLines),
			'styles' => implode(PHP_EOL, $styleLines),
		);
	}

	public function parseAssetFilename(string $filename): ?array
	{
		// Enhanced regex to better handle different naming patterns
		if (preg_match('/^(.+?)(?:-((?:\d+(?:\.\d+)+)))?(?:\.(min|latest))?\.(js|css|less|woff2?|ttf|otf)$/i', $filename, $matches)) {
			$name = $matches[1];
			$version = $matches[2] ?? null;
			$minified = isset($matches[3]) ? $matches[3] : null;
			$fileExtension = strtolower($matches[4]);
			
			// Clean up common patterns in library names
			$name = preg_replace('/[-_](hi-IN|en-US|min|latest|dist)$/i', '', $name);
			
			// Determine file type
			$type = $fileExtension;
			if ($minified) {
				$type = $minified . '.' . $fileExtension;
			}
			
			// Determine the correct assets directory based on file extension
			$assetsDir = $this->webroot . '/admin/assets/';
			switch ($fileExtension) {
				case 'css':
					$assetsDir .= 'css/';
					break;
				case 'less':
					$assetsDir .= 'less/';
					break;
				case 'js':
				default:
					$assetsDir .= 'js/';
					break;
			}
			
			return [
				'name' => $name,
				'version' => $version,
				'type' => $type,
				'fullPath' => $assetsDir . $filename,
			];
		}
		return null;
	}

}

try {
	$options = getopt('', ['version:', 'asterisk-versions:', 'webroot:', 'readmepath:']);
	$version = $options['version'] ?? null;
	$asteriskVersions = isset($options['asterisk-versions'])
		? sprintf("Supported Asterisk versions: %s", $options['asterisk-versions'])
		: null;
	$webroot = $options['webroot'] ?? null;
	$readmePath = $options['readmepath'] ?? null;

	$generator = new FrameworkReadmeGenerator($version, $asteriskVersions, $webroot);
	$readme = $generator->generateReadme();
	if ($readmePath) {
		file_put_contents($readmePath, $readme);
	}else{
		echo $readme;
	}
} catch (Exception $e) {
	fprintf(STDERR, "Error: %s\n", $e->getMessage());
	exit(1);
}