<?php

namespace MyApp\src\Parser\BeforeRender\Strategy\Expression;

use MyApp\src\Evaluator\Evaluator;
use MyApp\src\Parser\BeforeRender\Strategy\Expression\Filter\VarDelegateExpressionFilter;
use MyApp\src\Parser\BeforeRender\Strategy\Expression\Filter\VarFunctionExpressionFilter;
use MyApp\src\Parser\BeforeRender\Strategy\Expression\Filter\VarOnlyExpressionFilterRight;

class Expression extends ExpressionAbstract
{

    /**
     * @var Expression
     */
    protected $parent;

    /**
     * @var LineExpression
     */
    protected $lineExpression;


    /**
     * @var Expression
     */
    protected $child;

    /**
     * @var bool
     */
    protected $assignment;

    /**
     * @var string
     */
    protected $identifier;

    /**
     * @var
     */
    protected $leftIsAttribute;

    public function __construct()
    {
    }

    public function init()
    {
        return $this;
    }

    /**
     * @param Expression $expression
     */
    public function evaluate(Expression $expression)
    {
        $this->parent = $expression;
        $this->lineExpression = $expression->getLineExpression();
        $expression->setChild($this);
        // ?identifierExists
        //   identifier

        /************
         *
         * options:
         * $var = $class->functionName($whatEver);
         * $var = $class::functionName($whatEver);
         * $var = implode($whatEver, $whatEver2);
         * $var = $var2;
         *
         * $var->functionName($whatEver);
         * $var->attribute->functionName($whatEver);
         * $var->getClass()->functionName($whatEver);
         ************/
        
        // create new if for text getRight() and getLeft() null, with text consists '='
        
        if (null == $expression->getRight() && strpos($expression->getLeft(), '=')) {
            Evaluator::getInstance()->pushConditionArray(0, '$ = $');

            $newExpression = new Expression();
            $wholeExpressionExploded = explode('=', $expression->getLeft());
            $this->left = array_shift($wholeExpressionExploded);
            $this->right = implode('=', $wholeExpressionExploded);
            $this->assignment = true;
            $newExpression
                ->init()//        ->setWholeExpression($this->right);
            ;

            $newExpression->evaluate($this);
        }

        if (null == $expression->getRight()) {
            return;
        }


        // check assignment to left
        $filter = new VarOnlyExpressionFilterRight();
        if ($filter->filter($expression) && $expression->isAssignment()) {
            Evaluator::getInstance()->pushConditionArray(1, '$ = a($)');
            
            $this->left = trim($expression->getRight());

            $this->marked = true;
            $this->lineExpression->addToPotentialVars($this->left);

            return;
        }

        $filter = new VarDelegateExpressionFilter();
        if ($filter->filter($expression)) {
            Evaluator::getInstance()->pushConditionArray(2, '$ = $->b');
            
            $rightExpressionExploded = explode('->', $expression->getRight());
            $this->left = array_shift($rightExpressionExploded);
            $this->right = implode('->', $rightExpressionExploded);
            $this->leftIsAttribute = true;
            $newExpression = new Expression();
            $newExpression
                ->init()
            ;


            $newExpression->evaluate($this);
            
            return;
        }

        $filter = new VarFunctionExpressionFilter();
        if ($filter->filter($expression)) {
            Evaluator::getInstance()->pushConditionArray(3, '$ = $->b( , )');
            
            $rightExpressionExploded = explode(')', $expression->getRight());
            if (1 < count($rightExpressionExploded)) {
                // existing of delegate( , )->delegate()
            }
            $firstBracketExpression = [];
            $parameters = [];
            if (isset($rightExpressionExploded[0])) {
                $firstBracketExpression = $rightExpressionExploded[0];
                $separateParameters = explode('(', $firstBracketExpression);
                array_shift($separateParameters);
                $parametersString = $separateParameters[0];
                $parameters = explode(',', $parametersString);
            }
            
            if (!empty($firstBracketExpression)) {
                foreach ($parameters as $key => $potentialVarString) {
                    $parameters[$key] = trim($potentialVarString);
                }
                $this->lineExpression->addToPotentialVars($parameters);
                
                // spread
                
                // search up
                // find min distance of line with occurrences
            }
        }
    }

    /**
     * @return Expression
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param Expression $parent
     * @return Expression
     */
    public function setParent($parent)
    {
        $this->parent = $parent;

        return $this;
    }


    /**
     * @return LineExpression
     */
    public function getLineExpression()
    {
        return $this->lineExpression;
    }

    /**
     * @param LineExpression $lineExpression
     * @return Expression
     */
    public function setLineExpression($lineExpression)
    {
        $this->lineExpression = $lineExpression;

        return $this;
    }

    /**
     * @return Expression
     */
    public function getChild()
    {
        return $this->child;
    }

    /**
     * @param Expression $child
     * @return Expression
     */
    public function setChild($child)
    {
        $this->child = $child;

        return $this;
    }

    /**
     * @return string
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }

    /**
     * @param string $identifier
     * @return Expression
     */
    public function setIdentifier($identifier)
    {
        $this->identifier = $identifier;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isAssignment()
    {
        return $this->assignment;
    }

    /**
     * @param boolean $assignment
     * @return Expression
     */
    public function setAssignment($assignment)
    {
        $this->assignment = $assignment;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getLeftIsAttribute()
    {
        return $this->leftIsAttribute;
    }

    /**
     * @param mixed $leftIsAttribute
     * @return ExpressionAbstract
     */
    public function setLeftIsAttribute($leftIsAttribute)
    {
        $this->leftIsAttribute = $leftIsAttribute;

        return $this;
    }
}