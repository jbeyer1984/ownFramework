<?php

namespace MyApp\src\Tasks\ParentChild;



class ParentModel extends Model implements ModelDbInterface
{
    /**
     * @var [ChildModel];
     */
    private $childModelArray;

    /**
     * @var ParentDb
     */
    private $dbData;

    /**
     * @var int
     */
    private $receiptId;

    /**
     * @var string
     */
    private $description;

    /**
     * @var string
     */
    private $stuff;
    
    public function __construct()
    {
        parent::__construct();
    }

    protected function init()
    {
        $this->dbData = new ParentDb();
    }

    public function fetchByData($data)
    {
    }

    /**
     * @return RecordSetIterator
     */
    public function getChildModelArray()
    {
        Relation::getInstance()->getOrmRegistry()
            ->register($this, __METHOD__) // md5(serialize($this))
        ;
        $this->childModelArray = Relation::getInstance()->getOrmRegistry()
            ->oneToMany($this, __METHOD__, ChildModel::class) // md5(serialize($this))
        ;
        
        return $this->childModelArray;
    }

    /**
     * @param [ChildModel] $childModelArray
     * @return ParentModel
     */
    public function setChildModelArray($childModelArray)
    {
//        $this->childModelArray = $childModelArray;

        return $this;
    }
    
    /**
     * @return ParentDb
     */
    public function getDbData()
    {
        return $this->dbData;
    }

    /**
     * @return int
     */
    public function getReceiptId()
    {
        return $this->receiptId;
    }

    /**
     * @param int $receiptId
     * @return ParentModel
     */
    public function setReceiptId($receiptId)
    {
        $this->receiptId = $receiptId;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return ParentModel
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return string
     */
    public function getStuff()
    {
        return $this->stuff;
    }

    /**
     * @param string $stuff
     * @return ParentModel
     */
    public function setStuff($stuff)
    {
        $this->stuff = $stuff;

        return $this;
    }
}
