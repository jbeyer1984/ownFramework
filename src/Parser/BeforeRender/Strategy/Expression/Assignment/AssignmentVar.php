<?php

namespace MyApp\src\Parser\BeforeRender\Strategy\Expression\Assignment;

class AssignmentVar
{
    /**
     * @var string
     */
    protected $var;

    /**
     * @var string
     */
    protected $operator;
    
    CONST OPERATOR_EQUAL = '='; 
    CONST OPERATOR_DELEGATE = '->'; 
    
    public function __construct()
    {
        $this->init();
    }

    protected function init()
    {
    }

    /**
     * @return string
     */
    public function getVar()
    {
        return $this->var;
    }

    /**
     * @param string $var
     * @return AssignmentVar
     */
    public function setVar($var)
    {
        $this->var = $var;

        return $this;
    }

    /**
     * @return string
     */
    public function getOperator()
    {
        return $this->operator;
    }

    /**
     * @param string $operator
     * @return AssignmentVar
     */
    public function setOperator($operator)
    {
        $this->operator = $operator;

        return $this;
    }
}
