<?php

namespace MyApp\src\Finance\LoopedCalculations;

use MyApp\src\Finance\ConstData\ConstData;
use MyApp\src\Finance\DataProvider\VariableCostsDataProvider;
use MyApp\src\Finance\SaveCostsBeginYears;

class SaveCostsBeginYearsLooped
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
    }

    public function calculate()
    {
        $inPerYear        = $this->constData->getSaveBauData()->getSchaetzungperjahr();
        $yearsBegin       = $this->constData->getYearsFirstHalf();
        $taxRate          = $this->constData->getSaveBauData()->getAnsparphase();
        $savePhaseFirstYears = 0;
        $savePhaseFirstYearsOld = 0;
        $toPayOrigin      = $this->constData->getPayOrigin();
        $oldProvider      = new VariableCostsDataProvider($toPayOrigin, $inPerYear);
        $taxes  = 0;
        $taxesOld = $taxes;
        while($toPayOrigin > $savePhaseFirstYears) {
            $provider = new VariableCostsDataProvider($toPayOrigin, $inPerYear);
            $saveCostsBeginYears = new SaveCostsBeginYears($yearsBegin, $taxRate, $provider);
            $saveCostsBeginYears->calculate();
            $amount = $saveCostsBeginYears->getPhase()->getAmount();
            $taxes = array_sum($saveCostsBeginYears->getPhase()->getTaxArray());
            $savePhaseFirstYears = $amount + $taxes; // $taxes bekomme ich ja
            if ($toPayOrigin > $savePhaseFirstYears) {
                $inPerYear += 5;
                $oldProvider = $provider;
                $savePhaseFirstYearsOld = $savePhaseFirstYears;
                $taxesOld = $taxes;
            } else {
                $this->variableCostsProvider = $oldProvider;
                $this->calculatedAmount = $savePhaseFirstYearsOld - 2 * $taxesOld; // because ansparphase, muss weniger bezahlen
            }
        }
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