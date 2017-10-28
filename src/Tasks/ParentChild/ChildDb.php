<?php

namespace MyApp\src\Tasks\ParentChild;

use MyApp\src\Components\Components;
use MyApp\src\Utility\Db;

class ChildDb implements DbOrmInterface
{
    private $table = 'receiptitems';

    /**
     * @var Db
     */
    protected $db;

    /**
     * @var array
     */
    protected $fetchedData;

    public function __construct()
    {
        $this->init();
    }

    protected function init()
    {
        $this->db = Components::getInstance()->get('db');
    }

    /**
     * @param ChildModel $childModel
     */
    public function fetchById($childModel)
    {
        $sql  = <<<SQL
SELECT  *
FROM    {$this->table}
WHERE   receiptitems_id = :receiptitems_id
SQL;
        $data = [
            'receiptitems_id' => $childModel->getReceiptId()
        ];

        $pdo    = $this->db->execute($sql, $data);
        $result = $pdo->getData();

        if (0 < count($result)) {
            $this->fetchedData = $result[0];

            $this->equip($this->fetchedData, $childModel);
        }
    }

    /**
     * @param $fetchedArray
     * @param ChildModel $childModel
     */
    public function equip($fetchedArray, $childModel)
    {
        $childModel->setReceiptItemsId($fetchedArray['receiptitems_id']);
        $childModel->setReceiptId($fetchedArray['receipt_id']);
        $childModel->setPos($fetchedArray['pos']);
        $childModel->setTurnoverType($fetchedArray['turnover_type']);
        $childModel->setDescription($fetchedArray['description']);
        $childModel->setPrice($fetchedArray['price']);
        $childModel->setTaxes($fetchedArray['taxes']);
    }

    /**
     * @param $data
     * @param ChildModel $childModel
     */
    public function fetchByData($data, $childModel)
    {
        $childModel->setReceiptId($data['receipt_id']);

        $where = '';
        foreach ($data as $key => $value) {
            $where .= $key . ' = ' . $value;
        }
        $sql = <<<SQL
SELECT  *
FROM    receiptitems
WHERE   $where
SQL;

        $pdo    = $this->db->execute($sql, $data);
        $result = $pdo->getData();

        if (0 < count($result)) {
            $this->fetchedData = $result;
        }

    }

    /**
     * @param ChildModel $childModel
     */
    public function save($childModel)
    {
        $this->fetchedData['receiptitems_id'] = $childModel->getReceiptItemsId();
        $this->fetchedData['receipt_id']      = $childModel->getReceiptId();
        $this->fetchedData['pos']             = $childModel->getPos();
        $this->fetchedData['turnover_type']   = $childModel->getTurnoverType();
        $this->fetchedData['description']     = $childModel->getDescription();
        $this->fetchedData['price']           = $childModel->getPrice();
        $this->fetchedData['taxes']           = $childModel->getTaxes();
    }



    /**
     * @return array
     */
    public function getFetchedData()
    {
        return $this->fetchedData;
    }

    /**
     * @param array $fetchedData
     * @return ParentDb
     */
    public function setFetchedData($fetchedData)
    {
        $this->fetchedData = $fetchedData;

        return $this;
    }
    
    /**
     * @return string
     */
    public function getTable()
    {
        return $this->table;
    }
}
