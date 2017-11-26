<?php


namespace MyApp\src\Parser\PostgresLog\Section;


class SectionRowDispatcher
{
    /**
     * @var array[string]
     */
    private $lineArray;

    /**
     * @var array
     */
    private $sectionTextArray;

    /**
     * SectionRowDispatcher constructor.
     * @param $lineArray
     */
    public function __construct($lineArray)
    {
        $this->lineArray = $lineArray;
        $this->init();
    }

    protected function init()
    {
        $this->sectionTextArray = [];
    }


    /**
     * @param SectionRow $sectionRow
     */
    public function dispatch(SectionRow $sectionRow)
    {
        $sectionDispatcher = new SectionDispatcher($this->lineArray);
        foreach ($sectionRow->getSectionArray() as $section) {
            $dump = print_r("here", true);
            error_log(PHP_EOL . '-$- in ' . basename(__FILE__) . ':' . __LINE__ . ' in ' . __METHOD__ . PHP_EOL . '*** "here" ***' . PHP_EOL . " = " . $dump . PHP_EOL);
            
            $sectionDispatcher->dispatch($section);
            $this->sectionTextArray[] = $sectionDispatcher->getGrepLines();
        }
    }

    /**
     * @return array
     */
    public function getSectionTextArray()
    {
        return $this->sectionTextArray;
    }
}