<?php

namespace MyApp\src\Parser\PostgresLog\Collector\Wrapper;


use MyApp\src\Parser\PostgresLog\Collector\Collector;
use MyApp\src\Parser\PostgresLog\FinderInterface;

class CollectorOrWrapper extends Collector implements FinderInterface
{
    /**
     * @var array[Collector]
     */
    private $collectorArray;

    public function __construct($str)
    {
        parent::__construct($str);
        $this->init();
    }

    protected function init()
    {
        $this->collectorArray = [];
    }

    public function add(Collector $collector)
    {
        $this->collectorArray[] = $collector;
        
        return $this;
    }
    /**
     * @param string $str
     * @return bool
     */
    public function find($str)
    {
        foreach ($this->collectorArray as $collector) {
            /** @var Collector $collector */
            if ($collector->find($str)) {
                $this->grepString = $collector->getGrepString();
                return true;
            }
        }
        
        return false;
    }

    protected function determineGrepString($str)
    {
    }

}