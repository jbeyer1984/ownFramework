<?php

namespace MyApp\src\Finance\LoopedCalculations;

use MyApp\src\Finance\ConstData\ConstData;
use MyApp\src\Finance\DataProvider\VariableCostsDataProvider;
use MyApp\src\Finance\SaveCostsFurtherYears;

class SaveCostsFurtherYearsLooped
{
    /**
     * @var ConstData
     */
    private $constData;

    /**
     * @var VariableCostsDataProvider
     */
    private $variableCostsProvider;

    /**
     * @var double
     */
    private $calculatedAmount;
    
    public function __construct(ConstData $constData)
    {
        $this->constData = clone $constData;
        $this->init();
    }

    protected function init()
    {
        $toPayOrigin = $this->constData->getPayOrigin() * 0.6; // Zahl Phase
        $this->constData->setPayOrigin($toPayOrigin);
        $years = $this->constData->getYearsSecondHalf();
        $this->constData->setYearsSecondHalf($years+1); // trick to get 15 years redemption
    }

    public function calculate()
    {
        $inPerYear        = $this->constData->getSaveBauData()->getSchaetzungperjahr();
        $yearsBegin       = $this->constData->getYearsSecondHalf();
        $taxRate          = $this->constData->getSaveBauData()->getZahlphase();
        $dump = print_r("here", true);
        error_log(PHP_EOL . '-$- in ' . basename(__FILE__) . ':' . __LINE__ . ' in ' . __METHOD__ . PHP_EOL . '*** "here" ***' . PHP_EOL . " = " . $dump . PHP_EOL);
        
        $dump = print_r($taxRate, true);
        error_log(PHP_EOL . '-$- in ' . basename(__FILE__) . ':' . __LINE__ . ' in ' . __METHOD__ . PHP_EOL . '*** $taxRate ***' . PHP_EOL . " = " . $dump . PHP_EOL);
        
        $savePhaseFirstYears = 0;
        $savePhaseFirstYearsOld = 0;
        $toPayOrigin      = $this->constData->getPayOrigin();
        $oldProvider      = new VariableCostsDataProvider($toPayOrigin, $inPerYear);
        $taxesArray = [1]; // look in while condition below
        $taxesArrayLastItem = array_pop($taxesArray);
        $taxesArrayOld = $taxesArray;
        while(0 < $taxesArrayLastItem) { // related to ->init method, see trick
            $provider = new VariableCostsDataProvider($toPayOrigin, $inPerYear);
            $saveCostsFurtherYears = new SaveCostsFurtherYears($yearsBegin, $taxRate, $provider);
            $saveCostsFurtherYears->calculate();
            $savePhaseFirstYears = $saveCostsFurtherYears->getPhase()->getAmount();
            $taxesArray = $saveCostsFurtherYears->getPhase()->getTaxArray();
            if (0 < $taxesArrayLastItem) {
                $inPerYear += 5;
                $oldProvider = $provider;
                $savePhaseFirstYearsOld = $savePhaseFirstYears;
                $taxesArrayOld = $taxesArray;
                $taxesArrayLastItem = array_pop($taxesArray);
            }
        }
        $dump = print_r($taxesArrayOld, true);
        error_log(PHP_EOL . '-$- in ' . basename(__FILE__) . ':' . __LINE__ . ' in ' . __METHOD__ . PHP_EOL . '*** $taxesArrayOld ***' . PHP_EOL . " = " . $dump . PHP_EOL);
        
        $this->variableCostsProvider = $oldProvider;
        $this->calculatedAmount = $savePhaseFirstYearsOld + 2*array_sum($taxesArrayOld);
    }

    /**
     * @return VariableCostsDataProvider
     */
    public function getVariableCostsProvider()
    {
        return $this->variableCostsProvider;
    }

    /**
     * @return float
     */
    public function getCalculatedAmount()
    {
        return $this->calculatedAmount;
    }
}