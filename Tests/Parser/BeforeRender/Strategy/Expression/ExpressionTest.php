<?php

define('VAR_WWW', '/var/www/ownFramework');
//define( 'VAR_WWW', 'D:\\Programme\\xampp\\htdocs\\ownFramework\\' );
require_once(VAR_WWW . '/src/bootstrap.php');
require_once(ROOT_PATH . '/vendor/autoload.php');

use MyApp\src\Evaluator\Evaluator;
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
            ->init()
            ->setIdentifier('$test')
            ->setLeft('$test = $var')
            ->setLineExpression($lineExpression);;

        $this->expression->evaluate($varExpression);

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
            ->init()
            ->setIdentifier('$test')
            ->setAssignment(true)
            ->setLeft('$testLeft')
            ->setRight('$testRight->foo->determine')
            ->setLineExpression($lineExpression);;

        $this->expression->evaluate($varDelegateExpression);

//        $createdHmlPage = Evaluator::getInstance()->getPrintedConditionArray();
//        $dump = print_r($createdHmlPage, true);
//        error_log(PHP_EOL . '-$- in ' . basename(__FILE__) . ':' . __LINE__ . ' in ' . __METHOD__ . PHP_EOL . '*** $createdHmlPage ***' . PHP_EOL . " = " . $dump . PHP_EOL);

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
            ->init()
            ->setIdentifier('$test')
            ->setLeft('$testLeft')
            ->setRight('$testRight->foo->determine($varOne, $varTwo)')
            ->setLineExpression($lineExpression);
        
        $this->expression->evaluate($varDelegateExpression);

        $this->assertEquals($lineExpression, $varDelegateExpression->getChild()->getLineExpression());
        $this->assertEquals('$testRight', $varDelegateExpression->getChild()->getLeft());
        $this->assertEquals('foo->determine($varOne, $varTwo)', $varDelegateExpression->getChild()->getRight());

        $this->assertEquals($lineExpression, $varDelegateExpression->getChild()->getChild()->getLineExpression());
        $this->assertNotEquals(null, $varDelegateExpression->getChild()->getChild());
        $this->assertEquals('foo', $varDelegateExpression->getChild()->getChild()->getLeft());
        $this->assertEquals('determine($varOne, $varTwo)', $varDelegateExpression->getChild()->getChild()->getRight());
        $this->assertEquals(['$varOne', '$varTwo'], $lineExpression->getPotentialVars());
    }
}