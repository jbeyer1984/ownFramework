<?php
define( 'VAR_WWW', '/var/www/ownFramework' );
//define( 'VAR_WWW', 'D:\\Programme\\xampp\\htdocs\\ownFramework\\' );
require_once(VAR_WWW.'/src/bootstrap.php');
require_once(ROOT_PATH.'/vendor/autoload.php');

use MyApp\src\Parser\FuncParameterParser;

class FuncParameterParserTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var FuncParameterParserMock
     */
    private $funcParameterParser;

    public function setUp()
    {
        $this->funcParameterParser = new FuncParameterParserMock();
        $this->funcParameterParser->init();
    }

    /**
     * @dataProvider testParseProvider_thatPass
     * @param $parametersString
     * @param $expectedArray
     */
    public function testGeneratedIdentifiersArray_thatPass($parametersString, $expectedArray)
    {
        $this->funcParameterParser->setParametersString($parametersString);

        $this->assertEquals($expectedArray, $this->funcParameterParser->generatedIdentifiersArray());
    }

    public function testParseProvider_thatPass()
    {
        return [
            [
                '$voList',
                [
                    'voList',
                ]
            ],
            [
                '$voList, $distributionChannels',
                [
                    'voList',
                    'distributionChannels',
                ]
            ],
            [
                '$voList = null',
                [
                    'voList',
                ]
            ],
            [
                '$voList = null, $distributionChannels = []',
                [
                    'voList',
                    'distributionChannels',
                ]
            ],
            [
                '$voList = null, $distributionChannels',
                [
                    'voList',
                    'distributionChannels',
                ]
            ],
        ];
    }

    public function testBuiltArrayString_thatPass()
    {
        $arrayString = $this->funcParameterParser->builtArrayString([
            'one',
            'two',
        ]);

        $expectedString =
'[
    \'one\' => $one,
    \'two\' => $two,
]';
        $this->assertEquals($expectedString, $arrayString);
    }

    public function testParse_thatPass()
    {
        $parametersString = '$language_code,$discountId,$db,$where';
        $this->funcParameterParser->setParametersString($parametersString);
        $this->funcParameterParser->parse();
        $stringOut = $this->funcParameterParser->getStringOut();

        $dump = print_r(__DIR__, true);
        error_log(PHP_EOL . '-$- in ' . basename(__FILE__) . ':' . __LINE__ . ' -> ' . __METHOD__ . PHP_EOL . '*** __DIR__ ***' . PHP_EOL . " = " . $dump . PHP_EOL);


        $dump = print_r($stringOut, true);
        error_log(PHP_EOL . '-$- in ' . basename(__FILE__) . ':' . __LINE__ . ' -> ' . __METHOD__ . PHP_EOL . '*** $stringOut ***' . PHP_EOL . " = " . $dump . PHP_EOL);

        $this->assertTrue(true);
    }
}

class FuncParameterParserMock extends FuncParameterParser
{

}