<?php


namespace MyApp\src\Parser\BeforeRender\Strategy;


use MyApp\src\Parser\BeforeRender\Strategy\IFace\ParserStrategyInterface;
use MyApp\src\Parser\BeforeRender\Wrapper\AbstractWrapper;
use MyApp\src\Parser\BeforeRender\Wrapper\IFace\WrapperInterface;

abstract class AbstractParserStrategy  implements ParserStrategyInterface
{

  /**
   * @var string
   */
  private $inputText;

  /**
   * @var array[string]
   */
  private $allLines;

  /**
   * @var string
   */
  private $outputText;


  protected function __construct()
  {
  }

  public function init()
  {
    $this->inputText = '';
    $this->allLines = [];
  }

  /**
   * @param array[string] $allLines
   * @return void
   */
  public abstract function buildParserArrays($allLines);

  /**
   * @param string $varName
   * @param int $lineNum
   * @param AbstractWrapper $wrapper
   * @return
   */
  public abstract function wrapVar($varName, $lineNum, AbstractWrapper $wrapper);
  
  /**
   * @param string $text
   * @return array
   */
  public function explodeText($text)
  {
    $exploded = explode("\n", $text);
    $allLines = $exploded;

    return $allLines;
  }

  /**
   * @return array
   */
  public function getAllLines()
  {
    return $this->allLines;
  }

  /**
   * @param array $allLines
   * @return AbstractParserStrategy
   */
  public function setAllLines($allLines)
  {
    $this->allLines = $allLines;
    
    return $this;
  }

  /**
   * @return string
   */
  public function getOutputText()
  {
    return $this->outputText;
  }

  /**
   * @param string $outputText
   * @return AbstractParserStrategy
   */
  public function setOutputText($outputText)
  {
    $this->outputText = $outputText;
    
    return $this;
  }

  /**
   * @return string
   */
  public function getInputText()
  {
    return $this->inputText;
  }

  /**
   * @param string $inputText
   * @return AbstractParserStrategy
   */
  public function setInputText($inputText)
  {
    $this->inputText = $inputText;
    return $this;
  }
}