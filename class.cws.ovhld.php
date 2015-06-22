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
 * @copyright 2013-2015, Cr@zy
 * @license GNU LESSER GENERAL PUBLIC LICENSE
 * @version 1.4
 * @link https://github.com/crazy-max/CwsOvhLogsDownloader
 *
 */

class CwsOvhLogsDownloader
{
    const LOGS_WEB = 'web'; // Apache access logs
    const LOGS_ERROR = 'error'; // Apache error logs
    const LOGS_FTP = 'ftp'; // FTP sessions logs
    const LOGS_CGI = 'cgi'; // CGI scripts logs
    const LOGS_OUT = 'out'; // External access logs from the server
    const LOGS_SSH = 'ssh'; // SSH sessions logs
    
    /**
     * The OVH NIC-handle. (e.g. AB1234-OVH)
     * More infos : http://guides.ovh.com/NicHandle
     * @var string
     */
    private $nic;
    
    /**
     * The OVH NIC-handle password.
     * @var string
     */
    private $password;
    
    /**
     * Your OVH domain (e.g. crazyws.fr).
     * @var string
     */
    private $domain;
    
    /**
     * The download directory.
     * default logs/
     * @var string
     */
    private $dlPath;
    
    /**
     * Enable download or not.
     * default false
     * @var boolean
     */
    private $dlEnable;
    
    /**
     * Enable overwrite of existing logs or not.
     * default false
     * @var boolean
     */
    private $overwrite;
    
    /**
     * The OVH logs url.
     * @var string
     */
    private $url;
    
    /**
     * The OVH root logs list.
     * @var array
     */
    private $rootLogs;
    
    /**
     * The last error message.
     * @var string
     */
    private $error;
    
    /**
     * The cws debug instance.
     * @var CwsDebug
     */
    private $cwsDebug;
    
    /**
     * The cws curl instance.
     * @var CwsCurl
     */
    private $cwsCurl;
    
    public function __construct(CwsDebug $cwsDebug, CwsCurl $cwsCurl)
    {
        $this->cwsDebug = $cwsDebug;
        $this->cwsCurl = $cwsCurl;
        
        $this->nic = null;
        $this->password = null;
        $this->domain = null;
        $this->dlPath = 'logs/';
        $this->dlEnable = false;
        $this->overwrite = false;
        $this->url = null;
        $this->rootLogs = array();
        $this->error = null;
    }
    
    /**
     * Retrieve logs web.
     * @param int $year (optional)
     * @param int $month (optional)
     * @return array
     */
    public function getLogsWeb($year = null, $month = null)
    {
        return $this->getLogs(self::LOGS_WEB, $year, $month);
    }
    
    /**
     * Retrieve logs error.
     * @param int $year (optional)
     * @param int $month (optional)
     * @return array
     */
    public function getLogsError($year = null, $month = null)
    {
        return $this->getLogs(self::LOGS_ERROR, $year, $month);
    }
    
    /**
     * Retrieve logs ftp.
     * @param int $year (optional)
     * @param int $month (optional)
     * @return array
     */
    public function getLogsFtp($year = null, $month = null)
    {
        return $this->getLogs(self::LOGS_FTP, $year, $month);
    }
    
    /**
     * Retrieve logs cgi.
     * @param int $year (optional)
     * @param int $month (optional)
     * @return array
     */
    public function getLogsCgi($year = null, $month = null)
    {
        return $this->getLogs(self::LOGS_CGI, $year, $month);
    }
    
    /**
     * Retrieve logs out.
     * @param int $year (optional)
     * @param int $month (optional)
     * @return array
     */
    public function getLogsOut($year = null, $month = null)
    {
        return $this->getLogs(self::LOGS_OUT, $year, $month);
    }
    
    /**
     * Retrieve logs ssh.
     * @param int $year (optional)
     * @param int $month (optional)
     * @return array
     */
    public function getLogsSsh($year = null, $month = null)
    {
        return $this->getLogs(self::LOGS_SSH, $year, $month);
    }
    
