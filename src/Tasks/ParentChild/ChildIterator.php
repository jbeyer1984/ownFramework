<?php

namespace MyApp\src\Tasks\ParentChild;

use Iterator;

class ChildIterator implements Iterator
{
    private $childList;
    
    private $position;
    
    public function __construct($childList)
    {
        $this->childList = $childList;
        $this->init();
    }

    protected function init()
    {
        $this->position = 0;
    }

    public function current()
    {
        return $this->childList[$this->position];
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
        return $this->position < count($this->childList);
    }

    public function rewind()
    {
        $this->position = 0;
    }


}
