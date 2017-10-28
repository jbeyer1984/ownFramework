<?php

namespace MyApp\src\Tasks\ParentChild;

use MyApp\src\Components\Components;
use MyApp\src\Utility\Db;

class Model
{
    /**
     * @var int
     */
    protected $id;

//    /**
//     * @var array
//     */
//    protected $fetchedData;

//    /**
//     * @var bool
//     */
//    protected $changed;

//    /**
//     * @var Db
//     */
//    protected $db;

    public function __construct()
    {
        $this->init();
    }

    protected function init()
    {
//        $this->changed = false;
//        $this->fetchedData = null;
//        $this->db = Components::getInstance()->get('db');
    }

//    public function fetchById($id)
//    {
//        $this->id = $id;
//        
//        $sql = <<<SQL
//SELECT  *
//FROM    receipt
//WHERE   receipt_id = :receipt_id
//SQL;
//        $data = [
//            'receipt_id' => $this->id
//        ];
//
//        $pdo = $this->db->execute($sql, $data);
//        $result = $pdo->getData();
//        
//        if (0 < count($result)) {
//            $this->fetchedData = $result[0];
//        }
//        
////        $dump = print_r($result, true);
////        error_log(PHP_EOL . '-$- in ' . basename(__FILE__) . ':' . __LINE__ . ' in ' . __METHOD__ . PHP_EOL . '*** $result ***' . PHP_EOL . " = " . $dump . PHP_EOL);
//        
//    }

//    public function fetchByData($data)
//    {
//        
//    }
//
//    public function equip($fetchedArray)
//    {
//        
//    }

//    /**
//     * @return string
//     */
//    public function getTable()
//    {
//        return $this->table;    
//    }
//
//    /**
//     * @return int
//     */
//    public function getId()
//    {
//        return $this->id;
//    }
//
//    /**
//     * @param int $id
//     * @return Model
//     */
//    public function setId($id)
//    {
//        $this->id = $id;
//
//        return $this;
//    }
}
