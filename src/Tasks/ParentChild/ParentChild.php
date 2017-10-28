<?php

namespace MyApp\src\Tasks\ParentChild;

class ParentChild
{
    /**
     * @var ParentModel
     */
    private $parent;

//    /**
//     * @var ChildIterator
//     */
//    private $childIterator;
    
    public function __construct($parent)
    {
        $this->parent = $parent;
        $this->parent->setParentChild($this);
        
        $this->init();
    }

    protected function init()
    {
//        $this->childIterator = null;
    }

//    public function fetchChildren($array)
//    {
//        $this->childIterator = new ChildIterator($array);
//        
//        return $this->childIterator;
//    }
}
