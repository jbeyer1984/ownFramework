<?php

namespace MyApp\src\Tasks\ParentChild;

use MyApp\src\Components\Components;
use MyApp\src\Utility\Db;

class JoinerTemp
{
    private $flow;
    
    private $tempFrom;
    
    private $tempTo;

    /**
     * @var Db
     */
    protected $db;

    /**
     * @var Relation
     */
    private $relation;

    /**
     * @var RelationTableOverview
     */
    private $relationTableOverview;

    /**
     * @var array
     */
    private $relationDecision;
    
    public function __construct()
    {
        $this->init();
    }

    protected function init()
    {
        $this->flow                  = [];
        $this->relation              = Relation::getInstance();
        $this->relationTableOverview = Relation::getInstance()->getRelationTableOverview();
        $this->db                    = Components::getInstance()->get('db');
    }

    public function from(Model $class, $identifier)
    {
        $wholeClassName = get_class($class);
        
        $namespace = str_replace('\\', '_', substr($wholeClassName, 0, strrpos($wholeClassName, '\\')));
        $className = substr($wholeClassName, strrpos($wholeClassName, '\\')+1);
        $nsClass = $namespace . '__' . $className;
        
        $this->tempFrom = $wholeClassName;
        
        $this->tempFrom = [
            'class' => $className,
            'namespace' => $namespace,
            'nsClass' => $namespace . '__' . $className,
            'identifier' => $identifier,
            'table' => $class->getDbData()->getTable(),
            'model' => $class,
            'data' => $class->getDbData()->getFetchedData()
        ];
        
        return $this;
    }

    public function joinOneToMany()
    {
        $this->relationDecision = $this->relationTableOverview->oneToMany;
        
        return $this;
    }

    public function to(Model $class, $identifier)
    {
        $wholeClassName = get_class($class);

        $namespace = str_replace('\\', '_', substr($wholeClassName, 0, strrpos($wholeClassName, '\\')));
        $className = substr($wholeClassName, strrpos($wholeClassName, '\\')+1);
        $nsClass = $namespace . '__' . $className;

        $this->tempTo = [
            'class' => $className,
            'nsClass' => $nsClass,
            'namespace' => $namespace,
            'identifier' => $identifier,
            'table' => $class->getDbData()->getTable(),
            'model' => $class,
            'data' => []
        ];

        $this->equipRelation();
        
        $identifier = $this->tempFrom['class'] . '_' . $this->tempTo['class'];
        $this->flow[$identifier] = [
            'from' => $this->tempFrom,
            'to' => $this->tempTo,
        ];

        return $this;
    }

    public function save()
    {
        $identifier = $this->tempFrom['class'] . '_' . $this->tempTo['class'];
        $fromTable = $this->flow[$identifier]['from']['table'];
        $fromIdentifier = $this->flow[$identifier]['from']['identifier'];
        $toTable = $this->flow[$identifier]['to']['table'];
        $toIdentifier = $this->flow[$identifier]['to']['identifier'];
        $sql = <<<SQL
SELECT  {$toTable}.*
FROM    {$fromTable}
        JOIN {$toTable} ON {$fromTable}.{$fromIdentifier} = {$toTable}.{$toIdentifier}
LIMIT 1
SQL;
        $this->db->execute($sql);
        /** @var Model $class */
        
        $result = $this->db->getData();
        $dataArray = $this->equipData($result);
        
        return $dataArray;
    }

    /**
     * @param $result
     * @return array
     */
    protected function equipData($result)
    {
        if (0 < count($result)) {
            $this->relationTableOverview->add(
                $this->relation->getRelationTemp()->getTemp(),
                'oneToMany'
            );
            $dump                   = print_r($this->relationTableOverview->oneToMany, true);
            error_log(PHP_EOL . '-$- in ' . basename(__FILE__) . ':' . __LINE__ . ' in ' . __METHOD__ . PHP_EOL . '*** $this->relation->oneToMany ***' . PHP_EOL . " = " . $dump . PHP_EOL);
        }

        $dataArray = [];
        foreach ($result as $data) {
            $class = clone ($this->tempTo['model']);
            $class->getDbData()->setFetchedData($data);
            $class->getDbData()->equip($data, $class);
            $dataArray[] = $class;
        }

        return $dataArray;
    }

    protected function equipRelation()
    {
        $from = $this->tempFrom;
        $to   = $this->tempTo;

//        $relationTemplate                                                 = $this->relation->getTemplateRelation();
        $relationTemplate                                                 = [];
        
        $relationTemplate[$from['nsClass']]['identifier']                 = $from['identifier'];
        $relationTemplate[$from['nsClass']][$to['nsClass']]['identifier'] = $to['identifier'];

//        $this->relation->setRelationTemp($relationTemplate);
        $this->relation->getRelationTemp()->setTemp($relationTemplate);
    }

    protected function appendNewToRelation()
    {
        $relationTemplate       = $this->relationTableOverview->getRelationTemp();
        $this->relationDecision = array_merge($this->relationDecision, $relationTemplate);
    }

}
