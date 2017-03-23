<?php

namespace MyApp\src\Tasks\RecursiveTree;

class RecursiveTree
{
    private $logArray;
    
    public function __construct()
    {
    }

    protected function init()
    {
        $this->logArray = [];
    }

    public function execute()
    {
        
    }

    public function getPreparedArrayStructure($array)
    {
        $this->prepareLogArrayStructure($array);
        
        return $this->logArray;
    }

    private function prepareLogArrayStructure($data, &$dataStart = null)
    {
        if (empty($data)) {
            return;
        }
        if (is_null($dataStart)) {
            $dataStart = &$this->logArray;
        }
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                if (!isset($dataStart[$key])) {
                    $dataStart[$key] = [];
                }
                $this->prepareLogArrayStructure($value, $dataStart[$key]);
            } else {
                $dataStart[$key] = $value;
            }
        }
    }
}
