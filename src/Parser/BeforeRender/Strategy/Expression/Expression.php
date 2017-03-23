<?php

namespace MyApp\src\Parser\BeforeRender\Strategy\Expression;

use MyApp\src\Evaluator\Evaluator;
use MyApp\src\Parser\BeforeRender\Strategy\Expression\Assignment\AssignmentVar;
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
        $this->init();
    }

    protected function init()
    {
        return $this;
    }

    /**
     * @param Expression $expression
     */
    public function linkParent(Expression $expression)
    {
        $this->parent = $expression;
        $expression->setChild($this);
    }

    /**
     * @internal param Expression $expression
     */
    public function evaluate()
    {
        $expression = $this->parent; // work with parent, the backward link
        $this->lineExpression = $this->parent->getLineExpression();

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
        
        if (is_null($expression->getRight()) && strpos($expression->getLeft(), '=')) {
            $this->handleAssignment($expression);
            
            return;
            // search later in tree for equal expression chained together
        }
        
        if ($this->assignment && is_null($expression->getRight())) {
            
            return;
        }

        // check assignment to left
        $filter = new VarOnlyExpressionFilterRight();
        if ($filter->filter($expression) && $expression->isAssignment()) {
            $this->handleExpressionRight($expression);

            return;
        }

        $filter = new VarDelegateExpressionFilter();
        if ($filter->filter($expression)) {
            $this->handleDelegate($expression);
            
            return;
        }

        $filter = new VarFunctionExpressionFilter();
        if ($filter->filter($expression)) {
            $this->handleFunction($expression);
        }
    }

    /**
     * @param Expression $expression
     */
    protected function handleAssignment(Expression $expression)
    {
        Evaluator::getInstance()->pushConditionArray(0, '$ = $');

        $newExpression = new Expression();

        $wholeExpressionExploded = explode('=', $expression->getLeft());

        $this->left = trim(array_shift($wholeExpressionExploded));
        $this->right = implode('=', $wholeExpressionExploded);
        $this->assignment = true;
        $assignmentVar = new AssignmentVar();
        $assignmentVar
            ->setVar($this->left)
            ->setOperator(AssignmentVar::OPERATOR_EQUAL);
        ;
        
        $this->getLineExpression()->addToPotentialAssignments($assignmentVar);

        $this->setChild($newExpression);  // build link backward
        $newExpression->linkParent($this);

        $newExpression->evaluate();
    }

    /**
     * @param Expression $expression
     */
    protected function handleExpressionRight(Expression $expression)
    {
        Evaluator::getInstance()->pushConditionArray(1, '$ = a($)');

        $this->left = trim($expression->getRight());

        $this->marked = true;
        $this->lineExpression->addToPotentialVars($this->left);
    }

    /**
     * @param Expression $expression
     */
    protected function handleDelegate(Expression $expression)
    {
        Evaluator::getInstance()->pushConditionArray(2, '$ = $->b');

        $rightExpressionExploded = explode('->', $expression->getRight());
        $this->left = array_shift($rightExpressionExploded);
        $this->right = implode('->', $rightExpressionExploded);
        $this->leftIsAttribute = true;
        $assignmentVar = new AssignmentVar();
        $assignmentVar
            ->setVar($this->left)
            ->setOperator(AssignmentVar::OPERATOR_DELEGATE);
        ;
        $this->getLineExpression()->addToPotentialAssignments($assignmentVar);

        $newExpression = new Expression();
        $this->setChild($newExpression);  // build link backward
        $newExpression->linkParent($this);

        $newExpression->evaluate();
    }

    /**
     * @param Expression $expression
     */
    protected function handleFunction(Expression $expression)
    {
        Evaluator::getInstance()->pushConditionArray(3, '$ = $->b( , )');

        $rightExpressionExploded = explode(')', $expression->getRight());
        if (1 < count($rightExpressionExploded)) {
            // existing of delegate( , )->delegate()
        }
        $firstBracketExpression = [];
        $parameters = [];
        if (1 < $rightExpressionExploded[0]) {
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