    /**
     * Retrieve all logs types.
     * @param int $year (optional)
     * @param int $month (optional)
     * @return array
     */
    public function getAll($year = null, $month = null)
    {
        return array(
            $this->getLogsWeb($year, $month),
            $this->getLogsError($year, $month),
            $this->getLogsFtp($year, $month),
            $this->getLogsCgi($year, $month),
            $this->getLogsOut($year, $month),
            $this->getLogsSsh($year, $month),
        );
    }
    
    /**
     * Retrieve logs by type.
     * @param string $type
     * @param int $year (optional)
     * @param int $month (optional)
     * @return array
     */
    private function getLogs($type, $year = null, $month = null)
    {
        $this->cwsDebug->titleH2('getLogs');
        $this->cwsDebug->labelValue('Type', $type);
        $this->cwsDebug->labelValue('Date', $month . '/' . $year);
        
        $result = array(
            'type' => $type,
            'download' => false,
            'logs' => false,
        );
        
        $byDate = !empty($year) && !empty($month);
        if ((empty($year) && !empty($month)) || (!empty($year) && empty($month))) {
            $this->error = 'Year or month are not valid...';
            $this->cwsDebug->error($this->error);
            return $result;
        } elseif ($byDate) {
            $month = strlen($month) == 1 ? '0' . $month : $month;
        }
        
        set_time_limit(0);
        
        if ($this->rootLogs == null || !is_array($this->rootLogs)) {
            $this->procRootLogs($type);
        }
        
        foreach ($this->rootLogs as $date) {
            $exDate = explode('-', $date);
            $aYear = $exDate[2];
            $aMonth = $exDate[1];
            
            if ($byDate && ($year != $aYear || $month != $aMonth)) {
                continue;
            } elseif (in_array($type . '-' . $aMonth . '-' . $aYear, $this->rootLogs)) {
                $src = $this->getContent('logs-' . $aMonth . '-' . $aYear . ($type != self::LOGS_WEB ? '/' . $type : ''));
                $logs = $this->parse($src, '#<a href="' . $this->domain . '-(.*?)">#', '.log.gz');
                if (!empty($logs)) {
                    foreach ($logs as $log) {
                        $result['logs'][$aMonth . '-' . $aYear][] = $log;
                    }
                }
            }
        }
        
        if (!empty($result['logs']) && $this->dlEnable) {
            $result['download'] = $this->procDownload($result['logs'], $type);
        } elseif (empty($result['logs'])) {
            $this->error = 'No log found...';
            $this->cwsDebug->error($this->error);
            return $result;
        }
        
        $this->cwsDebug->titleH2('Final result');
        $this->cwsDebug->dump('result', $result);
        return $result;
    }
    
    /**
     * Retrieve root logs available from the main domain page.
     * @param string $type
     */
    private function procRootLogs($type)
    {
        $this->cwsDebug->titleH3('procRootLogs', CwsDebug::VERBOSE_REPORT);
        $this->rootLogs = array();
        
        if (!empty($this->nic) && !empty($this->password) && !empty($this->domain)) {
            $this->url = 'https://logs.ovh.net/' . $this->domain;
            
            $src = $this->getContent();
            $links = $this->parse($src, '#<a href="(.*?)">' . $type . '</a>#', 'logs-');
            if (!empty($links)) {
                foreach ($links as $link) {
                    $link = substr($link, 0, 12);
                    $explode = explode('-', $link);
                    $year = $explode[2];
                    $month = $explode[1];
                    $this->rootLogs[] = $type . '-' . $month . '-' . $year;
                }
            }
        }
        
        if (empty($this->rootLogs)) {
            $this->error = 'Root logs empty... Please check your OVH nic/password/domain.';
            $this->cwsDebug->error($this->error);
            exit();
        }
        
        $this->cwsDebug->dump('result', $this->rootLogs, CwsDebug::VERBOSE_REPORT);
    }
    
