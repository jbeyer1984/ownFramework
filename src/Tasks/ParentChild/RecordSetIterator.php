<?php

namespace MyApp\src\Tasks\ParentChild;

use ArrayAccess;
use Iterator;

class RecordSetIterator implements Iterator
{
    private $recordSetArray;

    private $changedList;
    
    private $position;

//    /**
//     * @var ChildModel
//     */
//    private $specificChild;
    public function addEntries($entry)
    {
        $this->recordSetArray[] = $entry;
    }
    
    public function __construct($childListArray)
    {
        $this->recordSetArray = $childListArray;
        $this->init();
    }

    protected function init()
    {
        $this->position = 0;
        $this->changedList = [];
    }

    /**
     * @return ChildModel
     */
    public function current()
    {
        return $this->recordSetArray[$this->position];
    }

    public function next()
    {
        $this->position++;
    }

    public function key()
    {
        return $this->position;
    }

    public function valid()
    {
        return $this->position < count($this->recordSetArray);
    }

    public function rewind()
    {
        $this->position = 0;
    }
}
