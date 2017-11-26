<?php

namespace MyApp\src\Parser\PostgresLog\FinderCollector;

use MyApp\src\Parser\PostgresLog\Collector\Collector;
use MyApp\src\Parser\PostgresLog\FindCondition\Wrapper\FinderConditionAndWrapper;

class FinderCollector
{
    /**
     * @var FinderConditionAndWrapper
     */
    private $finderConditionRow;

    /**
     * @var Collector
     */
    private $grabCollector;

    /**
     * FinderCollector constructor.
     * @param FinderConditionAndWrapper $finderConditionRow
     * @param Collector $grabCollector
     */
    public function __construct(FinderConditionAndWrapper $finderConditionRow, Collector $grabCollector)
    {
        $this->finderConditionRow = $finderConditionRow;
        $this->grabCollector      = $grabCollector;
    }

    /**
     * @param $str
     * @return string
     */
    public function getGrepString($str)
    {
        $finderFound = $this->finderConditionRow->find($str);
        $grepFound = $this->grabCollector->find($str);
        $grepString = '';
        if ($finderFound && $grepFound) {
            $grepString = $this->grabCollector->getGrepString();
        }
        
        return $grepString;
    }
}
