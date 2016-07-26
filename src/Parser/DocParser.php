<?php

namespace MyApp\src\Parser;

class DocParser
{

    /**
     * @var string
     */
    private $fileToRead;

    /**
     * @var string
     */
    private $fileToWrite;

    /**
     * @var string
     */
    private $text;

    /**
     * @var array
     */
    private $lines;

    /**
     * @var array
     */
    private $numberTagStrings;

    /**
     * @var array
     */
    private $numberStrings;

    /**
     * @var array
     */
    private $markedAnkers;

    /**
     * @var string
     */
    private $outputText;

    public function __construct()
    {
    }

    /**
     * @return $this
     */
    public function init()
    {
        $this->initAttributes();
            
        return $this;
    }

    protected function initAttributes()
    {
        $this->text = '';
        $this->numberTagStrings = [];
        $this->numberStrings = [];
        $this->outputText = '';
        $this->lines = [];
    }

    public function readFromFile()
    {
        $this->text = file_get_contents($this->fileToRead);
    }

    public function writeToFile()
    {
        file_put_contents(
            $this->fileToWrite,
            implode(PHP_EOL, $this->lines)
        );
    }

    public function builtOutputText()
    {
        return implode(PHP_EOL, $this->lines);
    }

    public function prepareLinesForConvert()
    {
        $this->parseTextToLines($this->text);
        $this->goThroughLines($this->lines);
    }

    /**
     * @param string $text
     */
    protected function parseTextToLines($text)
    {
        $this->lines = explode("\n", $text);
    }

    /**
     * @param array $lines
     */
    protected function goThroughLines($lines)
    {
        $found = [];
        foreach ($lines as $lineNumber => $line) {
            $beginOfLine = $this->returnFoundNumberTag('#', $line);
            if (!empty($beginOfLine)) {
                $this->numberTagStrings[$lineNumber] = $beginOfLine;
            }
            
            if (-1 < strpos($line, '#;;')) {
                preg_match('/#(;;.+?;;)/', $line, $found);
                if (!empty($found)) {
                    $this->markedAnkers[$found[1]] = $lineNumber;
                    $found = [];
                }
            }
            
        }
    }

    /**
     * @param string $tag
     * @param string $line
     * @return string
     */
    protected function returnFoundNumberTag($tag, $line)
    {
        $found = [];
        preg_match('/\s*' . $tag . '/', $line, $found);
        if (!empty($found)) {
            return $found[0];
        }
        return '';
    }

    /**
     * @param array $numberTagStrings
     */
    public function convertNumberTagStringsToNumbers($numberTagStrings)
    {
        $indents = [0]; // store indents to 
        $countForIndents = []; // store counts for each section indentation
        $currentNumberStringArray = [];
        
        foreach ($numberTagStrings as $lineNumber => $line) {
            $sameIndent = false;
            $indent = false;
            $unindent = false;
            $unindentResetSets = []; // to store indentation for resetting counts of new section
            
            $count = substr_count($line, ' ');

            if ($count == end($indents)) { // check indentation is same as last
                array_pop($indents);
                $sameIndent = true;
            } elseif ($count > end($indents)) { // check indentation is bigger
                $indent = true;
            } elseif ($count < end($indents)) { // check indentation is smaller
                // pop elements from indents and store it into $unindentResetSets until same indentation is found
                $unindentResetSets[] = array_pop($indents);
                $condition = ($count < end($unindentResetSets));
                while ($condition && !empty($indents)) {
                    $unindentResetSets[] = array_pop($indents);
                    $condition = ($count < end($unindentResetSets));
                }
                $unindent = true;
            }
            
            $indents[] = $count;

            // set right counts in $countForIndents
            if (!isset($countForIndents[$count])) {
                $countForIndents[$count] = 1;
            } elseif ($sameIndent) {
                $countForIndents[$count] += 1;
            } elseif ($indent) {
                $countForIndents[$count] += 1;
            } elseif ($unindent) {
                // go through $unindentResetSets backward and check if new section has begun and set right count
                $resetIndentation = false;
                for ($i = count($unindentResetSets)-1; $i >= 0; $i--) {
                    $fakePopped = $unindentResetSets[$i];
                    if ($resetIndentation) {
                        $countForIndents[$fakePopped] = 0; // here is the trick with init count = 0
                    } else {
                        $countForIndents[$fakePopped] += 1; // there will be the increment of section number like 1.1, 1.2 e.g.
                    }
                    $resetIndentation = true; // after first section backwards the count will be reset
                }
            }

            // set $currentNumberStringArray to right identation
            if (empty($currentNumberStringArray)) {
                $currentNumberStringArray[] = $countForIndents[$count];
            } else {
                if ($sameIndent) {
                    array_pop($currentNumberStringArray);
                } elseif ($unindent) {
                    while (!empty($unindentResetSets)) {
                        array_pop($unindentResetSets);
                        array_pop($currentNumberStringArray);
                    }
                } elseif ($indent) {
                    // do nothing before
                }
                $currentNumberStringArray[] = $countForIndents[$count];
            }

            if (1 == count($currentNumberStringArray)) { // implement here strategy for other implementations
                $this->numberStrings[$lineNumber] = preg_replace('/[^ ]/', '', $line) . $currentNumberStringArray[0] . '.';
            } else {
                $this->numberStrings[$lineNumber] = preg_replace('/[^ ]/', '', $line) . implode('.', $currentNumberStringArray);   
            }
        }
    }

