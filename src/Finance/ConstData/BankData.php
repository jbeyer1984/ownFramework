<?php


namespace MyApp\src\Finance\ConstData;


class BankData
{
    /**
     * @var double
     */
    private $zins;

    /**
     * @return float
     */
    public function getZins()
    {
        return $this->zins;
    }

    /**
     * @param float $zins
     * @return BankData
     */
    public function setZins($zins)
    {
        $this->zins = $zins;

        return $this;
    }
}