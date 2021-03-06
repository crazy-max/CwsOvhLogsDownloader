[![Latest Stable Version](https://img.shields.io/packagist/v/crazy-max/cws-ovh-logs-downloader.svg?style=flat-square)](https://packagist.org/packages/crazy-max/cws-ovh-logs-downloader)
[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%205.3.0-8892BF.svg?style=flat-square)](https://php.net/)
[![Build Status](https://img.shields.io/travis/com/crazy-max/CwsOvhLogsDownloader/master.svg?style=flat-square)](https://travis-ci.com/crazy-max/CwsOvhLogsDownloader)
[![Code Quality](https://img.shields.io/codacy/grade/6ca828770e69476fa3d7773f831eec36.svg?style=flat-square)](https://www.codacy.com/app/crazy-max/CwsOvhLogsDownloader)
[![Become a sponsor](https://img.shields.io/badge/sponsor-crazy--max-181717.svg?logo=github&style=flat-square)](https://github.com/sponsors/crazy-max)
[![Donate Paypal](https://img.shields.io/badge/donate-paypal-00457c.svg?logo=paypal&style=flat-square)](https://www.paypal.me/crazyws)

## :warning: Abandoned project

This project is not maintained anymore and is abandoned. Feel free to fork and make your own changes if needed.

Thanks to everyone for their valuable feedback and contributions.

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

## How can I help ?

All kinds of contributions are welcome :raised_hands:! The most basic way to show your support is to star :star2: the project, or to raise issues :speech_balloon: You can also support this project by [**becoming a sponsor on GitHub**](https://github.com/sponsors/crazy-max) :clap: or by making a [Paypal donation](https://www.paypal.me/crazyws) to ensure this journey continues indefinitely! :rocket:

Thanks again for your support, it is much appreciated! :pray:

## License

MIT. See `LICENSE` for more details.
