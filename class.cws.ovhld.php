<?php

/**
 * CwsOvhLogsDownloader
 *
 * CwsOvhLogsDownloader is a PHP class to download the Apache access and error,
 * FTP, CGI, Out and SSH logs available on http://logs.ovh.net from a shared hosting.
 * 
 * CwsOvhLogsDownloader is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or (at your option)
 * or (at your option) any later version.
 *
 * CwsOvhLogsDownloader is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or
 * FITNESS FOR A PARTICULAR PURPOSE. See the GNU Lesser General Public License
 * for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with this program. If not, see http://www.gnu.org/licenses/.
 * 
 * Related post : http://goo.gl/T3HH8
 * 
 * @package CwsOvhLogsDownloader
 * @author Cr@zy
 * @copyright 2013-2014, Cr@zy
 * @license GNU LESSER GENERAL PUBLIC LICENSE
 * @version 1.3
 * @link https://github.com/crazy-max/CwsOvhLogsDownloader
 *
 */

define('CWSOVHLD_VERBOSE_QUIET',      0);       // means no output at all.
define('CWSOVHLD_VERBOSE_SIMPLE',     1);       // means only output simple report.
define('CWSOVHLD_VERBOSE_REPORT',     2);       // means output a detail report.
define('CWSOVHLD_VERBOSE_DEBUG',      3);       // means output detail report as well as debug info.

define('CWSOVHLD_LOGS_APACHE_WEB',    'web');   // Apache access logs
define('CWSOVHLD_LOGS_APACHE_ERROR',  'error'); // Apache error logs
define('CWSOVHLD_LOGS_FTP',           'ftp');   // FTP sessions logs
define('CWSOVHLD_LOGS_CGI',           'cgi');   // CGI scripts logs
define('CWSOVHLD_LOGS_OUT',           'out');   // External access logs from the server
define('CWSOVHLD_LOGS_SSH',           'ssh');   // SSH sessions logs

class CwsOvhLogsDownloader
{
    /**
     * CwsOvhLogsDownloader version.
     * @var string
     */
    public $version = "1.3";
    
    /**
     * The OVH NIC-handle. (e.g. AB1234-OVH)
     * More infos : http://guides.ovh.com/NicHandle
     * @var string
     */
    public $nic;
    
    /**
     * The OVH NIC-handle password.
     * @var string
     */
    public $password;
    
    /**
     * Your OVH domain (e.g. crazyws.fr).
     * @var string
     */
    public $domain;
    
    /**
     * The download directory.
     * default logs/
     * @var string
     */
    public $dl_path = "logs/";
    
    /**
     * Enable download or not.
     * default false
     * @var boolean
     */
    public $dl_enable = false;
    
    /**
     * Enable overwrite of existing logs or not.
     * default false
     * @var boolean
     */
    public $overwrite = false;
    
    /**
     * The last error message.
     * @var string
     */
    public $error_msg;
    
    /**
     * Control the debug output.
     * default CWSOVHLD_VERBOSE_QUIET
     * @var int
     */
    public $debug_verbose = CWSOVHLD_VERBOSE_QUIET;
    
    /**
     * The OVH logs url.
     * @var string
     */
    private $_url;
    
    /**
     * The OVH root logs list.
     * @var array
     */
    private $_rootLogs;
    
    /**
     * Defines new line ending.
     */
    private $_newline = "<br />\n";
    
    /**
     * Output additional msg for debug.
     * @param string $msg : if not given, output the last error msg.
     * @param int $verbose_level : the output level of this message.
     * @param boolean $newline : insert new line or not.
     * @param boolean $code : is code or not.
     */
    private function output($msg=false, $verbose_level=CWSOVHLD_VERBOSE_SIMPLE, $newline=true, $code=false)
    {
        if ($this->debug_verbose >= $verbose_level) {
            if (empty($msg) && !$code) {
                echo 'ERROR: ' . $this->error_msg;
            } else {
                if ($code) {
                    echo '<textarea style="width:100%;height:300px;">';
                    print_r($msg);
                    echo '</textarea>';
                } else {
                    echo $msg;
                }
            }
            if ($newline) {
                echo $this->_newline;
            }
        }
    }
    
