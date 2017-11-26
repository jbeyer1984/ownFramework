<?php


namespace MyApp\src\Parser\PostgresLog\Collector;


use MyApp\src\Parser\PostgresLog\FinderInterface;

class CollectorWholeLine extends Collector implements FinderInterface
{
    /**
     * @param string $str
     * @return bool
     */
    public function find($str)
    {
        $this->determineGrepString($str);

        return true;
    }

    /**
     * @param string $str
     */
    protected function determineGrepString($str)
    {
        $this->grepString = $str;
    }


    /**
     * @return string
     */
    public function getGrepString()
    {
        return $this->grepString;
    }
}