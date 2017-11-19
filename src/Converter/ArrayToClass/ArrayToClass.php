<?php

namespace MyApp\src\Converter\ArrayToClass;

use MyApp\src\Converter\ArrayToClass\Template\SkeletonClass;

class ArrayToClass
{
    /**
     * @var array
     */
    private $classArray;

    /**
     * @var array
     */
    private $stackLevelLookup;

    /**
     * @var array
     */
    private $classData;

    /**
     * @var array
     */
    private $classNameStack;

    /**
     * ArrayToClass constructor.
     * @param $classArray
     */
    public function __construct($classArray)
    {
        $this->classArray = $classArray;
    }

    protected function init()
    {
        $this->classData        = [];
        $this->classNameStack   = [];
        $this->stackLevelLookup = [];
    }

    public function translate()
    {
        $level = 0;
        $data = [];
        foreach ($this->classArray as $identifier => $item) {
            $data = $this->translateRecursive($level, $identifier, $item);
        }
        
        foreach ($this->classNameStack as $skeletonClassName) {
            if (isset($data[$skeletonClassName]['attributes'])) {
                $skeletonClass = new SkeletonClass($skeletonClassName);
                $skeleton = $skeletonClass->getCreatedTemplate($data[$skeletonClassName]);
            }
        }
        
        return $data;
    }

    private function translateRecursive($level, $identifier, $item)
    {
        $level++;
        if (is_array($item)) {
            $this->classNameStack[] = $identifier;
            $this->stackLevelLookup[$level] = $identifier;
            if (1 < $level) {
                $lastSkeletonClass = $this->stackLevelLookup[$level-1];
                $this->classData[$lastSkeletonClass]['attributes'][] = $identifier;
            }
            foreach ($item as $pieceIdentifier => $piece) {
                $this->translateRecursive($level, $pieceIdentifier, $piece);    
            }
        } else {
            $stackLevel = $level-1;
            $lastSkeletonClass = $this->stackLevelLookup[$stackLevel]; 
            $this->classData[$lastSkeletonClass]['attributes'][] = $item;
        }
        
        return $this->classData;
    }
}