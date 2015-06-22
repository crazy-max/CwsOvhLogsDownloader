# CwsOvhLogsDownloader

CwsOvhLogsDownloader is a PHP class to download the Apache access and error, FTP, CGI, Out and SSH logs available on http://logs.ovh.net from a shared hosting.

## Installation

* Enable the [php_curl](http://php.net/manual/en/book.curl.php) extension.
* Download [CwsDump](https://github.com/crazy-max/CwsDump), [CwsDebug](https://github.com/crazy-max/CwsDebug) and [CwsCurl](https://github.com/crazy-max/CwsCurl).
* Copy the ``class.cws.ovhld.php`` file in a folder on your server.

## Getting started

See ``example.php`` file sample to help you.

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
