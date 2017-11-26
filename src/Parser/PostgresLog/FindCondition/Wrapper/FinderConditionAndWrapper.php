<?php


namespace MyApp\src\Parser\PostgresLog\FindCondition\Wrapper;


use MyApp\src\Parser\PostgresLog\FinderInterface;

class FinderConditionAndWrapper implements FinderInterface  
{
    /**
     * @var array[FinderCondition]
     */
    private $findConditionArray;

    public function __construct()
    {
        $this->init();
    }

    protected function init()
    {
        $this->findConditionArray = [];
    }

    public function add(FinderInterface $condition)
    {
        $this->findConditionArray[] = $condition;
    }

    /**
     * @param $str
     * @return bool
     */
    public function find($str)
    {
        $foundChained = [];
        
        foreach ($this->findConditionArray as $condition) {
            /** @var FinderInterface $condition */
            $foundChained[] = $condition->find($str);
        }

        $preConditionHit = $this->foundChained($foundChained);

        return $preConditionHit;
    }

    /**
     * @param array $foundChained
     * @return bool
     */
    private function foundChained($foundChained)
    {
        $found = false;
        $foundChainedTrue = true;
        foreach ($foundChained as $bool) {
            $foundChainedTrue &= $bool;
        }
        if (0 < count($foundChained) && $foundChainedTrue) {
            $found = true;
        }

        return $found;
    }
}