    /**
     * Retrieve logs by date and type.
     * @param int $year
     * @param int $month
     * @param string $type : default CWSOVHLD_LOGS_APACHE_WEB
     * @return array
     */
    public function getByDateAndType($year, $month, $type=CWSOVHLD_LOGS_APACHE_WEB)
    {
        $this->output('<h2>getByDateAndType</h2>', CWSOVHLD_VERBOSE_SIMPLE, false);
        $this->output('<strong>Date : </strong>' . $month . '/' . $year, CWSOVHLD_VERBOSE_SIMPLE);
        $this->output('<strong>Type : </strong>' . $type, CWSOVHLD_VERBOSE_SIMPLE);
        
        set_time_limit(0);
        $result = array(
            'type'        => $type,
            'download'    => false,
            'logs'        => false,
        );
        
        if (!empty($year) && !empty($month)) {
            $month = strlen($month) == 1 ? "0" . $month : $month;
            if ($this->_rootLogs == null || !is_array($this->_rootLogs)) {
                $this->procRootLogs($type);
            }
            if (in_array($type . "-" . $month . "-" . $year, $this->_rootLogs)) {
                $src = $this->getContent("logs-" . $month . "-" . $year . ($type != CWSOVHLD_LOGS_APACHE_WEB ? '/' . $type : ''));
                $logs = $this->parse($src, '#<a href="' . $this->domain . '-(.*?)">#', ".log.gz");
                if (!empty($logs)) {
                    foreach ($logs as $log) {
                        $result['logs'][$month . "-" . $year][] = $log;
                    }
                }
            } else {
                $this->error_msg = "No log found...";
                $this->output();
                return $result;
            }
        } else {
            $this->error_msg = "Year or month are not valid...";
            $this->output();
            return $result;
        }
        
        if (!empty($result) && $this->dl_enable) {
            $result['download'] = $this->procDownload($result['logs'], $type);
        }
        
        $this->output('<h2>getByDateAndType result</h2>', CWSOVHLD_VERBOSE_DEBUG, false);
        $this->output($result, CWSOVHLD_VERBOSE_DEBUG, false, true);
        
        return $result;
    }
    
    /**
     * Retrieve logs by date.
     * @param int $year
     * @param int $month
     * @return array
     */
    public function getByDate($year, $month)
    {
        $result = array();
    
        $result[] = $this->getByDateAndType($year, $month, CWSOVHLD_LOGS_APACHE_WEB);
        $result[] = $this->getByDateAndType($year, $month, CWSOVHLD_LOGS_APACHE_ERROR);
        $result[] = $this->getByDateAndType($year, $month, CWSOVHLD_LOGS_FTP);
        $result[] = $this->getByDateAndType($year, $month, CWSOVHLD_LOGS_CGI);
        $result[] = $this->getByDateAndType($year, $month, CWSOVHLD_LOGS_OUT);
        $result[] = $this->getByDateAndType($year, $month, CWSOVHLD_LOGS_SSH);
    
        return $result;
    }
    
