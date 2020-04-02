<?php


namespace EasySwoole\Log;


class Logger implements LoggerInterface
{
    private $logDir;

    private $colorMap = [
        self::LOG_LEVEL_NOTICE => '[43m',
        self::LOG_LEVEL_WARNING => '[45m',
        self::LOG_LEVEL_ERROR => '[41m',
        self::LOG_LEVEL_INFO => '[42m',
    ];

    public function setColorMap(array $map):LoggerInterface
    {
        $this->colorMap = $map;
        return $this;
    }

    function __construct(string $logDir = null)
    {
        if(empty($logDir)){
            $logDir = getcwd();
        }
        $this->logDir = $logDir;
    }

    function log(?string $msg,int $logLevel = self::LOG_LEVEL_INFO,string $category = 'DEBUG'):string
    {
        $date = date('Y-m-d H:i:s');
        $levelStr = $this->levelMap($logLevel);
        $filePath = $this->logDir."/log_{$category}.log";
        $str = "[{$date}][{$category}][{$levelStr}] : [{$msg}]\n";
        file_put_contents($filePath,"{$str}",FILE_APPEND|LOCK_EX);
        return $str;
    }

    function console(?string $msg,int $logLevel = self::LOG_LEVEL_INFO,string $category = 'DEBUG')
    {
        $date = date('Y-m-d H:i:s');
        $levelStr = $this->levelMap($logLevel);
        $temp =  $this->colorString("[{$date}][{$category}][{$levelStr}] : ",$logLevel)."[{$msg}]\n";
        fwrite(STDOUT,$temp);
    }

    private function colorString(string $str,int $logLevel)
    {
        if(isset($this->colorMap[$logLevel])){
            $out = $this->colorMap[$logLevel];
        }else{
            $out = $this->colorMap[self::LOG_LEVEL_INFO];
        }
        return chr(27) . "$out" . "{$str}" . chr(27) . "[0m";
    }


    private function levelMap(int $level)
    {
        switch ($level)
        {
            case self::LOG_LEVEL_INFO:
               return 'INFO';
            case self::LOG_LEVEL_NOTICE:
                return 'NOTICE';
            case self::LOG_LEVEL_WARNING:
                return 'WARNING';
            case self::LOG_LEVEL_ERROR:
                return 'ERROR';
            default:
                return 'UNKNOWN';
        }
    }
}