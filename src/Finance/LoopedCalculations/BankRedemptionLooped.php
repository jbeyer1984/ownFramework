<?php

namespace MyApp\src\Finance\LoopedCalculations;

use MyApp\src\Finance\ConstData\ConstData;
use MyApp\src\Finance\DataProvider\VariableCostsDataProvider;
use MyApp\src\Finance\FinanceBankTax;
use MyApp\src\Finance\SaveCostsBeginYears;

class BankRedemptionLooped
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
        $toPayOrigin = $this->constData->getPayOrigin() * 0.4; // Anzahl Phase
        $this->constData->setPayOrigin($toPayOrigin);
        $years = $this->constData->getYearsFirstHalf();
        $this->constData->setYearsFirstHalf($years+1); // trick to get 15 years redemption
    }

    public function calculate()
    {
        $inPerYear  = $this->constData->getSaveBauData()->getSchaetzungperjahr();
        $yearsBegin = $this->constData->getYearsFirstHalf();
        $financeBankYears = 0;
        $financeBankYearsOld = 0;
        
        $toPayOrigin = $this->constData->getPayOrigin();
        $oldProvider = new VariableCostsDataProvider($toPayOrigin, $inPerYear);
        $taxArray = [1]; // look in while condition below
        $taxArrayLastItem = array_pop($taxArray);
        $taxArrayOld = $taxArray;
        $taxes = 0;
        $taxesOld = $taxes;
        
        while(0 < $taxArrayLastItem) {
            $provider = new VariableCostsDataProvider($toPayOrigin, $inPerYear);
            $financeBankTax = new FinanceBankTax($yearsBegin, $inPerYear, $this->constData);
            $financeBankTax->calculate();
            $amount = $financeBankTax->getAmountTaxStatus()->getAmount();
            $taxArray = $financeBankTax->getAmountTaxStatus()->getTaxArray();
            
            $taxes = array_sum($financeBankTax->getAmountTaxStatus()->getTaxArray());
            $financeBankYears = $amount - $taxes;
            if (0 < $taxArrayLastItem) {
                $inPerYear           += 5;
                $financeBankYearsOld = $financeBankYears;
                $oldProvider         = $provider;
                $taxArrayOld         = $taxArray;
                $taxArrayLastItem    = array_pop($taxArray);
                $taxesOld            = $taxes;
            }
        }
        $this->variableCostsProvider = $oldProvider;
        $this->calculatedAmount = $taxesOld; // because of mixing bauspar, usually it is $financeBankYearsOld + 2* $taxesOld;
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