    /**
     * Retrieve logs by type.
     * @param string $type : default CWSOVHLD_LOGS_APACHE_WEB
     * @return array
     */
    public function getByType($type=CWSOVHLD_LOGS_APACHE_WEB)
    {
        $this->output('<h2>getByType</h2>', CWSOVHLD_VERBOSE_SIMPLE, false);
        $this->output('<strong>Type : </strong>' . $type, CWSOVHLD_VERBOSE_SIMPLE);
        
        set_time_limit(0);
        $result = array(
            'type'        => $type,
            'download'    => false,
            'logs'        => false,
        );
        
        $dl_esc = true;
        if ($this->dl_enable) {
            $dl_esc = false;
        }
        
        $this->procRootLogs($type);
        foreach ($this->_rootLogs as $date) {
            $exDate = explode("-", $date);
            $year = $exDate[2];
            $month = $exDate[1];
            $files = $this->getByDateAndType($year, $month, $type);
            if ($files !== false) {
                $result['logs'][$month . "-" . $year] = $files['logs'][$month . "-" . $year];
            }
        }
        
        $this->dl_enable = !$dl_esc;
        if (!empty($result['logs']) && $this->dl_enable) {
            $result['download'] = $this->procDownload($result['logs'], $type);
        } else {
            $this->error_msg = "No log found...";
            $this->output();
            return $result;
        }
        
        $this->output('<h2>getByType result</h2>', CWSOVHLD_VERBOSE_DEBUG, false);
        $this->output($result, CWSOVHLD_VERBOSE_DEBUG, false, true);
        
        return $result;
    }
    
    /**
     * Retrieve all logs.
     * @return array
     */
    public function getAll()
    {
        $result = array();
    
        $result[] = $this->getByType(CWSOVHLD_LOGS_APACHE_WEB);
        $result[] = $this->getByType(CWSOVHLD_LOGS_APACHE_ERROR);
        $result[] = $this->getByType(CWSOVHLD_LOGS_FTP);
        $result[] = $this->getByType(CWSOVHLD_LOGS_CGI);
        $result[] = $this->getByType(CWSOVHLD_LOGS_OUT);
        $result[] = $this->getByType(CWSOVHLD_LOGS_SSH);
    
        return $result;
    }
    
    /**
     * Retrieve root logs available from the main domain page.
     * @param string $type : default CWSOVHLD_LOGS_APACHE_WEB
     */
    private function procRootLogs($type=CWSOVHLD_LOGS_APACHE_WEB)
    {
        $this->output('<h2>procRootLogs</h2>', CWSOVHLD_VERBOSE_DEBUG, false);
        
        set_time_limit(0);
        $this->_rootLogs = array();
    
        if (!empty($this->nic) && !empty($this->password) && !empty($this->domain)) {
            $this->_url = "https://logs.ovh.net/" . $this->domain;
    
            $src = $this->getContent();
            $links = $this->parse($src, '#<a href="(.*?)">' . $type . '</a>#', "logs-");
            if (!empty($links)) {
                foreach ($links as $link) {
                    $link = substr($link, 0, 12);
                    $explode = explode("-", $link);
                    $year = $explode[2];
                    $month = $explode[1];
                    $this->_rootLogs[] = $type . "-" . $month . "-" . $year;
                }
            }
        }
        
        if (empty($this->_rootLogs)) {
            $this->error_msg = "Root logs empty... Please check your OVH nic/password/domain.";
            $this->output();
            exit();
        }
        
        $this->output('<h3>procRootLogs result</h3>', CWSOVHLD_VERBOSE_DEBUG, false);
        $this->output($this->_rootLogs, CWSOVHLD_VERBOSE_DEBUG, false, true);
    }
    
