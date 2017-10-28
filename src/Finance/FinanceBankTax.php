<?php


namespace MyApp\src\Finance;


use MyApp\src\Finance\ConstData\ConstData;

class FinanceBankTax
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
     * @var double
     */
    private $taxRate;
    
    /**
     * @var AmountTaxStatus
     */
    private $amountTaxStatus;

    /**
     * @var ConstData
     */
    private $constData;

    /**
     * @var int
     */
    private $yearsToPay;

    /**
     * FinanceBankTax constructor.
     * @param $yearsToPay
     * @param float $inPerYear
     * @param ConstData $constData
     * @internal param float $toPayOrigin
     */
    public function __construct($yearsToPay, $inPerYear, ConstData $constData)
    {
        $this->inPerYear   = $inPerYear;
        $this->constData = $constData;
        $this->init();
        $this->yearsToPay = $yearsToPay;
    }


    protected function init()
    {
//        $this->toPayOrigin = 211000 * 40/100; // 40 percent
//        $this->toPayOrigin = 211000 + 10000;
        $this->toPayOrigin = $this->constData->getPayOrigin();
//        $this->inPerYear = 5626; // too big
//        $this->taxRate = 2.15;
        $this->taxRate         = $this->constData->getBankData()->getZins();
        $this->amountTaxStatus = new PhaseStatus();
    }

    public function calculate()
    {
//        $this->calculateBankTax();
        $this->amountTaxStatus = $this->getCalculatedAmountTaxStatus(); 
    }

    /**
     * @return AmountTaxStatus
     */
    private function getCalculatedAmountTaxStatus()
    {
        $amount = $this->toPayOrigin;
        $inPerYear = $this->inPerYear;
        $taxRatePlus = $this->taxRate;
        $years = $this->constData->getYearsFirstHalf();
        list($amountArray, $taxRateArray) = FinanceCalcFunctions::taxOfTaxRedemption($amount, $years, $inPerYear, $taxRatePlus);

        $phaseStatus = new AmountTaxStatus();
//        $dump = print_r(array_sum($taxRateArray), true);
//        error_log(PHP_EOL . '-$- in ' . basename(__FILE__) . ':' . __LINE__ . ' in ' . __METHOD__ . PHP_EOL . '*** array_sum($taxRateArray) ***' . PHP_EOL . " = " . $dump . PHP_EOL);
//        $dump = print_r($amountArray, true);
//        error_log(PHP_EOL . '-$- in ' . basename(__FILE__) . ':' . __LINE__ . ' in ' . __METHOD__ . PHP_EOL . '*** $amountArray ***' . PHP_EOL . " = " . $dump . PHP_EOL);
//        $dump = print_r($taxRateArray, true);
//        error_log(PHP_EOL . '-$- in ' . basename(__FILE__) . ':' . __LINE__ . ' in ' . __METHOD__ . PHP_EOL . '*** $taxRateArray ***' . PHP_EOL . " = " . $dump . PHP_EOL);
        
        
        $amountWithoutTaxes = array_pop($amountArray);
        $phaseStatus->setAmount($amountWithoutTaxes);
        $this->amountToPayPerYear = ($amountWithoutTaxes + array_sum($taxRateArray))/$years;
        $phaseStatus->setTaxArray($taxRateArray);
        
        return $phaseStatus;
    }

//    private function calculateBankTax()
//    {
//        $toPayOrigin = $this->toPayOrigin;
//        $inPerYear = $this->inPerYear;
//        $taxRate = $this->taxRate;
//        $yearsBegin = 15;
////        $monthPerYear = 12;
//
//        $amountArray = [];
//        $taxArray = [];
//        $dump = print_r($toPayOrigin, true);
//        error_log(PHP_EOL . '-$- in ' . basename(__FILE__) . ':' . __LINE__ . ' in ' . __METHOD__ . PHP_EOL . '*** $toPayOrigin ***' . PHP_EOL . " = " . $dump . PHP_EOL);
//        
//        $currentAmount = $toPayOrigin;
//        $sumAmount = 0;
//        for ($year = 0; $year < $yearsBegin; $year++) {
////            $savePhase = $this->getCalculatedPhaseStatus($toPayOrigin, $inPerYear);
////            $amount = $savePhase->getAmount();
//            $taxRateAmount = $currentAmount * $taxRate/100;
//            $amountArray[] = $currentAmount;
//            $taxArray[] = $taxRateAmount;
//            $currentAmount -= $inPerYear;
//            $sumAmount += $inPerYear;
//        }
//
////        $currentAmount = 0;
////        $sumAmount = 0;
////        for ($year = 0; $year < $yearsBegin; $year++) {
//////            $savePhase = $this->getCalculatedPhaseStatus($toPayOrigin, $inPerYear);
//////            $amount = $savePhase->getAmount();
////            $currentAmount += $inPerYear;
////            $taxRateAmount = $currentAmount * $taxRate/100;
////            $amountArray[] = $currentAmount;
////            $taxArray[] = $taxRateAmount;
////            $sumAmount += $inPerYear;
////        }
//        
//        $dump = print_r($taxArray, true);
//        error_log(PHP_EOL . '-$- in ' . basename(__FILE__) . ':' . __LINE__ . ' in ' . __METHOD__ . PHP_EOL . '*** $taxArray ***' . PHP_EOL . " = " . $dump . PHP_EOL);
//
//        $phaseStatus = new AmountTaxStatus();
//        $phaseStatus
//            ->setAmount($currentAmount)
//            ->setTaxArray($taxArray);
//        ;
//        
//        $this->phaseStatus = $phaseStatus;
//        
////        $this->amountToPayPerYear = ($sumAmount + array_sum($taxArray)) / $yearsBegin;
////        $this->amountToPayPerYear = array_sum($taxArray)/$yearsBegin; // took this only pay taxes????
////        $dump = print_r(array_sum($taxArray), true);
////        error_log(PHP_EOL . '-$- in ' . basename(__FILE__) . ':' . __LINE__ . ' in ' . __METHOD__ . PHP_EOL . '*** array_sum($taxArray) ***' . PHP_EOL . " = " . $dump . PHP_EOL);
//        
//    }

    /**
     * @return AmountTaxStatus
     */
    public function getAmountTaxStatus()
    {
        return $this->amountTaxStatus;
    }

    /**
     * @return float
     */
    public function getAmountToPayPerYear()
    {
        return $this->amountToPayPerYear;
    }
}