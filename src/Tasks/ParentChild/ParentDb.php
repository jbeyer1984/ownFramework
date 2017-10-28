<?php

namespace MyApp\src\Tasks\ParentChild;

use MyApp\src\Components\Components;
use MyApp\src\Utility\Db;

class ParentDb implements DbOrmInterface
{
    private $table = 'receipt';

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
     * @param ParentModel $parentModel
     */
    public function fetchById($parentModel)
    {
        $sql  = <<<SQL
SELECT  *
FROM    {$this->table}
WHERE   receipt_id = :receipt_id
SQL;
        $data = [
            'receipt_id' => $parentModel->getReceiptId()
        ];

        $pdo    = $this->db->execute($sql, $data);
        $result = $pdo->getData();

        if (0 < count($result)) {
            $this->fetchedData = $result[0];

            $this->equip($this->fetchedData, $parentModel);
        }
    }

    /**
     * @param $fetchedArray
     * @param ParentModel $parentModel
     */
    public function equip($fetchedArray, $parentModel)
    {
        $parentModel->setReceiptId($fetchedArray['receipt_id']);
        $parentModel->setDescription($fetchedArray['description']);
        $parentModel->setStuff($fetchedArray['stuff']);
    }

    /**
     * @param $data
     * @param ParentModel $parentModel
     */
    public function fetchByData($data, $parentModel)
    {
        $parentModel->setReceiptId($data['receipt_id']);

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
     * @param ParentModel $parentModel
     */
    public function save($parentModel)
    {
        $this->fetchedData['receipt_id'] = $parentModel->getReceiptId();
        $this->fetchedData['description'] = $parentModel->getDescription();
        $this->fetchedData['stuff'] = $parentModel->getStuff();
        
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
