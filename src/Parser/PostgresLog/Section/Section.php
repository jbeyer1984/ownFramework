<?php


namespace MyApp\src\Parser\PostgresLog\Section;


use MyApp\src\Parser\PostgresLog\FinderCollector\FinderCollector;
use MyApp\src\Parser\PostgresLog\FinderInterface;

class Section
{
    /**
     * @var FinderInterface
     */
    private $preCondition;
    
    /**
     * @var FinderCollector
     */
    private $collectorBegin;

    /**
     * @var FinderCollector
     */
    private $collectorEnd;

    /**
     * @var FinderInterface
     */
    private $postCondition;

    /**
     * Section constructor.
     */
    public function __construct()
    {
        $this->init();
    }

    protected function init()
    {
        $this->preCondition   = null;
        $this->collectorBegin = [];
        $this->postCondition  = [];
    }

    /**
     * @param FinderInterface $condition
     * @return $this
     */
    public function applyPreCondition(FinderInterface $condition)
    {
        $this->preCondition = $condition;
        
        return $this;
    }

    /**
     * @param FinderCollector $collector
     * @return $this
     */
    public function applyCollectorBegin(FinderCollector $collector)
    {
        $this->collectorBegin = $collector;

        return $this;
    }

    /**
     * @param FinderCollector $collector
     * @return $this
     */
    public function applyCollectorEnd(FinderCollector $collector)
    {
        $this->collectorEnd = $collector;

        return $this;
    }

    /**
     * @param FinderInterface $condition
     * @return $this
     */
    public function applyPostCondition(FinderInterface $condition)
    {
        $this->postCondition = $condition;

        return $this;
    }

    /**
     * @return FinderInterface
     */
    public function getPreCondition()
    {
        return $this->preCondition;
    }

    /**
     * @return FinderCollector
     */
    public function getCollectorBegin()
    {
        return $this->collectorBegin;
    }

    /**
     * @return FinderCollector
     */
    public function getCollectorEnd()
    {
        return $this->collectorEnd;
    }

    /**
     * @return FinderInterface
     */
    public function getPostCondition()
    {
        return $this->postCondition;
    }
}