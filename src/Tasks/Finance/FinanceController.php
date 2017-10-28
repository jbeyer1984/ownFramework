<?php


namespace MyApp\src\Tasks\Finance;


use MyApp\src\Finance\ConstData\BankData;
use MyApp\src\Finance\ConstData\ConstData;
use MyApp\src\Finance\ConstData\HomeData;
use MyApp\src\Finance\ConstData\SaveBauData;
use MyApp\src\Finance\Finance;
use MyApp\src\Tasks\Tasks;

class FinanceController extends Tasks
{
    public function __construct()
    {
        parent::__construct();
    }

    public function overview()
    {
        $template = 'Finance/' . strtolower(__FUNCTION__) . '/' . strtolower(__FUNCTION__);
        if ('post' == strtolower($_SERVER['REQUEST_METHOD'])) {
//            $template .= '_output_rendered.twig';
            $template .= '.twig';
        } else {
            $template .= '.twig';
        }

        echo $this->components->get('view')->render($template, [
            'templateContext' => 'overview',
        ]);
    }

    public function start(
        $constdata = [], $home = [], $bauspar = [], $bank = []
    )
    {
        $template = 'Finance/' . strtolower(__FUNCTION__) . '/' . strtolower(__FUNCTION__);
        if ('post' == strtolower($_SERVER['REQUEST_METHOD'])) {
//            $template .= '_output_rendered.twig';
            $template .= '.twig';
        } else {
            $template .= '.twig';
        }

        if (empty($home) && empty($bauspar) && empty($bank)) {
            echo $this->components->get('view')->render($template, [
                'constdata' => [
                    'schaetzung_pro_monat' => 400,
                    'erstenjahre' => 15,
                    'zweitenjahre' => 15
                ],
                'home' => [
                    'wohnung' => '224000',
                    'eigenkapital' => '43000',
                    'grunderwerbssteuer' => '4.5',
                    'notar' => '2.0',
                    'makler' => '6',
                    'sonstiges' => '2000'
                ],
                'bauspar' => [
                    'ansparphase' => '0.2',
                    'zahlphase' => '1.5'
                ],
                'bank' => [
                    'zins' => '2.15'
                ],
                'templateContext' => 'start',
                ]
            );
            return;
        }
        
        $financeOutput = $this->getCalculatedInfo(
            $constdata, $home, $bauspar, $bank
        );

        echo $this->components->get('view')->render($template, [
                'templateContext' => 'start',
                'constdata' => $constdata,
                'home' => $home,
                'bauspar' => $bauspar,
                'bank' => $bank,
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
        $constdata, $home, $bauspar, $bank
    )
    {
        $monthsPerYear = 12;
            
        $homeData = new HomeData();
        $homeData
            ->setWohnung($home['wohnung'])
            ->setOwnMoney($home['eigenkapital'])
            ->setGrunderwerbssteuer($home['grunderwerbssteuer'])
            ->setNotar($home['notar'])
            ->setMakler($home['makler'])
            ->setSonstiges($home['sonstiges'])
        ;
        $saveBauData = new SaveBauData();
        $saveBauData
            ->setSchaetzungperjahr($constdata['schaetzung_pro_monat'] * $monthsPerYear)
            ->setAnsparphase($bauspar['ansparphase'])
            ->setZahlphase($bauspar['zahlphase'])
        ;
        $bankData = new BankData();
        $bankData
            ->setZins($bank['zins'])
        ;
        $constData = new ConstData();
        $dump = print_r($constdata, true);
        error_log(PHP_EOL . '-$- in ' . basename(__FILE__) . ':' . __LINE__ . ' in ' . __METHOD__ . PHP_EOL . '*** $constdata ***' . PHP_EOL . " = " . $dump . PHP_EOL);
        
        $constData
            ->setYearsFirstHalf($constdata['erstenjahre'])
            ->setYearsSecondHalf($constdata['zweitenjahre'])
            ->setHomeData($homeData)
            ->setSaveBauData($saveBauData)
            ->setBankData($bankData)
        ;

        $finance = new Finance($constData);
        $finance->calculation();
        
        $dump = print_r("huuuuuu", true);
        error_log(PHP_EOL . '-$- in ' . basename(__FILE__) . ':' . __LINE__ . ' in ' . __METHOD__ . PHP_EOL . '*** "huuuuuu" ***' . PHP_EOL . " = " . $dump . PHP_EOL);
        
        
        $financeOutput = $finance->getOutput();
        $dump = print_r($financeOutput, true);
        error_log(PHP_EOL . '-$- in ' . basename(__FILE__) . ':' . __LINE__ . ' in ' . __METHOD__ . PHP_EOL . '*** $financeOutput ***' . PHP_EOL . " = " . $dump . PHP_EOL);
        
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