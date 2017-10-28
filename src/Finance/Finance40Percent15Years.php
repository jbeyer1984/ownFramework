<?php


namespace MyApp\src\Finance;


class Finance40Percent15Years
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
//        $this->toPayOrigin = (211000 + 10000) * 40/100; // 40 percent
        $this->toPayOrigin = ($this->constData->getPayOrigin()) * 40/100; // 40 percent
        $this->inPerYear = 5626; // too big
        $this->phaseStatus = new PhaseStatus();
    }

    public function calculate()
    {
        $this->saveCosts40Percent15Years();
    }

    private function saveCosts40Percent15Years()
    {
        $toPayOrigin = $this->toPayOrigin;
        $inPerYear = $this->inPerYear;
        $monthPerYear = 12;

        $sum = $toPayOrigin + 10;
        while ($sum > $toPayOrigin) {
            $savePhase = $this->getCalculatedPhaseStatus($toPayOrigin, $inPerYear);
            $amount = $savePhase->getAmount();
            $sum = $amount;
            
            if ($sum <= $toPayOrigin) {
                $dump = print_r("found", true);
                error_log(PHP_EOL . '-$- in ' . basename(__FILE__) . ':' . __LINE__ . ' in ' . __METHOD__ . PHP_EOL . '*** "found" ***' . PHP_EOL . " = " . $dump . PHP_EOL);
            } else {
                $inPerYear -= 10;
            }
        }

        $this->amountToPayPerYear = $inPerYear;
        $amountToPayPerMonth      = $this->amountToPayPerYear/$monthPerYear;
        
        $dump = print_r($inPerYear, true);
        error_log(PHP_EOL . '-$- in ' . basename(__FILE__) . ':' . __LINE__ . ' in ' . __METHOD__ . PHP_EOL . '*** $inPerYear ***' . PHP_EOL . " = " . $dump . PHP_EOL);
        
    }

    private function getCalculatedPhaseStatus($toPayOrigin, $inPerYear)
    {
        $provider = new DataProviderSaveCostsBegin($toPayOrigin, $inPerYear);
        $yearsBegin = 15;
        $taxRate = 0.2;
        $saveCostsBeginYears = new SaveCostsBeginYears($yearsBegin, $taxRate, $provider);
        $saveCostsBeginYears->calculate();

        $costPhase = $saveCostsBeginYears->getPhase();
        
        return $costPhase;
    }

    /**
     * @return float
     */
    public function getAmountToPayPerYear()
    {
        return $this->amountToPayPerYear;
    }
}