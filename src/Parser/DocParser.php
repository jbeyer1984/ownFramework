<?php

namespace MyApp\src\Parser;

class DocParser
{

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
     * @var
     */
    private $numberStrings;

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

    public function prepareLinesForConvert()
    {
        $this->parseTextToLines($this->text);
        $this->goThrough($this->lines);
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
    protected function goThrough($lines)
    {
        foreach ($lines as $line) {
            $beginOfLine = $this->returnFoundNumberTag('#', $line);
            if (!empty($beginOfLine)) {
                $this->numberTagStrings[] = $beginOfLine;
            }
        }
    }

    /**
     * @param array $numberTagStrings
     */
    public function convertNumberTagStringsToNumbers($numberTagStrings)
    {
        $indents = [0]; // store indents to 
        $countForIndents = []; // store counts for indentation
        $currentNumberStringArray = [];

        foreach ($numberTagStrings as $index => $line) {
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
                        $countForIndents[$fakePopped] = 0;
                    } else {
                        $countForIndents[$fakePopped] += 1;
                    }
                    $resetIndentation = true;
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

            $this->numberStrings[] = preg_replace('/[^ ]/', '', $line) . implode('.', $currentNumberStringArray);
        }
    }

    /**
     * @param string $tag
     * @param string $line
     * @return mixed|string
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
     * @param $lines
     * @param $numberTagStrings
     * @param $numberStrings
     */
    public function replaceConvertedLinesWithUsualText($lines, $numberTagStrings, $numberStrings)
    {
        foreach ($this->lines as $key => $line) {
            if (-1 < strpos($line, $this->numberTagStrings[$key])) {
                $line = str_replace($this->numberTagStrings[$key], $this->numberStrings[$key], $line);
                $dump = print_r($line, true);
                error_log(PHP_EOL . '-$- in ' . basename(__FILE__) . ':' . __LINE__ . ' -> ' . __METHOD__ . PHP_EOL . '*** $line ***' . PHP_EOL . " = " . $dump . PHP_EOL);
            }
        }
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
     * @return mixed
     */
    public function getNumberStrings()
    {
        return $this->numberStrings;
    }

    /**
     * @param mixed $numberStrings
     * @return DocParser
     */
    public function setNumberStrings($numberStrings)
    {
        $this->numberStrings = $numberStrings;

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