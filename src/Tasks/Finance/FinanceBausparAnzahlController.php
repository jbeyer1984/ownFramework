<?php


namespace MyApp\src\Tasks\Finance;


use MyApp\src\Finance\ConstData\ConstData;
use MyApp\src\Finance\ConstData\SaveBauData;
use MyApp\src\Finance\DataProvider\VariableCostsDataProvider;
use MyApp\src\Finance\SaveCostsBeginYears;
use MyApp\src\Tasks\Tasks;

class FinanceBausparAnzahlController extends Tasks
{
    public function __construct()
    {
        parent::__construct();
    }

    public function bausparanzahl(
        $input = [], $bauspar = []
    )
    {
        $template = 'Finance/' . strtolower(__FUNCTION__) . '/' . strtolower(__FUNCTION__);
        if ('post' == strtolower($_SERVER['REQUEST_METHOD'])) {
//            $template .= '_output_rendered.twig';
            $template .= '.twig';
        } else {
            $template .= '.twig';
        }

        if (empty($input) && empty($bauspar)) {
            echo $this->components->get('view')->render($template, [
                    'input' => [
                        'zu_erreichen' => '89600'
                    ],
                    'bauspar' => [
                        'ansparphase' => '0.2',
//                        'zahlphase' => '1.5'
                    ],
                    'templateContext' => 'bausparanzahl',
                ]
            );
            return;
        }
        
        $financeOutput = $this->getCalculatedInfo(
            $input, $bauspar
        );

        echo $this->components->get('view')->render($template, [
                'templateContext' => 'bausparanzahl',
                'input' => $input,
                'bauspar' => $bauspar,
                'outputArray' => $financeOutput
            ]
        );
//        $outputArray = [
//            'one' => 'firstValue'
//        ];
//
//        echo $this->components->get('view')->render($template, array(
//            'templateContext' => 'start',
////            'inputString' => $inputArray,
//            'outputString' => $outputArray,
//        ));
    }

    public function getCalculatedInfo(
        $input, $bauspar
    )
    {
        $constData = new ConstData();
        $saveBauData = new SaveBauData();
        $saveBauData
            ->setAnsparphase($bauspar['ansparphase'])
//            ->setZahlphase($bauspar['zahlphase'])
        ;
        $constData
            ->setSaveBauData($saveBauData)
        ;
        $montPerYear = 12;
//        $toPayOrigin = 89600;
        $toPayOrigin = $input['zu_erreichen'];
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
        
        $financeOutput = [
            'abgezahlte_summe' => $savePhase15Years,
            'zu_zahlen_pro_jahr' => $inPerYear,
            'zu_zahlen_pro_monat' => $inPerYear/12
        ];
        
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