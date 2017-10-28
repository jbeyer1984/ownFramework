<?php


namespace MyApp\src\Finance\ConstData;


use MyApp\src\Finance\ConstData\BankData;
use MyApp\src\Finance\ConstData\HomeData;
use MyApp\src\Finance\ConstData\SaveBauData;

class ConstData
{
    /**
     * @var double
     */
    private $yearsFirstHalf;

    /**
     * @var double
     */
    private $yearsSecondHalf;
    
    /**
     * @var double
     */
    private $payOrigin;

    /**
     * @var HomeData
     */
    private $homeData;

    /**
     * @var SaveBauData
     */
    private $saveBauData;

    /**
     * @var BankData
     */
    private $bankData;

    /**
     * @return float
     */
    public function getYearsFirstHalf()
    {
        return $this->yearsFirstHalf;
    }

    /**
     * @param float $yearsFirstHalf
     * @return ConstData
     */
    public function setYearsFirstHalf($yearsFirstHalf)
    {
        $this->yearsFirstHalf = $yearsFirstHalf;

        return $this;
    }

    /**
     * @return float
     */
    public function getYearsSecondHalf()
    {
        return $this->yearsSecondHalf;
    }

    /**
     * @param float $yearsSecondHalf
     * @return ConstData
     */
    public function setYearsSecondHalf($yearsSecondHalf)
    {
        $this->yearsSecondHalf = $yearsSecondHalf;

        return $this;
    }
    
    /**
     * @return mixed
     */
    public function getPayOrigin()
    {
        return $this->payOrigin;
    }

    /**
     * @param mixed $payOrigin
     * @return ConstData
     */
    public function setPayOrigin($payOrigin)
    {
        $this->payOrigin = $payOrigin;

        return $this;
    }

    /**
     * @return HomeData
     */
    public function getHomeData()
    {
        return $this->homeData;
    }

    /**
     * @param HomeData $homeData
     * @return ConstData
     */
    public function setHomeData($homeData)
    {
        $this->homeData = $homeData;

        return $this;
    }

    /**
     * @return SaveBauData
     */
    public function getSaveBauData()
    {
        return $this->saveBauData;
    }

    /**
     * @param SaveBauData $saveBauData
     * @return ConstData
     */
    public function setSaveBauData($saveBauData)
    {
        $this->saveBauData = $saveBauData;

        return $this;
    }

    /**
     * @return BankData
     */
    public function getBankData()
    {
        return $this->bankData;
    }

    /**
     * @param BankData $bankData
     * @return ConstData
     */
    public function setBankData($bankData)
    {
        $this->bankData = $bankData;

        return $this;
    }
}