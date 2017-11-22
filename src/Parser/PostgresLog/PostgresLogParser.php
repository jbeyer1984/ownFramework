<?php

namespace MyApp\src\Parser\PostgresLog;

class PostgresLogParser
{
    private $txt;

    public function getParsedSelect($txt)
    {
        $lines = explode(PHP_EOL, $txt);
        $begin = false;
        $end = false;
        
        $selectText = '';
        foreach ($lines as $line) {
            if (false !== stripos($line, 'begin')) {
                $begin = true;
            } elseif (false !== stripos($line, 'end')) {
                $end = true;
            }
            
            if ($begin && $end) {
                break;
            } elseif($begin && !$end) {
                $selectText .= $line . PHP_EOL;
            }
        }
        
        return $selectText;
    }
}