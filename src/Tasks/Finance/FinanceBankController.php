<?php


namespace MyApp\src\Tasks\Finance;


use MyApp\src\Finance\ConstData\BankData;
use MyApp\src\Finance\ConstData\ConstData;
use MyApp\src\Finance\ConstData\SaveBauData;
use MyApp\src\Finance\DataProviderSaveCostsBegin;
use MyApp\src\Finance\FinanceBankTax;
use MyApp\src\Finance\SaveCostsFurtherYears;
use MyApp\src\Tasks\Tasks;

class FinanceBankController extends Tasks
{
    public function __construct()
    {
        parent::__construct();
    }

    public function bank(
        $input = [], $bank = []
    )
    {
        $template = 'Finance/' . strtolower(__FUNCTION__) . '/' . strtolower(__FUNCTION__);
        if ('post' == strtolower($_SERVER['REQUEST_METHOD'])) {
//            $template .= '_output_rendered.twig';
            $template .= '.twig';
        } else {
            $template .= '.twig';
        }

        if (empty($input) && empty($bank)) {
            echo $this->components->get('view')->render($template, [
                    'input' => [
                        'einzahlung_pro_jahr' => '6600',
                        'zu_erreichen' => '89600',
                    ],
                    'bank' => [
//                        'ansparphase' => '0.2',
                        'zins' => '2.17'
                    ],
                    'templateContext' => 'bank',
                ]
            );
            return;
        }
        
        $financeOutput = $this->getCalculatedInfo(
            $input, $bank
        );

        echo $this->components->get('view')->render($template, [
                'templateContext' => 'bank',
                'input' => $input,
                'bank' => $bank,
                'outputArray' => $financeOutput
            ]
        );
    }

    public function getCalculatedInfo(
        $input, $bank
    )
    {
        $toPayOrigin = $input['zu_erreichen'];
        
        $constData = new ConstData();
        $constData->setPayOrigin($toPayOrigin);
        $bankData = new BankData();
        $bankData->setZins($bank['zins']);
        $constData->setBankData($bankData);
        $yearsBegin = 15;
        $constData->setYearsFirstHalf($yearsBegin);
        $monthPerYear = 12;
        $inPerYear = $input['einzahlung_pro_jahr']; // per month ca. 750
        
        $financeBank15Years = 0;
        $taxes = 0;
        $steps = 0;
        while($toPayOrigin > $financeBank15Years) {
            $financeBankTax = new FinanceBankTax($yearsBegin, $inPerYear, $constData);
            $financeBankTax->calculate();
            $amount = $financeBankTax->getAmountTaxStatus()->getAmount();
            
            $taxes = array_sum($financeBankTax->getAmountTaxStatus()->getTaxArray());
            $financeBank15Years = $amount - $taxes;
            if ($toPayOrigin > $financeBank15Years) {
                $inPerYear += 5;
                $steps++;
            }
        }

        $noteMessage = '';

        $financeOutput = [];
        
        if (0 == $steps) {
            $noteMessage = 'in_pro_jahr ist zu hoch angesezt';
            $financeOutput['notiz'] = $noteMessage;
        }
        
        
        

        $dump = print_r($financeBank15Years, true);
        error_log(PHP_EOL . '-$- in ' . basename(__FILE__) . ':' . __LINE__ . ' in ' . __METHOD__ . PHP_EOL . '*** $financeBank15Years ***' . PHP_EOL . " = " . $dump . PHP_EOL);
        $dump = print_r($inPerYear, true);
        error_log(PHP_EOL . '-$- in ' . basename(__FILE__) . ':' . __LINE__ . ' in ' . __METHOD__ . PHP_EOL . '*** $inPerYear ***' . PHP_EOL . " = " . $dump . PHP_EOL);
        
        $financeOutput = array_merge($financeOutput, [
            'abgezahlte_summe' => $financeBank15Years,
            'abgezahlte_summe_plus_zins' => $toPayOrigin + $taxes,
            'zu_zahlen_pro_jahr' => $inPerYear,
            'zu_zahlen_pro_monat' => $inPerYear/$monthPerYear
        ]);
        
        return $financeOutput;
    }

//    public function prepare()
//    {
//
//    }
//
//    public function render()
//    {
//
//    }
}