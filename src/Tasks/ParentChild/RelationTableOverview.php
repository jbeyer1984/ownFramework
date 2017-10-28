<?php

namespace MyApp\src\Tasks\ParentChild;

class RelationTableOverview
{
    private $fileName;
    
    public $oneToMany;
    
    public $manyToOne;

    public function __construct()
    {
        $this->init(); 
    }

    protected function init()
    {
        $this->fileName  = 'tableOverview.json';
        $this->oneToMany = [
            'MyApp_src_Tasks_ParentChild__ParentModel' => [
                'identifier' => 'receipt_id',
                'MyApp_src_Tasks_ParentChild__ChildModel' => [
                    'identifier' => 'receipt_id',
                ],
            ],
//            'MyApp_src_Tasks_ParentChild__ChildModel' => [
//                'identifier' => 'receiptitems_id',
//            ]
        ];
        
//        $this->write();
        $this->read();
        
        $this->manyToOne($this->oneToMany);
    }
    
    protected function read()
    {
        $filePath = __DIR__ . PATH_SEPARATOR . $this->fileName . 'json';
        $jsonString = file_get_contents($filePath, true);
        $jsonArray = json_decode($jsonString, true);
//        $dump = print_r($jsonArray, true);
//        error_log(PHP_EOL . '-$- in ' . basename(__FILE__) . ':' . __LINE__ . ' in ' . __METHOD__ . PHP_EOL . '*** $jsonArray ***' . PHP_EOL . " = " . $dump . PHP_EOL);
        
    }
    
    protected function write()
    {
        $filePath = __DIR__ . PATH_SEPARATOR . $this->fileName . 'json';
        $jsonString = json_encode($this->oneToMany);
        file_put_contents($filePath, $jsonString);
    }

    protected function manyToOne($oneToMany)
    {
        $manyToOne = [];
        foreach ($oneToMany as $nsClassFrom => $arrFrom) {
            $identifierFrom = $arrFrom['identifier'];
            unset($arrFrom['identifier']);
            foreach ($arrFrom as $nsClassTo => $arrTo) {
                $identifierTo = $arrTo['identifier'];
                $manyToOne[$nsClassTo]['identifier'] = $identifierTo;
                $manyToOne[$nsClassTo][$nsClassFrom]['identifier'] = $identifierFrom;
            }   
        }
        
        $this->manyToOne = $manyToOne;
    }

    public function add($entry, $direction)
    {
        $countBefore = count($this->oneToMany);
        switch ($direction) {
            case 'oneToMany':
                $this->oneToMany = array_merge(
                    $this->oneToMany,
                    $entry
                );
                break;
            case 'manyToOne':
                $dump = print_r("@todo twist direction", true);
                error_log(PHP_EOL . '-$- in ' . basename(__FILE__) . ':' . __LINE__ . ' in ' . __METHOD__ . PHP_EOL . '*** "@todo twist direction" ***' . PHP_EOL . " = " . $dump . PHP_EOL);
                break;
            default:
                // do nothing
        }
        
        $countAfter = count($this->oneToMany);
        
        if ($countBefore != $countAfter) {
            $this->write();    
        }
    }
    
}
