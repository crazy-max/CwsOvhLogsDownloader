CwsOvhLogsDownloader
====================

CwsOvhLogsDownloader is a PHP class to download the Apache access and error, FTP, CGI, Out and SSH logs available on http://logs.ovh.net from a shared hosting.

Installation
------------

* Enable the [php_curl](http://php.net/manual/en/book.curl.php) extension.
* Copy the ``class.cws.ovhld.php`` file in a folder on your server.
* Go to ``index.php`` to see an example.

![](http://static.crazyws.fr/resources/blog/2013/05/ovh-logs-downloader-php.png)

Options
-------

Public vars :

* **nic** - The OVH NIC-handle. (e.g. AB1234-OVH)
* **password** - The OVH NIC-handle password.
* **domain** - Your OVH domain (e.g. crazyws.fr).
* **dl_path** - The download directory.
* **dl_enable** - Enable download or not.
* **overwrite** - Enable overwrite of existing logs or not.
* **error_msg** - The last error message.
* **debug_verbose** - Control the debug output.

Public methods :

* **getByDateAndType** - Retrieve logs by date and type.
* **getByDate** - Retrieve logs by date.
* **getByType** - Retrieve logs by type.
* **getAll** - Retrieve all logs.
