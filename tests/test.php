<?php

require_once __DIR__.'/../vendor/autoload.php'; // Autoload files using Composer autoload

$cwsDebug = new Cws\CwsDebug();
$cwsDebug->setDebugVerbose();
$cwsDebug->setEchoMode();

$cwsCurl = new Cws\CwsCurl(new Cws\CwsDebug());

$cwsOvhLogsDownloader = new Cws\CwsOvhLogsDownloader($cwsDebug, $cwsCurl);
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
