<?php

namespace MyApp\src\Tasks\ParentChild;

use MyApp\src\Components\Components;
use MyApp\src\Utility\Db;

class Joiner
{
    private $flow;
    
    private $tempFrom;
    
    private $tempTo;

    /**
     * @var Db
     */
    protected $db;

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
        $this->relationTableOverview = Relation::getInstance()->getRelationTableOverview();
        $this->db                    = Components::getInstance()->get('db');
    }

    public function from(ModelDbInterface $class)
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
//            'identifier' => $relation[$nsClass]['identifier'],
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

    public function joinManyToOne()
    {
        $this->relationDecision = $this->relationTableOverview->manyToOne;

        return $this;
    }

    public function to(ModelDbInterface $class)
    {
        $wholeClassName = get_class($class);

        $namespace = str_replace('\\', '_', substr($wholeClassName, 0, strrpos($wholeClassName, '\\')));
        $className = substr($wholeClassName, strrpos($wholeClassName, '\\')+1);
        $nsClass = $namespace . '__' . $className;

        $relation = $this->relationDecision;
        $dump = print_r($relation, true);
        error_log(PHP_EOL . '-$- in ' . basename(__FILE__) . ':' . __LINE__ . ' in ' . __METHOD__ . PHP_EOL . '*** $relation ***' . PHP_EOL . " = " . $dump . PHP_EOL);
        
        
        $this->tempFrom['identifier'] = $relation[$this->tempFrom['nsClass']]['identifier'];

        $this->tempTo = [
            'class' => $className,
            'namespace' => $namespace,
            'identifier' => $relation[$this->tempFrom['nsClass']][$nsClass]['identifier'],
            'table' => $class->getDbData()->getTable(),
            'model' => $class,
            'data' => []
        ];
        $identifier = $this->tempFrom['class'] . '_' . $this->tempTo['class'];
        $this->flow[$identifier] = [
            'from' => $this->tempFrom,
            'to' => $this->tempTo,
        ];

        return $this;
    }

    public function fetch($where = 'TRUE')
    {
        $identifier = $this->tempFrom['class'] . '_' . $this->tempTo['class'];
        $fromTable = $this->flow[$identifier]['from']['table'];
        $fromIdentifier = $this->flow[$identifier]['from']['identifier'];
        $toTable = $this->flow[$identifier]['to']['table'];
        $toIdentifier = $this->flow[$identifier]['to']['identifier'];
        /** @var ModelDbInterface $fromClass */
        $fromClass = $this->flow[$identifier]['from']['model'];
        $fromClass->getDbData()->save($fromClass);
        $fromClassFetchedData = $fromClass->getDbData()->getFetchedData();
        $idValueWhere = $fromClassFetchedData[$fromIdentifier];
        if (!empty($idValueWhere)) {
            $where = <<<TXT
{$fromTable}.{$fromIdentifier} = {$idValueWhere}
TXT;

        }
        $sql = <<<SQL
SELECT  {$toTable}.*
FROM    {$fromTable}
        JOIN {$toTable} ON {$fromTable}.{$fromIdentifier} = {$toTable}.{$toIdentifier}
WHERE   {$where}
SQL;
        $dump = print_r($sql, true);
        error_log(PHP_EOL . '-$- in ' . basename(__FILE__) . ':' . __LINE__ . ' in ' . __METHOD__ . PHP_EOL . '*** $sql ***' . PHP_EOL . " = " . $dump . PHP_EOL);
        
        $this->db->execute($sql);
        /** @var Model $class */
        
        // determine php doc relations between parent and child

        $model = $this->flow[$identifier]['to']['model'];
        $modelDocAttributes = $this->getParsedOrmDocAttributes($model);
        
        $result = $this->db->getData();
        $recordSetIterator = new RecordSetIterator($result);
        $dataArray = [];
        foreach ($recordSetIterator as $data) {
            $class = clone ($this->tempTo['model']);
            /** @var ModelDbInterface $class */
            $class->getDbData()->setFetchedData($data);
            $class->getDbData()->equip($data, $class);

            foreach ($modelDocAttributes as $relation => $classInfo) {
                if ('manyToOne' == $relation) {
                    foreach ($classInfo as $classToExtend => $info) {
                        if ($classToExtend == $this->flow[$identifier]['from']['nsClass']) {
                            $setMethod = $info['setMethodName'];
                            $class->$setMethod($this->flow[$identifier]['from']['model']);
                        }
                    }
                }
            }
            
            $dataArray[] = $class;    
        }
        
        return $dataArray;
    }

    /**
     * @param $model
     * @return array
     */
    protected function getParsedOrmDocAttributes($model)
    {
        $docOrmParser = new DocOrmParser($model);
        $docOrmParser->parse();
        
        $docOrmMethodAttributes = $docOrmParser->getDocOrmMethodAttributes();
        
        return $docOrmMethodAttributes;
    }

}
