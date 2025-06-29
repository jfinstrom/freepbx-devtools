<p align="center">
  <a href="https://www.freepbx.org/">
    <img src="https://www.freepbx.org/wp-content/uploads/Sangoma_FreePBX_Logo_RGB_hori-pos-e1588854523908.png" alt="FreePBX Logo" width="250">
  </a>
</p>

<p align="center">
  A versatile, open-source IP PBX, built on the power of Asterisk.
</p>

<p align="center">

<a href="https://www.gnu.org/licenses/gpl-3.0.en.html"><img src="https://img.shields.io/badge/License-GPLv3-blue.svg?style=for-the-badge" alt="License: GPL v3"></a>
<a href="https://community.freepbx.org/"><img src="https://img.shields.io/badge/Community-Forum-orange?style=for-the-badge" alt="Community Forum"></a>

</p>

---

_FreePBX_ is a communications platform that leverages _Asterisk_ to create sophisticated voice, video, and messaging solutions. It offers a user-friendly web interface and a modular architecture, allowing you to tailor your PBX system with a wide range of features. From standard call management to complex call center operations, FreePBX provides the foundation for a modern and scalable communication system.

FreePBX and Asterisk are Registered Trademarks of [Sangoma Technologies, Inc].

## ✨ Features

-   **User-Friendly Web UI:** Manage your complex PBX system through an intuitive graphical interface.
-   **Modular Architecture:** Add and remove features as needed with a wide variety of available modules.
-   **Scalable:** Suitable for small businesses to large enterprise call centers.
-   **Open Source:** Built by a vibrant community and free to use under the GPLv3 license.
-   **Rich Feature Set:** Includes IVRs, call queues, reporting, voicemail, conferencing, and much more.

## 🚀 Getting Started

### Prerequisites

