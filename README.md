[![Latest Stable Version](https://img.shields.io/packagist/v/crazy-max/cws-ovh-logs-downloader.svg?style=flat-square)](https://packagist.org/packages/crazy-max/cws-ovh-logs-downloader)
[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%205.3.0-8892BF.svg?style=flat-square)](https://php.net/)
[![Build Status](https://img.shields.io/travis/crazy-max/CwsOvhLogsDownloader/master.svg?style=flat-square)](https://travis-ci.org/crazy-max/CwsOvhLogsDownloader)
[![Scrutinizer Code Quality](https://img.shields.io/scrutinizer/g/crazy-max/CwsOvhLogsDownloader.svg?style=flat-square)](https://scrutinizer-ci.com/g/crazy-max/CwsOvhLogsDownloader)
[![Gemnasium](https://img.shields.io/gemnasium/crazy-max/CwsOvhLogsDownloader.svg?style=flat-square)](https://gemnasium.com/github.com/crazy-max/CwsOvhLogsDownloader)

# CwsOvhLogsDownloader

PHP class to download the Apache access and error, FTP, CGI, Out and SSH logs available on http://logs.ovh.net from a shared hosting.

## Requirements

* PHP >= 5.3.0
* CwsCurl >= 1.8
* Enable the [php_curl](http://php.net/manual/en/book.curl.php) extension.

## Installation with Composer

```bash
composer require crazy-max/cws-ovh-logs-downloader
```

And download the code:

```bash
composer install # or update
```

## Getting started

See `tests/test.php` file sample to help you.

## Example

![](https://raw.github.com/crazy-max/CwsOvhLogsDownloader/master/example.png)

## Methods

**getLogsWeb** - Retrieve logs web.<br />
**getLogsError** - Retrieve logs error.<br />
**getLogsFtp** - Retrieve logs ftp.<br />
**getLogsCgi** - Retrieve logs cgi.<br />
**getLogsOut** - Retrieve logs out.<br />
**getLogsSsh** - Retrieve logs ssh.<br />
**getAll** - Retrieve all logs types.<br />

**setNic** - Set the OVH NIC-handle. (e.g. AB1234-OVH).<br />
**setPassword** - Set the OVH NIC-handle password.<br />
**getDomain** - Your OVH domain.<br />
**setDomain** - Set the OVH domain (e.g. crazyws.fr).<br />
**getDlPath** - The download directory.<br />
**setDlPath** - Set the download directory. (default 'logs/')<br />
**isDlEnable** - Is downloading enable.<br />
**setDlEnable** - Set download activation. (default false)<br />
**isOverwrite** - Is overwriting enable.<br />
**setOverwrite** - Set overwrite activation. (default false)<br />
**getError** - Get the last error.

## License

LGPL. See ``LICENSE`` for more details.

## More infos

http://www.crazyws.fr/dev/classes-php/cwsovhlogsdownloader-pour-telecharger-les-logs-ovh-JPZQ1.html
