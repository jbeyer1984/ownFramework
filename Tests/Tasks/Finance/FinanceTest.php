<?php

define('VAR_WWW', '/var/www/ownFramework');
//define( 'VAR_WWW', 'D:\\Programme\\xampp\\htdocs\\ownFramework\\' );
require_once(VAR_WWW . '/src/bootstrap.php');
require_once(ROOT_PATH . '/vendor/autoload.php');

use MyApp\src\Finance\ConstData\BankData;
use MyApp\src\Finance\ConstData\ConstData;
use MyApp\src\Finance\ConstData\HomeData;
use MyApp\src\Finance\ConstData\SaveBauData;
use MyApp\src\Finance\DataProvider\VariableCostsDataProvider;
use \MyApp\src\Finance\Finance;
use MyApp\src\Finance\FinanceCalcFunctions;
use MyApp\src\Finance\SaveCostsBeginYears;
use MyApp\src\Finance\SaveCostsFurtherYears;

class FinanceTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
    }

    public function tearDown()
    {
    }

    public function testBank15Years_Finance_calculation()
    {
        $homeData = new HomeData();
        $homeData
            ->setWohnung(224000)
            ->setGrunderwerbssteuer(4.5)
            ->setNotar(2.0)
            ->setMakler(6)
            ->setOwnMoney(43000)
            ->setSonstiges(2000)
        ;
        $saveBauData = new SaveBauData();
        $saveBauData
            ->setAnsparphase(0.2)
            ->setZahlphase(1.5)
        ;
        $bankData = new BankData();
        $bankData
            ->setZins(2.5)
        ;
        $constData = new ConstData();
        $constData
            ->setHomeData($homeData)
            ->setSaveBauData($saveBauData)
            ->setBankData($bankData)
        ;
        
        $finance = new Finance($constData);
        $finance->calculation();
    }

    public function testSavePhase15years_SaveCostsBeginYears_success()
    {
        $constData = new ConstData();
        $saveBauData = new SaveBauData();
        $saveBauData
            ->setAnsparphase(0.2)
            ->setZahlphase(1.5)
        ;
        $constData
            ->setSaveBauData($saveBauData)
        ;
        $montPerYear = 12;
        $toPayOrigin = 89600;
        $inPerYear = 5200;
//        $inPerYear = 5615;
//        $inPerYear = 9000; // per month ca. 750
        $yearsBegin = 15;
        $taxRate = $constData->getSaveBauData()->getAnsparphase();
        $savePhase15Years = 0;
        while($toPayOrigin > $savePhase15Years) {
            $provider = new VariableCostsDataProvider($toPayOrigin, $inPerYear);
            $saveCostsBeginYears = new SaveCostsBeginYears($yearsBegin, $taxRate, $provider);
            $saveCostsBeginYears->calculate();
            $savePhase15Years = $saveCostsBeginYears->getPhase()->getAmount();
            if ($toPayOrigin > $savePhase15Years) {
                $inPerYear += 5;
            }
        }
        $dump = print_r($savePhase15Years, true);
        error_log(PHP_EOL . '-$- in ' . basename(__FILE__) . ':' . __LINE__ . ' in ' . __METHOD__ . PHP_EOL . '*** $savePhase15Years ***' . PHP_EOL . " = " . $dump . PHP_EOL);
        $dump = print_r($inPerYear, true);
        error_log(PHP_EOL . '-$- in ' . basename(__FILE__) . ':' . __LINE__ . ' in ' . __METHOD__ . PHP_EOL . '*** $inPerYear ***' . PHP_EOL . " = " . $dump . PHP_EOL);
        
    }

    public function testSavePhase15years_SaveCostsFurtherYears_success()
    {
        $constData = new ConstData();
        $saveBauData = new SaveBauData();
        $saveBauData
            ->setAnsparphase(0.2)
            ->setZahlphase(1.5)
        ;
        $constData
            ->setSaveBauData($saveBauData)
        ;
        $toPayOrigin = 134400;
//        $inPerYear = 5615;
        $inPerYear = 9000; // per month ca. 750
        $yearsBegin = 15;
        $taxRate = $constData->getSaveBauData()->getZahlphase();
        $savePhase15Years = 0;
        while($toPayOrigin > $savePhase15Years) {
            $provider = new DataProviderSaveCostsBegin($toPayOrigin, $inPerYear);
            $saveCostsBeginYears = new SaveCostsFurtherYears($yearsBegin, $taxRate, $provider);
            $saveCostsBeginYears->calculate();
            $savePhase15Years = $saveCostsBeginYears->getPhase()->getAmount();
            if ($toPayOrigin > $savePhase15Years) {
                $inPerYear += 5;
            }
        }
        
        $dump = print_r($savePhase15Years, true);
        error_log(PHP_EOL . '-$- in ' . basename(__FILE__) . ':' . __LINE__ . ' in ' . __METHOD__ . PHP_EOL . '*** $savePhase15Years ***' . PHP_EOL . " = " . $dump . PHP_EOL);
        $dump = print_r($inPerYear, true);
        error_log(PHP_EOL . '-$- in ' . basename(__FILE__) . ':' . __LINE__ . ' in ' . __METHOD__ . PHP_EOL . '*** $inPerYear ***' . PHP_EOL . " = " . $dump . PHP_EOL);
        
    }

    public function testRedemption_FinanceCalcFunctions_looped()
    {
        // my calculation
//        $amount = 189000;
        $amount = 1000;
//        $amount = 131549.97;
//        $payPerMonth = 623.33;
        $years = 5;
        $payPerMonth = 1000/$years/12;
        $taxes = 2;
        list($amountArray, $taxesArray) = FinanceCalcFunctions::taxOfTaxRedemption($amount, $years, $payPerMonth * 12, $taxes);
        $dump = print_r($amountArray, true);
        error_log(PHP_EOL . '-$- in ' . basename(__FILE__) . ':' . __LINE__ . ' in ' . __METHOD__ . PHP_EOL . '*** $amountArray ***' . PHP_EOL . " = " . $dump . PHP_EOL);
        $dump = print_r($taxesArray, true);
        error_log(PHP_EOL . '-$- in ' . basename(__FILE__) . ':' . __LINE__ . ' in ' . __METHOD__ . PHP_EOL . '*** $taxesArray ***' . PHP_EOL . " = " . $dump . PHP_EOL);
        $result = array_pop($amountArray) + array_sum($taxesArray);
        $dump = print_r($result, true);
        error_log(PHP_EOL . '-$- in ' . basename(__FILE__) . ':' . __LINE__ . ' in ' . __METHOD__ . PHP_EOL . '*** $result ***' . PHP_EOL . " = " . $dump . PHP_EOL);
        
     
        //  q = 1 + p / 100 = 1 + i
        $tax = 2;
        $q = 1 + $tax / 100;
        $years = 5;
        $amount = 1000;
        $payPerYear = $amount/$years;
        
        // math calculation
        $firstTerm = (pow($q, $years) -1)/($q-1);
        $secondTerm = 1/(pow($q, $years));
        $amountPlusTax = $payPerMonth *  $firstTerm * $secondTerm;
//        $amountPlusTax = $payPerYear *  $firstTerm;
        $result = $amountPlusTax;
        $result += $result * $tax/100;
        ;
        $dump = print_r($amountPlusTax, true);
        error_log(PHP_EOL . '-$- in ' . basename(__FILE__) . ':' . __LINE__ . ' in ' . __METHOD__ . PHP_EOL . '*** $amountPlusTax ***' . PHP_EOL . " = " . $dump . PHP_EOL);
        $dump = print_r($result, true);
        error_log(PHP_EOL . '-$- in ' . basename(__FILE__) . ':' . __LINE__ . ' in ' . __METHOD__ . PHP_EOL . '*** $result ***' . PHP_EOL . " = " . $dump . PHP_EOL);
        
    }
}
    