<?php

namespace MyApp\src\Tasks\ParentChild;

class DocOrmAttributes
{
    /**
     * @var string
     */
    private $docToParse;

    /**
     * @var string
     */
    private $valuesToParse;

    /**
     * @var string
     */
    private $command;

    /**
     * @var array
     */
    private $values;

    /**
     * @var array
     */
    private $tupleValues;

    /**
     * DocOrmAttributes constructor.
     * @param string $docToParse
     */
    public function __construct($docToParse)
    {
        $this->docToParse = $docToParse;
        
        $this->init();   
    }

    protected function init()
    {
        $this->values = [];
        $this->tupleValues = [];
    }

    public function parse()
    {
        $explodedOrm = explode('(', $this->docToParse);
        $explodedCommand = $explodedOrm[0];
        $this->command = $explodedCommand;
        
        $explodedCommandValues  = explode(')', $explodedOrm[1])[0];
        $this->valuesToParse = $explodedCommandValues;

        $this->parseValues();
    }

    protected function parseValues()
    {
        $exploded = explode(',', $this->valuesToParse);
        foreach ($exploded as $expression) {
            $expression = trim($expression);
            if (false !== strpos($expression, '=')) {
                $explodedTuple                        = explode('=', $expression);
                $this->tupleValues[$explodedTuple[0]] = trim($explodedTuple[1], '"');
            }
        }
    }

    /**
     * @return string
     */
    public function getCommand()
    {
        return $this->command;
    }

    /**
     * @return array
     */
    public function getValues()
    {
        return $this->values;
    }

    /**
     * @return array
     */
    public function getTupleValues()
    {
        return $this->tupleValues;
    }
}