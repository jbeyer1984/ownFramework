<?php


namespace MyApp\src\Parser\PostgresLog\Collector;


abstract class Collector
{
    /**
     * @var string
     */
    protected $strToFind;

    /**
     * @var string
     */
    protected $grepString; 

    /**
     * Collector constructor.
     * @param $strToFind
     */
    public function __construct($strToFind)
    {
        $this->strToFind = $strToFind;
        $this->init();
    }

    protected function init()
    {
        $this->grepString = '';
    }

    /**
     * @param string $str
     * @return bool
     */
    abstract public function find($str);

    /**
     * @param $str
     */
    abstract protected function determineGrepString($str);

    /**
     * @return string
     */
    public function getGrepString()
    {
        return $this->grepString;
    }
}