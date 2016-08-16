<?php
define('VAR_WWW', '/var/www/ownFramework');
//define( 'VAR_WWW', 'D:\\Programme\\xampp\\htdocs\\ownFramework\\' );
require_once(VAR_WWW . '/src/bootstrap.php');
require_once(ROOT_PATH . '/vendor/autoload.php');

use MyApp\src\Parser\BeforeRender\BeforeRenderParser;
use MyApp\src\Parser\BeforeRender\Strategy\ViewParserStrategy;
use MyApp\src\Parser\BeforeRender\Template\StrategyParserTemplate;

class BeforeRenderParserTest extends PHPUnit_Framework_TestCase
{

  /**
   * @var BeforeRenderParserMock
   */
  private $beforeRenderParser;

  public function setUp()
  {
    $this->beforeRenderParser = new BeforeRenderParserMock();
//    $this->beforeRenderParser->init();
  }

  public function testViewVarWrapping()
  {
    $text = '
$varOne = 1;
$varTwo = 2;

$this->view->varOne = $varTwo;
$this->view->varTwo = $varOne;
$this->render();
';

//    $allLines = $this->beforeRenderParser->explodeText($text);
//    $this->beforeRenderParser->setAllLines($allLines);
    
    $manipulatedText = $this->beforeRenderParser->parseStrategyTemplates($text);
    
    $this->assertNotFalse(strpos($manipulatedText, 'bold'));
    $this->assertNotFalse(strpos($manipulatedText, 'click_able'));
    $this->assertNotFalse(strpos($manipulatedText, 'view_highlight'));
  }
  
  public function testViewOverwritten()
  {
    $text = '
$this->view->whatFirst = 0;

$this->render();

$this->view->whatFirst = 1;
$this->view->whatSecond = 1;

$this->render();

$this->view->whatSecond = 2;
$this->view->whatThird = 2;

$this->render();
';

    $strategyParserTemplate = new StrategyParserTemplate();

    $viewParserStrategy = ViewParserStrategy::initialized();
    $allLines = $viewParserStrategy->explodeText($text);

    $viewParserStrategy->setAllLines($allLines);
    
    $strategyParserTemplate
      ->setStrategy($viewParserStrategy)
    ;
    
    $strategyParserTemplate->parse();
    
    $viewArray = $strategyParserTemplate->getStrategy()->getViewArray();
    
    $text = $viewParserStrategy->getOutputText();
    
    $dump = print_r($text, true);
    error_log(PHP_EOL . '-$- in ' . basename(__FILE__) . ':' . __LINE__ . ' in ' . __METHOD__ . PHP_EOL . '*** $text ***' . PHP_EOL . " = " . $dump . PHP_EOL);
    

  }
}

class BeforeRenderParserMock extends BeforeRenderParser
{

}