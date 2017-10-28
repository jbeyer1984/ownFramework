<?php

namespace MyApp\src\Tasks\ParentChild;

use MyApp\src\Components\Components;
use MyApp\src\Utility\Db;

class ChildModel extends Model implements ModelDbInterface
{
//    /**
//     * @var ParentChild;
//     */
//    private $parentChild;

//    /**
//     * @var SpecificChildIterator
//     */
//    private $specificChildIterator;

    /**
     * @var ParentDb
     */
    private $dbData;

    /**
     * @var ParentModel
     */
    private $parentModel;

    /**
     * @var int
     */
    private $receiptId;

    /**
     * @var int
     */
    private $receiptItemsId;

    /**
     * @var int
     */
    private $pos;

    /**
     * @var string
     */
    private $turnoverType;

    /**
     * @var string
     */
    private $description;

    /**
     * @var double
     */
    private $price;

    /**
     * @var double
     */
    private $taxes;
    
    public function __construct()
    {
        parent::__construct();
    }

    protected function init()
    {
        $this->dbData = new ChildDb();
    }

//    /**
//     * @return ParentChild
//     */
//    public function getParentChild()
//    {
//        return $this->parentChild;
//    }
//
//    /**
//     * @param ParentChild $parentChild
//     * @return ChildModel
//     */
//    public function setParentChild($parentChild)
//    {
//        $this->parentChild = $parentChild;
//
//        return $this;
//    }

//    /**
//     * @return SpecificChildIterator
//     */
//    public function getSpecificChildIterator()
//    {
//        return $this->specificChildIterator;
//    }
//
//    /**
//     * @param SpecificChildIterator $specificChildIterator
//     * @return ChildModel
//     */
//    public function setSpecificChildIterator($specificChildIterator)
//    {
//        $this->specificChildIterator = $specificChildIterator;
//
//        return $this;
//    }

    /**
     * @return ParentDb
     */
    public function getDbData()
    {
        return $this->dbData;
    }

    /**
     * @param ParentDb $dbData
     * @return ChildModel
     */
    public function setDbData($dbData)
    {
        $this->dbData = $dbData;

        return $this;
    }

    /**
     * @myOrm|manyToOne(targetEntity="ParentModel")
     * @return ParentModel
     */
    public function getParentModel()
    {
        Relation::getInstance()->getOrmRegistry()
            ->register($this, __METHOD__) // md5(serialize($this))
        ;
        $parentModelArray = Relation::getInstance()->getOrmRegistry()
            ->manyToOne($this, __METHOD__, ParentModel::class) // md5(serialize($this))
        ;
        
        $this->parentModel = $parentModelArray->current();
        
        return $this->parentModel;
    }

    /**
     * @param ParentModel $parentModel
     * @return ChildModel
     */
    public function setParentModel($parentModel)
    {
        $this->parentModel = $parentModel;

        return $this;
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
     * @return ChildModel
     */
    public function setReceiptId($receiptId)
    {
        $this->receiptId = $receiptId;

        return $this;
    }

    /**
     * @return int
     */
    public function getReceiptItemsId()
    {
        return $this->receiptItemsId;
    }

    /**
     * @param int $receiptItemsId
     * @return ChildModel
     */
    public function setReceiptItemsId($receiptItemsId)
    {
        $this->receiptItemsId = $receiptItemsId;

        return $this;
    }
    
    /**
     * @return int
     */
    public function getPos()
    {
        return $this->pos;
    }

    /**
     * @param int $pos
     * @return ChildModel
     */
    public function setPos($pos)
    {
        $this->pos = $pos;

        return $this;
    }

    /**
     * @return string
     */
    public function getTurnoverType()
    {
        return $this->turnoverType;
    }

    /**
     * @param string $turnoverType
     * @return ChildModel
     */
    public function setTurnoverType($turnoverType)
    {
        $this->turnoverType = $turnoverType;

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
     * @return ChildModel
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param float $price
     * @return ChildModel
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @return float
     */
    public function getTaxes()
    {
        return $this->taxes;
    }

    /**
     * @param float $taxes
     * @return ChildModel
     */
    public function setTaxes($taxes)
    {
        $this->taxes = $taxes;

        return $this;
    }
}
