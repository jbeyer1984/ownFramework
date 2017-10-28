<?php


namespace MyApp\src\Finance;


use MyApp\src\Finance\ConstData\ConstData;
use MyApp\src\Tasks\ParentChild\ParentChild;

class SaveCostsAll
{
    /**
     * @var PhaseStatus
     */
    private $phaseStatus;

    /**
     * @var double
     */
    private $toPayOrigin;
    
    private $amountToPayPerYear;

    private $saveCostsBeginYears;
    
    private $saveCostsToEndYears;

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
        $this->toPayOrigin = $this->constData->getPayOrigin();
        $this->phaseStatus = new PhaseStatus(); // not used yet
    }

    public function calculate()
    {
        $toPayOrigin = $this->toPayOrigin;
        $inPerYear = 5615;

        $sum = 0;
        while ($toPayOrigin > $sum) {
            $this->phaseStatus = $this->getCalculatedPhaseStatus($toPayOrigin, $inPerYear);
            
            $savePhaseTemp = $this->phaseStatus->getSavePhase();
            $savePhaseAmountTemp = $savePhaseTemp->getAmount();
            
            $costPhaseTemp = $this->phaseStatus->getCostPhase();
            $costPhaseAmountTemp = $costPhaseTemp->getAmount();
            
            $sum = $savePhaseAmountTemp + $costPhaseAmountTemp;
            if ($toPayOrigin <= $sum) {
            } else {
                $inPerYear += 10;
            }
        }
        
        $this->amountToPayPerYear = $inPerYear;
    }

    protected function getCalculatedPhaseStatus($toPayOrigin, $inPerYear)
    {
        $provider = new DataProviderSaveCostsBegin($toPayOrigin, $inPerYear);
        $yearsBegin = $this->constData->getYearsFirstHalf();
//        $taxRate = 0.2;
        $taxRate = $this->constData->getSaveBauData()->getAnsparphase();
        $saveCostsBeginYears = new SaveCostsBeginYears($yearsBegin, $taxRate, $provider);
        $saveCostsBeginYears->calculate();

        $phaseStatus = new PhaseStatus();
        
        $savePhase = $saveCostsBeginYears->getPhase();
        $phaseStatus->setSavePhase($savePhase);

        $taxRate = $this->constData->getSaveBauData()->getZahlphase();
        $furtherYears = $this->constData->getYearsSecondHalf();
        $saveCostsFurtherYears = new SaveCostsFurtherYears($furtherYears, $taxRate, $provider);
        $saveCostsFurtherYears->calculate();
        $costPhase = $saveCostsFurtherYears->getPhase();
        $phaseStatus->setCostPhase($costPhase);
        
        return $phaseStatus;
    }

    /**
     * @return mixed
     */
    public function getAmountToPayPerYear()
    {
        return $this->amountToPayPerYear;
    }
}