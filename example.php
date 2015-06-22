<?php

// Download CwsDump at https://github.com/crazy-max/CwsDump
require_once '../CwsDump/class.cws.dump.php';
$cwsDump = new CwsDump();

// Download CwsDebug at https://github.com/crazy-max/CwsDebug
require_once '../CwsDebug/class.cws.debug.php';
$cwsDebug = new CwsDebug($cwsDump);
$cwsDebug->setReportVerbose();
$cwsDebug->setEchoMode();

// Download CwsCurl at https://github.com/crazy-max/CwsCurl
require_once '../CwsCurl/class.cws.curl.php';
$cwsCurl = new CwsCurl(new CwsDebug($cwsDump));

require_once 'class.cws.ovhld.php';

$cwsOvhLogsDownloader = new CwsOvhLogsDownloader($cwsDebug, $cwsCurl);
$cwsOvhLogsDownloader->setNic(''); // The OVH NIC-handle (e.g. AB1234-OVH)
$cwsOvhLogsDownloader->setPassword(''); // The OVH NIC-handle password
$cwsOvhLogsDownloader->setDomain(''); // Your OVH domain (e.g. crazyws.fr)
$cwsOvhLogsDownloader->setDlPath('logs/'); // The download directory (default 'logs/')
$cwsOvhLogsDownloader->setDlEnable(true); // Enable download (default false)
$cwsOvhLogsDownloader->setOverwrite(true); // Enable overwrite of existing logs (default false)

// retrieve Apache access logs from May 2015
$logs = $cwsOvhLogsDownloader->getLogsWeb(2015, 5);

// retrieve Apache error logs from May 2015
//$logs = $cwsOvhLogsDownloader->getLogsError(2015, 5);

// retrieve all Apache access logs
//$logs = $cwsOvhLogsDownloader->getLogsWeb();

// retrieve all FTP sessions logs
//$logs = $cwsOvhLogsDownloader->getLogsOut();

// retrieve all logs from May 2015
//$logs = $cwsOvhLogsDownloader->getAll(2015, 5);

// retrieve all logs
//$logs = $cwsOvhLogsDownloader->getAll();