    /**
     * @param $lines
     * @param $numberTagStrings
     * @param $numberStrings
     */
    public function replaceConvertedLinesWithUsualText($lines, $numberTagStrings, $numberStrings)
    {
        if (empty($numberTagStrings)) {
            return;
        }
        
        $tempNumberString = $numberStrings;
        
        foreach ($lines as $lineNumber => $line) {
            if (!empty($this->markedAnkers)) {
                if (false !== strpos($line, '#;;')) {
                    $line = str_replace(';;', '', $line);
                } elseif (false !== strpos($line, ';;')) {
                    $found = [];
                    preg_match('/;;.+?;;/', $line, $found);
                    if (!empty($found)) {
                        $line = str_replace(
                            $found[0], 
                            str_replace(' ', '', $tempNumberString[$this->markedAnkers[$found[0]]]),
                            $line
                        );
                        $this->lines[$lineNumber] = $line;
                    }
                }    
            }

            if (!empty($numberTagStrings)) {
                if (-1 < strpos($line, reset($numberTagStrings))) {
                    $line = str_replace(reset($numberTagStrings), reset($numberStrings), $line);
                    array_shift($numberTagStrings);
                    array_shift($numberStrings);
                    $this->lines[$lineNumber] = $line;
                }
            }
        }
    }

    /**
     * @return string
     */
    public function getFileToRead()
    {
        return $this->fileToRead;
    }

    /**
     * @param string $fileToRead
     * @return $this
     */
    public function setFileToRead($fileToRead)
    {
        $this->fileToRead = $fileToRead;

        return $this;
    }

    /**
     * @return string
     */
    public function getFileToWrite()
    {
        return $this->fileToWrite;
    }

    /**
     * @param string $fileToWrite
     * @return $this
     */
    public function setFileToWrite($fileToWrite)
    {
        $this->fileToWrite = $fileToWrite;

        return $this;
    }

    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param string $text
     * @return DocParser
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * @return array
     */
    public function getLines()
    {
        return $this->lines;
    }

    /**
     * @param array $lines
     * @return DocParser
     */
    public function setLines($lines)
    {
        $this->lines = $lines;

        return $this;
    }

    /**
     * @return array
     */
    public function getNumberTagStrings()
    {
        return $this->numberTagStrings;
    }

    /**
     * @param array $numberTagStrings
     * @return DocParser
     */
    public function setNumberTagStrings($numberTagStrings)
    {
        $this->numberTagStrings = $numberTagStrings;

        return $this;
    }

    /**
     * @return array
     */
    public function getNumberStrings()
    {
        return $this->numberStrings;
    }

    /**
     * @param array $numberStrings
     * @return DocParser
     */
    public function setNumberStrings($numberStrings)
    {
        $this->numberStrings = $numberStrings;

        return $this;
    }

    /**
     * @return array
     */
    public function getMarkedAnkers()
    {
        return $this->markedAnkers;
    }

    /**
     * @param array $markedAnkers
     * @return DocParser
     */
    public function setMarkedAnkers($markedAnkers)
    {
        $this->markedAnkers = $markedAnkers;

        return $this;
    }

    /**
     * @return string
     */
    public function getOutputText()
    {
        return $this->outputText;
    }

    /**
     * @param string $outputText
     * @return DocParser
     */
    public function setOutputText($outputText)
    {
        $this->outputText = $outputText;

        return $this;
    }
}