<?php
define( 'VAR_WWW', '/var/www/ownFramework' );
//define( 'VAR_WWW', 'D:\\Programme\\xampp\\htdocs\\ownFramework\\' );
require_once(VAR_WWW.'/src/bootstrap.php');
require_once(ROOT_PATH.'/vendor/autoload.php');

use MyApp\src\Parser\DocParser;

class DocParserTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var DocParserMock
     */
    private $docParser;

    public function setUp()
    {
        $this->docParser = new DocParserMock();
        $this->docParser->init();
    }

    public function testImplode()
    {
        $arr = [1];
        $imploded = implode('.', $arr);
        $this->assertEquals('1.', $imploded);
    }

    /**
     * @dataProvider run_thatPassProvider
     * @param $text
     * @param $fragmentsText
     */
    public function testRun_thatPass($text, $fragmentsText)
    {
        $this->docParser->setText($text);
        $this->docParser->prepareLinesForConvert();
        $numberTagStrings = $this->docParser->getNumberTagStrings();
        $lineFragments = implode("\n", $numberTagStrings);
        
        $expectedFragmentsText = $fragmentsText;
        
        $this->assertEquals($expectedFragmentsText, $lineFragments);
    }

    public function run_thatPassProvider()
    {
        return [
            [
                '#',
                '#'
            ],
            [
                '# so what',
                '#'
            ],
            [
                '  # so what',
                '  #'
            ],
            [
                  "# so what\n"
                . "  # so that"
                ,
                  "#\n" 
                . "  #"
            ],
            [
                  "# so what\n"
                . "  # so that\n"
                . "    # so this"
                ,
                  "#\n"
                . "  #\n"
                . "    #"
            ],
        ];
    }

    /**
     * @dataProvider convertNumberTagStringsToNumbersProvider
     * @param $numberTagString
     * @param $expectedNumberStrings
     */
    public function testConvertNumberTagStringsToNumbers($numberTagString, $expectedNumberStrings)
    {
        $this->docParser->convertNumberTagStringsToNumbers($numberTagString);
        $numberStrings = $this->docParser->getNumberStrings();
        
        $this->assertEquals($expectedNumberStrings, $numberStrings);
    }

    public function convertNumberTagStringsToNumbersProvider()
    {
        return [
            [
                [
                    "#",
                    "#",
                    "  #",
                ],
                [
                    "1.",
                    "2.",
                    "  2.1",
                ]
            ],
            [
                [
                    "#",
                    "  #",
                    "    #",
                ],
                [
                    "1.",
                    "  1.1",
                    "    1.1.1",
                ]
            ],
            [
                [
                    "#",
                    "  #",
                    "    #",
                    "  #",
                    "#",
                ],
                [
                    "1.",
                    "  1.1",
                    "    1.1.1",
                    "  1.2",
                    "2.",
                ]
            ],
            [
                [
                    "#",
                    "  #",
                    "    #",
                    "#",
                    "  #",
                ],
                [
                    "1.",
                    "  1.1",
                    "    1.1.1",
                    "2",
                    "  2.1",
                ]
            ],
        ];
    }

    public function testAll()
    {
        $text = <<<EOF
# so what
  # so that
    # so this
  # easy
    # something
# new secion
EOF;
        $this->docParser->setText($text);
        $this->docParser->prepareLinesForConvert();
        $lines = $this->docParser->getLines();
        $numberTagStrings = $this->docParser->getNumberTagStrings();

        $this->docParser->convertNumberTagStringsToNumbers($numberTagStrings);
        $numberStrings = $this->docParser->getNumberStrings();

        $this->docParser->replaceConvertedLinesWithUsualText($lines, $numberTagStrings, $numberStrings);
    }

}

class DocParserMock extends DocParser
{
    
}