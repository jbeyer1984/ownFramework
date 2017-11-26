<?php


namespace MyApp\src\Parser\PostgresLog\FindCondition;


use MyApp\src\Parser\PostgresLog\FinderInterface;

class FinderConditionPreg implements FinderInterface
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
        $found = (0 < preg_match($str, $this->strToFind));

        return $found;
    }
}
{

}