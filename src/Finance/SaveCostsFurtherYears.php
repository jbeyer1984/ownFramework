<?php


namespace MyApp\src\Finance;


use MyApp\src\Finance\DataProvider\VariableCostsDataProvider;

class SaveCostsFurtherYears extends SaveCostsBeginYears
{
    
    public function __construct($years, $taxRate, VariableCostsDataProvider $variableCostsDataProvider)
    {
        parent::__construct($years, $taxRate, $variableCostsDataProvider);
    }

    public function calculate()
    {
        $this->phase = $this->getCalculatedPhase();
    }

    /**
     * @return AmountTaxStatus
     */
    protected function getCalculatedPhase()
    {
//        $amount = 0;
//        $years = $this->years;
//        $inPerYear = $this->dataProvider->getInPerYear();
//        $taxRatePlus = $this->taxRate;
//        list($amountArray, $taxRateArray) = FinanceCalcFunctions::taxOfTaxGrow($amount, $years, $inPerYear, $taxRatePlus);
//        $phase = new AmountTaxStatus();
////        $amount = array_pop($amountArray);
////        $amount = array_sum($amountArray);
//        $amount = array_pop($amountArray);
//        $amount -= 2*array_sum($taxRateArray);
//        $phase->setAmount($amount);
//        $phase->setTaxArray($taxRateArray);
        $amount = $this->dataProvider->getToPayOrigin();
        $years = $this->years;
        $inPerYear = $this->dataProvider->getInPerYear();
        $taxRatePlus = $this->taxRate;
        list($amountArray, $taxRateArray) = FinanceCalcFunctions::taxOfTaxRedemption($amount, $years, $inPerYear, $taxRatePlus);
        $phase = new AmountTaxStatus();
//        $amount = array_pop($amountArray);
//        $amount = array_sum($amountArray);
        $amount = array_pop($amountArray);
        $amount -= array_sum($taxRateArray);
        $phase->setAmount($amount);
        $phase->setTaxArray($taxRateArray);

        return $phase;
    }
}