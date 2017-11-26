<?php


namespace MyApp\src\Parser\PostgresLog\Section;


class SectionRow
{
    /**
     * @var array[Section]
     */
    private $sectionArray;
    
    public function __construct()
    {
        $this->init();
    }

    protected function init()
    {
        $this->sectionArray = [];
    }

    /**
     * @param Section $section
     * @return $this
     */
    public function add(Section $section)
    {
        $this->sectionArray[] = $section;
        
        return $this;
    }

    /**
     * @return array[Section]
     */
    public function getSectionArray()
    {
        return $this->sectionArray;
    }
}