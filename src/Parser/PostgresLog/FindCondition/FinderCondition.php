<?php


namespace MyApp\src\Parser\PostgresLog\FindCondition;


use MyApp\src\Parser\PostgresLog\FinderInterface;

class FinderCondition implements FinderInterface
{
    private $strToFind;

    /**
     * FindCondition constructor.
     * @param $strToFind
     */
    public function __construct($strToFind)
    {
        $this->strToFind = $strToFind;
    }

    /**
     * @param string $str
     * @return bool
     */
    public function find($str)
    {
        $found = (false !== stripos($str, $this->strToFind));
        
        return $found;
    }
}