    /**
     * Process download of available logs.
     * @param array $resultLogs
     * @param string $type : default CWSOVHLD_LOGS_APACHE_WEB
     * @return array
     */
    private function procDownload($resultLogs, $type)
    {
        $this->output('<h2>procDownload</h2>', CWSOVHLD_VERBOSE_REPORT, false);
        
        $result = array(
            'count'    => 0,
            'size'     => 0,
            'time'     => 0,
        );
        
        if (empty($this->dl_path)) {
            $this->error_msg = '<strong>dl_path</strong> is required!';
            $this->output();
            return;
        }
        
        $result['time'] = $this->getMicrotime();
        
        foreach ($resultLogs as $date => $logs) {
            $exDate = explode("-", $date);
            $year = $exDate[1];
            $month = $exDate[0];
            
            $path = $this->endWith($this->dl_path, '/') ? $this->dl_path : $this->dl_path . '/';
            $path = $path . $year . '/' . $month;
            if (!file_exists($path)) {
                mkdir($path, 0, true);
            }
            
            foreach ($logs as $log) {
                $exDate = explode("-", $log);
                $day = $exDate[0];
                
                $content = $this->getContent('logs-' . $month . '-' . $year . '/' . $this->domain . '-' . $day . '-' . $month . '-' . $year . '.log.gz');
                $target = $path . '/' . $type . '-' . $year . '-' . $month . '-' . $day . '.log.gz';
                
                if (!empty($content) && (!file_exists($target) || $this->overwrite)) {
                    $handle = fopen($target, 'w');
                    fwrite($handle, $content);
                    fclose($handle);
                    
                    $result['count']++;
                    $result['size'] += filesize($target);
                }
            }
        }
        
        $result['size'] = $this->formatSize($result['size']);
        $result['time'] = round($this->getMicrotime() - $result['time'], 3);
        
        $this->output('<h3>procDownload result</h3>', CWSOVHLD_VERBOSE_REPORT, false);
        $this->output('<strong>Downloaded : </strong>' . $result['count'], CWSOVHLD_VERBOSE_REPORT);
        $this->output('<strong>Size : </strong>' . $result['size'], CWSOVHLD_VERBOSE_REPORT);
        $this->output('<strong>Time : </strong>' . $result['time'] . ' seconds', CWSOVHLD_VERBOSE_REPORT);
        
        return $result;
    }
    
    /**
     * Download a url's content using CwsCurl wrapper class.
     * This class can be downloaded at https://github.com/crazy-max/CwsCurl
     * @param string $params
     * @return string
     */
    private function getContent($params='')
    {
        $this->output('<h3>getContent</h3>', CWSOVHLD_VERBOSE_DEBUG, false);
        
        $url = $this->_url . '/' . $params;
        $this->output('<strong>Url : </strong>' . $url, CWSOVHLD_VERBOSE_DEBUG);
        
        $time = $this->getMicrotime();
        
        $cwsCurl = new CwsCurl();
        $cwsCurl->setUrl($url);
        $cwsCurl->setAuth($this->nic, $this->password);
        $cwsCurl->process();
        
        $this->output('<strong>Size : </strong>' . $this->formatSize(strlen($cwsCurl->getContent())), CWSOVHLD_VERBOSE_DEBUG);
        $this->output('<strong>Time : </strong>' . round($this->getMicrotime() - $time, 3) . ' seconds', CWSOVHLD_VERBOSE_DEBUG);
        
        if ($cwsCurl->getErrorMsg()) {
            $this->error_msg = $cwsCurl->getErrorMsg();
            $this->output();
            return false;
        }
        
        return $cwsCurl->getContent();
    }
    
    /**
     * Parse a web page
     * @param string $src
     * @param string $regexp
     * @param string $contain
     * @return array
     */
    private function parse($src, $regexp, $contain)
    {
        $result = array();
        
        preg_match_all($regexp, $src, $matches);
        if (!empty($matches)) {
            foreach ($matches[1] as $value) {
                if ($this->contains($value, $contain)) {
                    $result[] = $value;
                }
            }
        }
        
        return $result;
    }
    
    private static function contains($string, $search)
    {
        if (!empty($string) && !empty($search)) {
            if (stripos($string, $search) !== false) {
                return true;
            }
        }
        
        return false;
    }
    
    private static function endWith($string, $search)
    {
        $length = strlen($search);
        $start  = $length * -1;
        
        return (substr($string, $start) === $search);
    }
    
    private static function formatSize($size)
    {
        if ($size >= 1073741824) {
            $size = round($size / 1073741824 * 100) / 100 . "Go";
        } elseif ($size >= 1048576) {
            $size = round($size / 1048576 * 100) / 100 . "Mo";
        } else {
            $size = round($size / 1024 * 100) / 100 . "Ko";
        }
        
        return $size;
    }
    
    private static function getMicrotime()
    {
        list($usec, $sec) = explode(" ", microtime());
        return ((float) $usec + (float) $sec);
    }
}

?>
