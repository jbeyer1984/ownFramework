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

    public function testMarkedAnkers()
    {
        $text =
<<< EOF
#;; tag ;;
EOF
        ;
        
        $this->docParser->setText($text);
        $this->docParser->prepareLinesForConvert();
        $markedAnkers = $this->docParser->getMarkedAnkers();
        
        $expectedMarkedAnkers = [
            ';; tag ;;' => '0' // value is here line number
        ];
        
        $this->assertEquals($expectedMarkedAnkers, $markedAnkers);
    }

    /**
     * @dataProvider prepareLinesForConvert_thatPassProvider
     * @param $text
     * @param $fragmentsText
     */
    public function testPrepareLinesForConvert_thatPass($text, $fragmentsText)
    {
        $this->docParser->setText($text);
        $this->docParser->prepareLinesForConvert();
        $numberTagStrings = $this->docParser->getNumberTagStrings();
        $lineFragments = implode("\n", $numberTagStrings);
        
        $expectedFragmentsText = $fragmentsText;
        
        $this->assertEquals($expectedFragmentsText, $lineFragments);
    }

    public function prepareLinesForConvert_thatPassProvider()
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
#;; so what ;;
  # so that
    no tag
    # so this
  # easy
    # something ;; mark ;;
# new secion
EOF;
        $files = [];
        foreach (glob(__DIR__ . '/*.txt') as $file) {
            if (false === strpos($file, '_parsed')) {
                $files[] = $file;
            }
        }

        foreach ($files as $file) {
            $this->docParser
//                ->setFileToRead($fileToRead)
//                ->setFileToWrite($fileToWrite);
            ;
//            $this->docParser->readFromFile();

            $this->docParser
                ->setText($text)
                ->setFileToWrite('/var/www/ownFramework/public/text/textOut.txt');
            ;


            $this->docParser->prepareLinesForConvert();
            $lines = $this->docParser->getLines();
            $numberTagStrings = $this->docParser->getNumberTagStrings();

            $this->docParser->convertNumberTagStringsToNumbers($numberTagStrings);
            $numberStrings = $this->docParser->getNumberStrings();

            $this->docParser->replaceConvertedLinesWithUsualText($lines, $numberTagStrings, $numberStrings);

            $this->docParser->writeToFile();

            break;
        }
    }

}

class DocParserMock extends DocParser
{
    
}