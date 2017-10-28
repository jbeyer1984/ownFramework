<?php


namespace MyApp\src\Finance;


class AmountTaxStatus
{
    /**
     * @var double
     */
    private $amount;

    /**
     * @var array
     */
    private $taxArray;

    public function __construct()
    {
        $this->init();
    }

    protected function init()
    {
        $this->amount   = 0;
        $this->taxArray = 0;
    }

    /**
     * @return double
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param double $amount
     * @return AmountTaxStatus
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * @return array
     */
    public function getTaxArray()
    {
        return $this->taxArray;
    }

    /**
     * @param array $taxArray
     * @return AmountTaxStatus
     */
    public function setTaxArray($taxArray)
    {
        $this->taxArray = $taxArray;

        return $this;
    }
}