    /**
     * Process download of available logs.
     * @param array $resultLogs
     * @param string $type
     * @return array
     */
    private function procDownload($resultLogs, $type)
    {
        $this->cwsDebug->titleH3('procDownload', CwsDebug::VERBOSE_REPORT);
        
        $result = array(
            'count' => 0,
            'size' => 0,
            'time' => 0,
        );
        
        if (empty($this->dlPath)) {
            $this->error = '<strong>dlPath</strong> is required!';
            $this->cwsDebug->error($this->error);
            return;
        }
        
        $result['time'] = $this->getMicrotime();
        
        foreach ($resultLogs as $date => $logs) {
            $exDate = explode('-', $date);
            $year = $exDate[1];
            $month = $exDate[0];
            
            $path = $this->endWith($this->dlPath, '/') ? $this->dlPath : $this->dlPath . '/';
            $path = $path . $year . '/' . $month;
            if (!file_exists($path)) {
                mkdir($path, 0, true);
            }
            
            foreach ($logs as $log) {
                $exDate = explode('-', $log);
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
        
        $this->cwsDebug->dump('result', $result, CwsDebug::VERBOSE_REPORT);
        return $result;
    }
    
    /**
     * Download a url's content using CwsCurl wrapper class.
     * This class can be downloaded at https://github.com/crazy-max/CwsCurl
     * @param string $params
     * @return string
     */
    private function getContent($params = '')
    {
        $this->cwsDebug->titleH3('getContent', CwsDebug::VERBOSE_DEBUG);
        
        $url = $this->url . '/' . $params;
        $this->cwsDebug->labelValue('Url', $url, CwsDebug::VERBOSE_DEBUG);
        
        $time = $this->getMicrotime();
        
        $this->cwsCurl->reset();
        $this->cwsCurl->setUrl($url);
        $this->cwsCurl->setAuth($this->nic, $this->password);
        $this->cwsCurl->process();
        
        $this->cwsDebug->labelValue('Size', $this->formatSize(strlen($this->cwsCurl->getContent())), CwsDebug::VERBOSE_DEBUG);
        $this->cwsDebug->labelValue('Time', round($this->getMicrotime() - $time, 3) . ' seconds', CwsDebug::VERBOSE_DEBUG);
        
        if ($this->cwsCurl->getError()) {
            $this->error = $this->cwsCurl->getError();
            $this->cwsDebug->error($this->error);
            return false;
        }
        
        return $this->cwsCurl->getContent();
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
            $size = round($size / 1073741824 * 100) / 100 . 'Go';
        } elseif ($size >= 1048576) {
            $size = round($size / 1048576 * 100) / 100 . 'Mo';
        } else {
            $size = round($size / 1024 * 100) / 100 . 'Ko';
        }
        
        return $size;
    }
    
    private static function getMicrotime()
    {
        list($usec, $sec) = explode(' ', microtime());
        return ((float) $usec + (float) $sec);
    }
    
    /**
     * Set the OVH NIC-handle. (e.g. AB1234-OVH)
     * More infos : http://guides.ovh.com/NicHandle
     * @param string $nic
     */
    public function setNic($nic)
    {
        $this->nic = $nic;
    }

    /**
     * Set the OVH NIC-handle password.
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * Your OVH domain
     * @return the $domain
     */
    public function getDomain()
    {
        return $this->domain;
    }

    /**
     * Set the OVH domain (e.g. crazyws.fr).
     * @param string $domain
     */
    public function setDomain($domain)
    {
        $this->domain = $domain;
    }

    /**
     * The download directory.
     * @return the $dlPath
     */
    public function getDlPath()
    {
        return $this->dlPath;
    }

    /**
     * Set the download directory.
     * @param string $dlPath
     */
    public function setDlPath($dlPath)
    {
        $this->dlPath = $dlPath;
    }

    /**
     * Is downloading enable.
     * @return the $dlEnable
     */
    public function isDlEnable()
    {
        return $this->dlEnable;
    }

    /**
     * Set download activation
     * @param boolean $dlEnable
     */
    public function setDlEnable($dlEnable)
    {
        $this->dlEnable = $dlEnable;
    }

    /**
     * Is overwriting enable.
     * @return the $overwrite
     */
    public function isOverwrite()
    {
        return $this->overwrite;
    }

    /**
     * Set overwrite of existing logs
     * @param boolean $overwrite
     */
    public function setOverwrite($overwrite)
    {
        $this->overwrite = $overwrite;
    }

    /**
     * The last error.
     * @return the $error
     */
    public function getError()
    {
        return $this->error;
    }
}
