<?php

namespace MyApp\src\Finance;

use MyApp\src\Finance\ConstData\ConstData;

class FinanceSaveDivided
{
    /**
     * @var double
     */
    private $toPayOrigin;

    /**
     * @var double
     */
    private $inPerYear;

    /**
     * @var double
     */
    private $amountToPayPerYear;

    /**
     * @var PhaseStatus
     */
    private $phaseStatus;

    /**
     * @var ConstData
     */
    private $constData;

    public function __construct(ConstData $constData)
    {
        $this->constData = $constData;
        $this->init();
    }

    protected function init()
    {
//        $this->toPayOrigin = 211000 + 10000;
//        $this->toPayOrigin = $this->constData->getPayOrigin();
        $this->inPerYear = 5615;
        $this->phaseStatus = new PhaseStatus();
    }

    public function calculate()
    {
        $this->saveCosts();
    }

    private function saveCosts()
    {
//        $toPayOrigin = 211000 + 10000;
//        $monthPerYear = 12;
        $saveCostsAll = new SaveCostsAll($this->constData);
        $saveCostsAll->calculate();
        $amountToPayPerYear = $saveCostsAll->getAmountToPayPerYear();
        
        $this->amountToPayPerYear = $amountToPayPerYear;
//        $amountToPayPerMonth      = $this->amountToPayPerYear/$monthPerYear;
    }

    /**
     * @return float
     */
    public function getAmountToPayPerYear()
    {
        return $this->amountToPayPerYear;
    }
}