Ensure you have a compatible environment. For detailed requirements, please see the [official documentation](https://sangomakb.atlassian.net/wiki/spaces/FP/pages/9732130/Install+FreePBX).

### Installation

```sh
# Clone the repository
git clone [https://github.com/FreePBX/framework.git](https://github.com/FreePBX/framework.git)
cd framework

# Run the installation script
./install -n
```

## 🛠️ Tech Stack

FreePBX uses a number of open-source projects to work properly. The core components include:

-   **[Asterisk]**: The telephony engine that powers FreePBX.
-   **[PHP]**: The backend scripting language.
-   **[MariaDB]**: The database used for storing configuration and call data.
-   **[Apache HTTP Server]**: The web server that delivers the FreePBX interface.

<details>
<summary>Click to view all backend libraries and dependencies</summary>

#### Backend

-   [Asterisk] - Software PBX. Version: `Supported Asterisk versions: 18, 19, 20, 21, 22`, License: GPL
-   [PHP] - General-purpose scripting language. License: PHP License
-   [MariaDB] - Community-developed fork of MySQL. License: GPL
-   [Apache HTTP Server] - Open-source HTTP server. License: Apache License 2.0
-   [Pear Console::Getopt] - PHP implementation of "getopt". License: PHP License
-   [dialparties.agi] - Ported to PHP by the FreePBX community. License: GPL
-   [CDR (Call Detail Records) Application] - Originally by Arezqui Belaid. License: GPL
-   [CssMin] - A simple CSS minifier. License: MIT
-   [Codeigniter helpers/librarires] - License: GPL

#### PHP Libraries

-   [symfony/console](https://github.com/symfony/console) - Eases the creation of beautiful and testable command line interfaces. License MIT
-   [symfony/contracts](https://github.com/symfony/contracts) - A set of abstractions extracted out of the Symfony components. License MIT
-   [symfony/security-core](https://github.com/symfony/security-core) - Symfony Security Component - Core Library. License MIT
-   [symfony/security-http](https://github.com/symfony/security-http) - Symfony Security Component - HTTP Integration. License MIT
-   [symfony/security-csrf](https://github.com/symfony/security-csrf) - Symfony Security Component - CSRF Library. License MIT
-   [symfony/security-guard](https://github.com/symfony/security-guard) - Symfony Security Component - Guard. License MIT
-   [symfony/browser-kit](https://github.com/symfony/browser-kit) - Simulates the behavior of a web browser, allowing you to make requests, click on links and submit forms programmatically. License MIT
-   [symfony/css-selector](https://github.com/symfony/css-selector) - Converts CSS selectors to XPath expressions. License MIT
-   [symfony/process](https://github.com/symfony/process) - Executes commands in sub-processes. License MIT
-   [symfony/filesystem](https://github.com/symfony/filesystem) - Provides basic utilities for the filesystem. License MIT
-   [symfony/lock](https://github.com/symfony/lock) - Creates and manages locks, a mechanism to provide exclusive access to a shared resource. License MIT
-   [symfony/finder](https://github.com/symfony/finder) - Finds files and directories via an intuitive fluent interface. License MIT
-   [symfony/translation](https://github.com/symfony/translation) - Provides tools to internationalize your application. License MIT
-   [filp/whoops](https://github.com/filp/whoops) - php error handling for cool kids. License MIT
-   [guzzlehttp/guzzle](https://github.com/guzzle/guzzle) - Guzzle is a PHP HTTP client library. License MIT
-   [wrep/daemonizable-command](https://github.com/mac-cain13/daemonizable-command) - Daemonizable (endless running) commands for Symfony.. License MIT
-   [mobiledetect/mobiledetectlib](https://github.com/serbanghita/Mobile-Detect) - Mobile_Detect is a lightweight PHP class for detecting mobile devices. It uses the User-Agent string combined with specific HTTP headers to detect the mobile environment.. License MIT
-   [sinergi/browser-detector](https://github.com/sinergi/php-browser-detector) - Detecting the user's browser, operating system and language.. License GPL-2.0
-   [respect/validation](https://github.com/Respect/Validation) - The most awesome validation engine ever created for PHP. License BSD Style
-   [neitanod/forceutf8](https://github.com/neitanod/forceutf8) - PHP Class Encoding featuring popular Encoding::toUTF8() function --formerly known as forceUTF8()-- that fixes mixed encoded strings.. License Unknown
-   [rmccue/requests](https://github.com/WordPress/Requests) - A HTTP library written in PHP, for human beings.. License ISC
-   [doctrine/dbal](https://github.com/doctrine/dbal) - Powerful PHP database abstraction layer (DBAL) with many features for database schema introspection and management.. License LGPL
-   [doctrine/cache](https://github.com/doctrine/cache) - PHP Doctrine Cache library is a popular cache implementation that supports many different drivers such as redis, memcache, apc, mongodb and others.. License MIT
-   [doctrine/orm](https://github.com/doctrine/orm) - Object-Relational-Mapper for PHP. License LGPL
-   [fightbulc/moment](https://github.com/fightbulc/moment.php) - Parse, validate, manipulate, and display dates in PHP w/ i18n support. Inspired by moment.js. License MIT
-   [swiftmailer/swiftmailer](https://github.com/swiftmailer/swiftmailer) - Swiftmailer, free feature-rich PHP mailer. License MIT
-   [ramsey/uuid](https://github.com/ramsey/uuid) - A PHP library for generating and working with universally unique identifiers (UUIDs).. License MIT
-   [brick/math](https://github.com/brick/math) - Arbitrary-precision arithmetic library. License MIT
-   [nesbot/carbon](https://github.com/CarbonPHP/carbon) - An API extension for DateTime that supports 281 different languages.. License MIT
-   [malkusch/lock](https://github.com/php-lock/lock) - Mutex library for exclusive code execution.. License WTFPL
-   [tedivm/jshrink](https://github.com/tedious/JShrink) - Javascript Minifier built in PHP. License BSD-3-Clause
-   [composer/ca-bundle](https://github.com/composer/ca-bundle) - Lets you find a path to the system CA bundle, and includes a fallback to the Mozilla CA bundle.. License MIT
-   [hhxsv5/php-sse](https://github.com/hhxsv5/php-sse) - A simple and efficient library implemented HTML5's server-sent events by PHP, is used to real-time push events from server to client, and easier than Websocket, instead of AJAX request.. License MIT
-   [sepia/po-parser](https://github.com/pherrymason/PHP-po-parser) - Gettext \*.PO file parser for PHP.. License MIT
-   [simplepie/simplepie](https://github.com/simplepie/simplepie) - A simple Atom/RSS parsing library for PHP. License BSD-3-Clause
-   [symfony/var-dumper](https://github.com/symfony/var-dumper) - Provides mechanisms for walking through any arbitrary PHP variable. License MIT
-   [splitbrain/php-archive](https://github.com/splitbrain/php-archive) - Pure-PHP implementation to read and write TAR and ZIP archives. License MIT
-   [slim/slim](https://github.com/slimphp/Slim) - Slim is a PHP micro framework that helps you quickly write simple yet powerful web applications and APIs. License MIT
-   [alchemy/zippy](https://github.com/alchemy-fr/Zippy) - Zippy, the archive manager companion. License MIT
-   [symfony/polyfill-php70](https://github.com/symfony/polyfill-php70) - Symfony polyfill backporting some PHP 7.0+ features to lower PHP versions. License MIT
-   [symfony/polyfill-php71](https://github.com/symfony/polyfill-php71) - Symfony polyfill backporting some PHP 7.1+ features to lower PHP versions. License MIT
-   [symfony/polyfill-php72](https://github.com/symfony/polyfill-php72) - Symfony polyfill backporting some PHP 7.2+ features to lower PHP versions. License MIT
-   [symfony/polyfill-php73](https://github.com/symfony/polyfill-php73) - Symfony polyfill backporting some PHP 7.3+ features to lower PHP versions. License MIT
-   [giggsey/libphonenumber-for-php](https://github.com/giggsey/libphonenumber-for-php) - A library for parsing, formatting, storing and validating international phone numbers, a PHP Port of Google's libphonenumber.. License Apache-2.0
-   [monolog/monolog](https://github.com/Seldaek/monolog) - Sends your logs to files, sockets, inboxes, databases and various web services. License MIT
-   [mtdowling/cron-expression](https://github.com/mtdowling/cron-expression) - CRON for PHP: Calculate the next or previous run date and determine if a CRON expression is due. License MIT
-   [povils/figlet](https://github.com/povils/figlet) - Figlet text generator - PHP. License MIT
-   [symfony/debug](https://github.com/symfony/debug) - Provides tools to ease debugging PHP code. License MIT

#### JavaScript Libraries

-   [Sortable](/var/www/html/admin/assets/js/Sortable-1.15.0.min.js) - Version: 1.15.0,
-   [autosize](/var/www/html/admin/assets/js/autosize-5.0.1.min.js) - Version: 5.0.1,
-   [browser-locale](https://github.com/maxogden/browser-locale#readme) - Version: 1.0.0, - get the user's selected language locale from the navigator object from client-side js
-   [chosen.jquery](/var/www/html/admin/assets/js/chosen.jquery-1.8.7.min.js) - Version: 1.8.7,
-   [eventsource](/var/www/html/admin/assets/js/eventsource-1.0.24.min.js) - Version: 1.0.24,
-   [jed](https://github.com/SlexAxton/Jed#readme) - Version: 1.1.1, - Gettext Style i18n for Modern JavaScript Apps
-   [jquery](https://jquery.com) - Version: 3.6.0, - JavaScript library for DOM operations
-   [jquery-migrate](/var/www/html/admin/assets/js/jquery-migrate-3.0.0.js) - Version: 3.0.0,
-   [jquery.fileupload](/var/www/html/admin/assets/js/jquery.fileupload-10.32.0.js) - Version: 10.32.0,
-   [jquery.fileupload-process](/var/www/html/admin/assets/js/jquery.fileupload-process-10.32.0.js) - Version: 10.32.0,
-   [jquery.form](/var/www/html/admin/assets/js/jquery.form-4.3.0.min.js) - Version: 4.3.0,
-   [jquery.hotkeys](/var/www/html/admin/assets/js/jquery.hotkeys-0.2.0.js) - Version: 0.2.0,
-   [jquery.iframe-transport](/var/www/html/admin/assets/js/jquery.iframe-transport-10.32.0.js) - Version: 10.32.0,
-   [jquery.jplayer](/var/www/html/admin/assets/js/jquery.jplayer-2.9.2.min.js) - Version: 2.9.2,
-   [jquery.numeric](/var/www/html/admin/assets/js/jquery.numeric-1.4.1.min.js) - Version: 1.4.1,
-   [jquery.selector-set](/var/www/html/admin/assets/js/jquery.selector-set-0.2.2.js) - Version: 0.2.2,
-   [jquery.smartWizard](/var/www/html/admin/assets/js/jquery.smartWizard-3.3.1.js) - Version: 3.3.1,
-   [jquery.tablednd](/var/www/html/admin/assets/js/jquery.tablednd-0.9.1.min.js) - Version: 0.9.1,
-   [js.cookie](/var/www/html/admin/assets/js/js.cookie-3.0.1.min.js) - Version: 3.0.1,
-   [modernizr](https://github.com/Modernizr/Modernizr) - Version: 3.3.1, - Modernizr is a JavaScript library that detects HTML5 and CSS3 features in the user’s browser.
-   [moment-duration-format](/var/www/html/admin/assets/js/moment-duration-format-2.2.1.js) - Version: 2.2.1,
-   [moment-timezone-with-data-1970-2030](/var/www/html/admin/assets/js/moment-timezone-with-data-1970-2030-0.5.41.min.js) - Version: 0.5.41,
-   [moment-with-locales](/var/www/html/admin/assets/js/moment-with-locales-2.20.1.min.js) - Version: 2.20.1,
-   [selector-set](/var/www/html/admin/assets/js/selector-set-1.1.0.js) - Version: 1.1.0,
-   [tableexport](https://tableexport.travismclarke.com/) - Version: 1.26.0, - The simple, easy-to-implement library to export HTML tables to xlsx, xls, csv, and txt files
-   [typeahead.bundle](/var/www/html/admin/assets/js/typeahead.bundle-0.10.5.min.js) - Version: 0.10.5,
-   [zxcvbn](/var/www/html/admin/assets/js/zxcvbn-4.4.0.min.js) - Version: 4.4.0,

</details>

<details>
<summary>Click to view visual and audio asset information</summary>

### Visuals

FreePBX uses a number of visual graphics packages to render properly:

-   [bootstrap]() - Version: 4.6.1,
-   [bootstrap-select]() - Version: 1.13.14,
-   [font-awesome.min]() - Version: 4.7.0,
-   [text-editor]() - Version: 1.2.1,
-   [typehead.js-bootstrap3]() - Version: 0.2.3,

##### Individual Modules may use additional libraries. These can usually be seen in their `composer.json` or `package.json` file.

### Music on Hold

FreePBX incorporates royalty-free music files distributed under the [Creative Commons Attribution-ShareAlike 3.0](http://creativecommons.org/licenses/by-sa/3.0/) license.

-   [macroform-cold_day] - Paul Shuler (Macroform)
-   [macroform-robot_dity] - Paul Shuler (Macroform)
-   [macroform-the_simplicity] - Paul Shuler (Macroform)
-   [manolo_camp-morning_coffee] - Manolo Camp
-   [reno_project-system] - Reno Project

</details>

## 🤝 Contributing

Please see our [developer docs](https://sangomakb.atlassian.net/wiki/spaces/FP/pages/10584285/FreePBX+Development) for details on our code of conduct, and the process for submitting pull requests to us. Bug reports and feature requests can be submitted through our [issue tracker](https://github.com/FreePBX/issue-tracker)

## ⚖️ License

Distributed under the GPLv3 License. See `LICENSE` for more information.

---

> _Open source: Empowering the many, not the few._

[Asterisk (PBX)]: http://www.asterisk.org
[GPLv3]: https://www.gnu.org/licenses/gpl-3.0.en.html
[Sangoma Technologies, Inc]: https://www.sangoma.com/
[PHP]: https://www.php.net
[MariaDB]: https://mariadb.org
[Apache HTTP Server]: https://httpd.apache.org
[pear console::getopt]: https://pear.php.net/package/Console_Getopt
[asterisk]: http://www.asterisk.org
[dialparties.agi]: http://www.sprackett.com
[CDR (Call Detail Records) Application]: http://www.asterisk-stats.org
[spandsp]: http://opencall.org
[Silk Icon Set]: http://www.famfamfam.com/lab/icons/silk/
[CssMin]: http://opensource.org/licenses/mit-license.php
[Codeigniter helpers/librarires]: http://codeigniter.com/user_guide/license.html
[macroform-cold_day]: http://macroform.bandcamp.com/
[macroform-robot_dity]: http://macroform.bandcamp.com/
[macroform-the_simplicity]: http://macroform.bandcamp.com/
[manolo_camp-morning_coffee]: http://ccmixter.org/people/ManoloCamp
[reno_project-system]: http://www.jamendo.com/en/album/23661
[pear db]: http://pear.php.net/package/DB
[pear console::getopt]: http://pear.php.net/package/Console_Getopt
