<?php


namespace MyApp\src\Parser\PostgresLog\Section;

class SectionDispatcher
{
    /**
     * @var array
     */
    private $linesArray;

    /**
     * @var array
     */
    private $grepLines;

    /**
     * SectionDispatcher constructor.
     * @param array $linesArray
     */
    public function __construct(array $linesArray)
    {
        $this->linesArray = $linesArray;
        $this->init();
    }

    protected function init()
    {
        $this->grepLines = [];
    }

    /**
     * @param Section $section
     */
    public function dispatch(Section $section)
    {
        $this->init();
        $preConditionHit = false;
        $foundCollectorBegin = false;
        $foundCollectorEnd = false;
        $foundCollectorBeginOnSameLine = false;
        $foundCollectorEndOnSameLine = false;
        $postConditionHit = false;
        $linesToCollect = [];
        
        $linesArrayCount = count($this->linesArray)-1;
        foreach ($this->linesArray as $key => $line) {
            if (!$preConditionHit) {
                $preConditionHit = $section->getPreCondition()->find($line);
            }

            if ($preConditionHit && !$foundCollectorBegin) { // have to check after collectorEnd because same line
                $lineGrep = $section->getCollectorBegin()->getGrepString($line);
                if ('' != $lineGrep) {
                    $foundCollectorBegin = true;
                    $foundCollectorBeginOnSameLine = true;
                }
                $lineGrep = $section->getCollectorEnd()->getGrepString($line);
                if ('' != $lineGrep) {
                    $foundCollectorEnd = true;
                    $foundCollectorEndOnSameLine = true;
                }
            }

            if ($foundCollectorBeginOnSameLine && $foundCollectorEndOnSameLine) {
                $lineToGrep = $section->getCollectorEnd()->getGrepString($line); // check this

                if ($linesArrayCount != $key) {
                    $nextLine = $this->linesArray[$key + 1];
                    $postConditionHit = $section->getPostCondition()->find($nextLine);
                }

                $lineToGrep = $this->getGreppedStringAfterLastColon($lineToGrep);
                
                $linesToCollect[] = $lineToGrep;
            }

            if ($preConditionHit && !$foundCollectorEnd) {
                 if($foundCollectorBeginOnSameLine) {
                     $lineToGrep = $section->getCollectorBegin()->getGrepString($line);

                     $lineToGrep = $this->getGreppedStringAfterLastColon($lineToGrep);

                    $foundCollectorBeginOnSameLine = false;
                } else {
                    $lineToGrep = $section->getCollectorEnd()->getGrepString($line);
                    if ('' != $lineToGrep) {
                        $foundCollectorEnd = true;
                    } else { // fetch whole line
                        $lineToGrep = $line;
                    }
                }

                if ($linesArrayCount != $key) {
                    $nextLine = $this->linesArray[$key + 1];
                    $postConditionHit = $section->getPostCondition()->find($nextLine);
                }
                
                $linesToCollect[] = $lineToGrep;
            }
            
            if ($foundCollectorEnd && $postConditionHit) {
                $this->grepLines[] = implode(PHP_EOL, $linesToCollect);

                $preConditionHit = false;
                $foundCollectorBegin = false;
                $foundCollectorEnd = false;
                $foundCollectorBeginOnSameLine = false;
                $foundCollectorEndOnSameLine = false;
                $postConditionHit = false;
                $linesToCollect = [];
            }
            
            if ($linesArrayCount == $key) {
                if ($foundCollectorEnd && !$postConditionHit) {
                    $this->grepLines[] = implode(PHP_EOL, $linesToCollect);
                }
            }
        }
    }

    /**
     * @return array
     */
    public function getGrepLines()
    {
        return $this->grepLines;
    }

    /**
     * @param string $lineToGrep
     * @return string
     */
    protected function getGreppedStringAfterLastColon($lineToGrep)
    {
        $foundPos = strrpos($lineToGrep, ':');
        if (false !== $foundPos) {
            $lineToGrep = substr($lineToGrep, $foundPos + 1);
        }

        return $lineToGrep;
    }
}