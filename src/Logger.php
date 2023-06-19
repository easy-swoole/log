<?php


namespace EasySwoole\Log;


class Logger implements LoggerInterface
{
    private $logDir;
    private $prefix = '';

    function __construct(string $logDir = null,?string $prefix = null)
    {
        if(empty($logDir)){
            $logDir = getcwd();
        }
        if(!empty($prefix)){
            $this->prefix = $prefix;
        }
        $this->logDir = $logDir;
    }

    function log(?string $msg,int $logLevel = self::LOG_LEVEL_DEBUG,string $category = 'debug')
    {
        $levelStr = $this->levelMap($logLevel);
        $date = date('Ym');
        if(!empty($this->prefix)){
            $filePath = $this->logDir."/{$this->prefix}.log_{$date}.log";
        }else{
            $filePath = $this->logDir."/log_{$date}.log";
        }
        $time = date('Y-m-d H:i:s');
        $str = "[{$time}][{$category}][{$levelStr}]:[{$msg}]\n";
        file_put_contents($filePath,"{$str}",FILE_APPEND|LOCK_EX);
        return $str;
    }

    function console(?string $msg,int $logLevel = self::LOG_LEVEL_DEBUG,string $category = 'debug')
    {
        $date = date('Y-m-d H:i:s');
        $levelStr = $this->levelMap($logLevel);
        echo "[{$date}][{$category}][{$levelStr}]:[{$msg}]\n";
    }

    private function levelMap(int $level)
    {
        switch ($level)
        {
            case self::LOG_LEVEL_DEBUG:
                return 'debug';
            case self::LOG_LEVEL_INFO:
               return 'info';
            case self::LOG_LEVEL_NOTICE:
                return 'notice';
            case self::LOG_LEVEL_WARNING:
                return 'warning';
            case self::LOG_LEVEL_ERROR:
                return 'error';
            default:
                return 'unknown';
        }
    }
}
