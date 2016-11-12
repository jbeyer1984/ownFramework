<?php


namespace MyApp\src\Parser\BeforeRender\Strategy;

use MyApp\src\Parser\BeforeRender\Strategy\Expression\Expression;
use MyApp\src\Parser\BeforeRender\Strategy\Expression\LineExpression;
use MyApp\src\Parser\BeforeRender\Wrapper\AbstractWrapper;
use MyApp\src\Parser\BeforeRender\Wrapper\Text\VariableText;

class ViewAssignmentStrategy extends AbstractParserStrategy
{

    /**
     * @var array
     */
    private $viewArray;

    /**
     * @var array
     */
    private $lineReferenceView;

    /**
     * @var array
     */
    private $renderLinesArray;

    public function __construct()
    {
    }

    /**
     * @return AbstractParserStrategy
     */
    public static function initialized()
    {
        $self = new self();
        $self->init();

        return $self;
    }

    /**
     * @return $this
     */
    protected function init()
    {
        parent::init();

        $this->viewArray = [];
        $this->lineReferenceView = [];
        $this->renderLinesArray = [];

        return $this;
    }

    /**
     * @param array [string] $allLines
     */
    public function buildParserArrays($allLines)
    {
        $viewArray = $this->getViewArray(); // is like [ varOne => [ [0] => 4 ]] 
        $lineReferenceView = $this->getLineReferenceView(); // is like [ 4 => varOne ]

        $renderCount = 0;


        foreach ($allLines as $lineNum => $line) {
            // create expression tree for each line
            $lineExpression = new LineExpression();
            $lineExpression->setLine($line);
            $expression = new Expression();
            $expression
                ->init()
//            ->setIdentifier('$test')
//            ->setAssignment(true)
//            ->setLeft('$testLeft')
                ->setRight($line)
//            ->setRight('$testRight->foo->determine')
                ->setLineExpression($lineExpression);;

            $expressionTree = new Expression();
            $expressionTree->evaluate($expression);

        }

//    $this->setViewArray($viewArray);
//    $this->setLineReferenceView($lineReferenceView);
    }

    /**
     * @param string $varName
     * @param int $lineNum
     * @param AbstractWrapper $wrapper
     */
    public function wrapVar($varName, $lineNum, AbstractWrapper $wrapper)
    {
        $variableText = VariableText::initialized();
        $variableText->setIdentifier($varName);

        $variableText->addWrapper($wrapper);
        $variableText->apply();

        $allLines = $this->getAllLines();
//    $allLines[$lineNum] = str_replace(
//      '$this->view->' . $varName,
//      '$this->view->' . $variableText->getManipulatedString(),
//      $allLines[$lineNum]
//    );

        $this->setAllLines($allLines);
    }

    /**
     * @return array
     */
    public function getViewArray()
    {
        return $this->viewArray;
    }

    /**
     * @param array $viewArray
     * @return AbstractParserStrategy
     */
    public function setViewArray($viewArray)
    {
        $this->viewArray = $viewArray;

        return $this;
    }

    /**
     * @return array
     */
    public function getLineReferenceView()
    {
        return $this->lineReferenceView;
    }

    /**
     * @param array $lineReferenceView
     * @return AbstractParserStrategy
     */
    public function setLineReferenceView($lineReferenceView)
    {
        $this->lineReferenceView = $lineReferenceView;

        return $this;
    }

    /**
     * @return array
     */
    public function getRenderLinesArray()
    {
        return $this->renderLinesArray;
    }

    /**
     * @param array $renderLinesArray
     * @return ViewParserStrategy
     */
    public function setRenderLinesArray($renderLinesArray)
    {
        $this->renderLinesArray = $renderLinesArray;

        return $this;
    }
}