<?php

namespace MyApp\src\Finance;

use MyApp\src\Finance\ConstData\ConstData;

class FinanceOld
{
    /**
     * @var ConstData
     */
    private $constData;

    /**
     * @var array
     */
    private $output;
    
    public function __construct(ConstData $constData)
    {
        $this->constData = $constData;
        $this->init();
    }

    protected function init()
    {
        $this->output = [];
    }

    public function calculation()
    {
        //init
//        $payHome = 224000;
        $payHome = $this->constData->getHomeData()->getWohnung();
//        $others = 2000;
        $others = $this->constData->getHomeData()->getOwnMoney();
        $groundTax = $this->constData->getHomeData()->getGrunderwerbssteuer();
        $notar = $this->constData->getHomeData()->getNotar();
        $makler = $this->constData->getHomeData()->getMakler();
        $costsOfAllInstances = $payHome * (1 + ($groundTax + $notar + $makler)/100) + $others;
//        $ownMoney = 43000;
        $ownMoney = $this->constData->getHomeData()->getOwnMoney();
        $payOrigin = $costsOfAllInstances - $ownMoney;
        $dump = print_r($payOrigin, true);
        error_log(PHP_EOL . '-$- in ' . basename(__FILE__) . ':' . __LINE__ . ' in ' . __METHOD__ . PHP_EOL . '*** $payOrigin ***' . PHP_EOL . " = " . $dump . PHP_EOL);
        
        $this->constData->setPayOrigin($payOrigin);
        $monthPerYear = 12;
        // bauspar
        $financeSaveDivided = new FinanceSaveDivided($this->constData);
        $financeSaveDivided->calculate();
        $financeSaveDividedPerYear = $financeSaveDivided->getAmountToPayPerYear();
        $this->output['save_per_month'] = $financeSaveDividedPerYear/$monthPerYear;
        
        // bank
//        $toPayOrigin = 211.000 + 10000;
        $yearsToPay = $this->constData->getYearsSecondHalf();
        $financeBankTax = new FinanceBankTax($yearsToPay, $financeSaveDividedPerYear, $this->constData);
        $financeBankTax->calculate();
        $financeBankPerYear = $financeBankTax->getAmountToPayPerYear();
        $dump = print_r($financeBankPerYear, true);
        error_log(PHP_EOL . '-$- in ' . basename(__FILE__) . ':' . __LINE__ . ' in ' . __METHOD__ . PHP_EOL . '*** $financeBankPerYear ***' . PHP_EOL . " = " . $dump . PHP_EOL);
        
        
        
        $perMonth = $financeSaveDividedPerYear/$monthPerYear;
        $this->output['bank_per_month'] = $financeBankPerYear/$monthPerYear;
        $dump = print_r($perMonth, true);
        error_log(PHP_EOL . '-$- in ' . basename(__FILE__) . ':' . __LINE__ . ' in ' . __METHOD__ . PHP_EOL . '*** $perMonth ***' . PHP_EOL . " = " . $dump . PHP_EOL);
        
        $perMonthBank = $financeBankPerYear/$monthPerYear;
        $dump = print_r($perMonthBank, true);
        error_log(PHP_EOL . '-$- in ' . basename(__FILE__) . ':' . __LINE__ . ' in ' . __METHOD__ . PHP_EOL . '*** $perMonthBank ***' . PHP_EOL . " = " . $dump . PHP_EOL);
        
        
        
        $perYearMerged = $financeSaveDividedPerYear + $financeBankPerYear;
        $perMonth = $perYearMerged/$monthPerYear;
        $this->output['all_per_month'] = $perYearMerged/$monthPerYear;
        $dump = print_r($perMonth, true);
        error_log(PHP_EOL . '-$- in ' . basename(__FILE__) . ':' . __LINE__ . ' in ' . __METHOD__ . PHP_EOL . '*** $perMonth ***' . PHP_EOL . " = " . $dump . PHP_EOL);
    }

    /**
     * @return array
     */
    public function getOutput()
    {
        return $this->output;
    }
}
