<?php
define('VAR_WWW', '/var/www/ownFramework');
//define( 'VAR_WWW', 'D:\\Programme\\xampp\\htdocs\\ownFramework\\' );
require_once(VAR_WWW . '/src/bootstrap.php');
require_once(ROOT_PATH . '/vendor/autoload.php');

use MyApp\src\Parser\BeforeRenderParser;

class BeforeRenderParserTest extends PHPUnit_Framework_TestCase
{

  /**
   * @var BeforeRenderParserMock
   */
  private $beforeRenderParser;

  public function setUp()
  {
    $this->beforeRenderParser = BeforeRenderParserMock::initialized();
    $this->beforeRenderParser->init();
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

    $allLines = $this->beforeRenderParser->explodeText($text);
//    $this->beforeRenderParser->setAllLines($allLines);
    
    $this->beforeRenderParser->parse($allLines);
    
    $text = implode('\n', $this->beforeRenderParser->getAllLines());
    
    $this->assertNotFalse(strpos($text, 'bold'));
    $this->assertNotFalse(strpos($text, 'click_able'));
    $this->assertNotFalse(strpos($text, 'view_highlight'));
  }
}

class BeforeRenderParserMock extends BeforeRenderParser
{

}