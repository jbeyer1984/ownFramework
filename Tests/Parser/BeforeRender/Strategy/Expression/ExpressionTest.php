<?php

define('VAR_WWW', '/var/www/ownFramework');
//define( 'VAR_WWW', 'D:\\Programme\\xampp\\htdocs\\ownFramework\\' );
require_once(VAR_WWW . '/src/bootstrap.php');
require_once(ROOT_PATH . '/vendor/autoload.php');

use MyApp\src\Evaluator\Evaluator;
use MyApp\src\Parser\BeforeRender\AssignmentRenderParser;
use MyApp\src\Parser\BeforeRender\Strategy\Expression\Expression;
use MyApp\src\Parser\BeforeRender\Strategy\Expression\LineExpression;

class ExpressionTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Expression
     */
    protected $expression;

    public function setup()
    {
        Evaluator::getInstance()->init();
        $this->expression = new Expression();
    }

    public function testEvaluate_Expression_variable()
    {
        $lineExpression = new LineExpression();
        $varExpression = new Expression();
        $varExpression
            ->setIdentifier('$test')
            ->setLeft('$test = $var')
            ->setLineExpression($lineExpression);;

        $this->expression->evaluate();

        $this->assertEquals($this->expression->getParent(), $varExpression);
        $this->assertEquals($this->expression->getLineExpression(), $lineExpression);
        $this->assertEquals(true, $this->expression->isAssignment());
        $this->assertEquals(
            [
                '$var'
            ], $this->expression->getLineExpression()->getPotentialVars()
        );
//    $this->assertTrue($this->expression->isMarked());
    }

    public function testEvaluate_Expression_variableDelegate()
    {
        $lineExpression = new LineExpression();
        $varDelegateExpression = new Expression();
        $varDelegateExpression
            ->setIdentifier('$test')
            ->setAssignment(true)
            ->setLeft('$testLeft')
            ->setRight('$testRight->foo->determine')
            ->setLineExpression($lineExpression);;

        $this->expression->evaluate();

        $this->assertEquals($lineExpression, $varDelegateExpression->getChild()->getLineExpression());
        $this->assertEquals('$testRight', $varDelegateExpression->getChild()->getLeft());
        $this->assertEquals('foo->determine', $varDelegateExpression->getChild()->getRight());

        $this->assertEquals($lineExpression, $varDelegateExpression->getChild()->getChild()->getLineExpression());
        $this->assertNotEquals(null, $varDelegateExpression->getChild()->getChild());
        $this->assertEquals('foo', $varDelegateExpression->getChild()->getChild()->getLeft());
        $this->assertEquals('determine', $varDelegateExpression->getChild()->getChild()->getRight());
//        $this->assertEquals(
//            [
//                '$testLeft',
//                'determine'
//            ], $this->expression->getLineExpression()->getPotentialVars()
//        );
    }

    public function testEvaluate_Expression_variableDelegateFunction()
    {
        $lineExpression = new LineExpression();
        $varDelegateExpression = new Expression();
        $varDelegateExpression
            ->setIdentifier('$test')
            ->setLeft('$testLeft')
            ->setRight('$testRight->foo->determine($varOne, $varTwo)')
            ->setLineExpression($lineExpression);

        $this->expression->evaluate();

        $this->assertEquals($lineExpression, $varDelegateExpression->getChild()->getLineExpression());
        $this->assertEquals('$testRight', $varDelegateExpression->getChild()->getLeft());
        $this->assertEquals('foo->determine($varOne, $varTwo)', $varDelegateExpression->getChild()->getRight());

        $this->assertEquals($lineExpression, $varDelegateExpression->getChild()->getChild()->getLineExpression());
        $this->assertNotEquals(null, $varDelegateExpression->getChild()->getChild());
        $this->assertEquals('foo', $varDelegateExpression->getChild()->getChild()->getLeft());
        $this->assertEquals('determine($varOne, $varTwo)', $varDelegateExpression->getChild()->getChild()->getRight());
        $this->assertEquals(['$varOne', '$varTwo'], $lineExpression->getPotentialVars());
    }

    public function testEvaluate_multipleLines_withClosestIdentifierState()
    {
        $txt = '
$test->attr = new Change();
$test->attr->attr2->getFunc();
$test->attr->getFunc();
$test->attr->getFoo();
$test = 3;
$test = 5;
';

        $assignmentParser = new AssignmentRenderParser();
        $assignmentParser->parseStrategyTemplates($txt);

        $evalPrint = Evaluator::getInstance()->getPrintedConditionArray();
        $dump = print_r($evalPrint, true);
        error_log(PHP_EOL . '-$- in ' . basename(__FILE__) . ':' . __LINE__ . ' in ' . __METHOD__ . PHP_EOL . '*** $evalPrint ***' . PHP_EOL . " = " . $dump . PHP_EOL);
        
    }
    
    public function testInGeneral()
    {
        $assignmentParser = new AssignmentRenderParser();
        $assignmentParser->parseStrategyTemplates('$test->attr->getFunc()');

        $evalPrint = Evaluator::getInstance()->getPrintedConditionArray();
    }
}