<?php


namespace MyApp\src\Parser\PostgresLog;


interface FinderInterface
{
    /**
     * @param string $str
     * @return bool
     */
    public function find($str);
}