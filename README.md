[![Latest Stable Version](https://img.shields.io/packagist/v/crazy-max/cws-ovh-logs-downloader.svg?style=flat-square)](https://packagist.org/packages/crazy-max/cws-ovh-logs-downloader)
[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%205.3.0-8892BF.svg?style=flat-square)](https://php.net/)
[![Build Status](https://img.shields.io/travis/crazy-max/CwsOvhLogsDownloader/master.svg?style=flat-square)](https://travis-ci.org/crazy-max/CwsOvhLogsDownloader)
[![Code Quality](https://img.shields.io/codacy/grade/6ca828770e69476fa3d7773f831eec36.svg?style=flat-square)](https://www.codacy.com/app/crazy-max/CwsOvhLogsDownloader)
[![StyleCI](https://styleci.io/repos/9979083/shield?style=flat-square)](https://styleci.io/repos/9979083)
[![Libraries.io](https://img.shields.io/librariesio/github/crazy-max/CwsOvhLogsDownloader.svg?style=flat-square)](https://libraries.io/github/crazy-max/CwsOvhLogsDownloader)
[![Beerpay](https://img.shields.io/beerpay/crazy-max/CwsOvhLogsDownloader.svg?style=flat-square)](https://beerpay.io/crazy-max/CwsOvhLogsDownloader)
[![Donate Paypal](https://img.shields.io/badge/donate-paypal-7057ff.svg?style=flat-square)](https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=U2NGLLF484NX4)

## About

PHP class to download the Apache access and error, FTP, CGI, Out and SSH logs available on http://logs.ovh.net from a shared hosting.

## Installation

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

![](.res/example.png)

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

## How can i help ?

All kinds of contributions are welcomed :raised_hands:!<br />
The most basic way to show your support is to star :star2: the project, or to raise issues :speech_balloon:<br />
But we're not gonna lie to each other, I'd rather you buy me a beer or two :beers:!

[![Beerpay](https://beerpay.io/crazy-max/CwsOvhLogsDownloader/badge.svg?style=beer-square)](https://beerpay.io/crazy-max/CwsOvhLogsDownloader)
or [![Paypal](.res/paypal.svg)](https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=U2NGLLF484NX4)

## License

MIT. See `LICENSE` for more details.
