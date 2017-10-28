<?php


namespace MyApp\src\Finance;


use MyApp\src\Finance\DataProvider\VariableCostsDataProvider;

class SaveCostsBeginYears
{
    /**
     * @var AmountTaxStatus
     */
    protected $phase;

    /**
     * @var int
     */
    protected $years;

    /**
     * @var double
     */
    protected $taxRate;

    /**
     * @var VariableCostsDataProvider
     */
    protected $dataProvider;

    public function __construct($years, $taxRate, VariableCostsDataProvider $variableCostsDataProvider)
    {
        $this->dataProvider = $variableCostsDataProvider;
        $this->years        = $years;
        $this->taxRate      = $taxRate;
        $this->init();
    }

    protected function init()
    {
        $this->phase = new AmountTaxStatus();
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
        $amount = 0;
        $years = $this->years;
        $inPerYear = $this->dataProvider->getInPerYear();
        $taxRatePlus = $this->taxRate;
        list($amountArray, $taxRateArray) = FinanceCalcFunctions::taxOfTaxGrow($amount, $years, $inPerYear, $taxRatePlus);
//        $dump = print_r($amountArray, true);
//        error_log(PHP_EOL . '-$- in ' . basename(__FILE__) . ':' . __LINE__ . ' in ' . __METHOD__ . PHP_EOL . '*** $amountArray ***' . PHP_EOL . " = " . $dump . PHP_EOL);
        
        $phase = new AmountTaxStatus();
        $phase->setAmount(array_pop($amountArray));
        $phase->setTaxArray($taxRateArray);
        
        return $phase;
    }

    /**
     * @return AmountTaxStatus
     */
    public function getPhase()
    {
        return $this->phase;
    }
}