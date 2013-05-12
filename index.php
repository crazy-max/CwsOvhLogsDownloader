<?php

include('class.cws.ovhld.php');

$cwsOvhLogsDownloader = new CwsOvhLogsDownloader();
$cwsOvhLogsDownloader->debug_verbose    = CWSOVHLD_VERBOSE_DEBUG;  // default : CWSOVHLD_VERBOSE_SIMPLE
$cwsOvhLogsDownloader->nic              = "";                      // The OVH NIC-handle (e.g. AB1234-OVH)
$cwsOvhLogsDownloader->password         = "";                      // The OVH NIC-handle password
$cwsOvhLogsDownloader->domain           = "";                      // Your OVH domain (e.g. crazyws.fr)
$cwsOvhLogsDownloader->dl_path          = "logs/";                 // The download directory ; default : logs/
$cwsOvhLogsDownloader->dl_enable        = true;                    // Enable download or not ; default : false
$cwsOvhLogsDownloader->overwrite        = true;                    // Enable overwrite of existing logs or not ; default : false

// retrieve Apache access logs from May 2013
$dateTypeLogs = $cwsOvhLogsDownloader->getByDateAndType(2013, 5);

// retrieve Apache error logs from May 2013
//$dateTypeLogs = $cwsOvhLogsDownloader->getByDateAndType(2013, 5, CWSOVHLD_LOGS_APACHE_ERROR);

// retrieve all Apache access logs
//$typeLogs = $cwsOvhLogsDownloader->getByType();

// retrieve all FTP sessions logs
//$typeLogs = $cwsOvhLogsDownloader->getByType(CWSOVHLD_LOGS_FTP);

// retrieve all logs
//$allLogs = $cwsOvhLogsDownloader->getAll();

?>