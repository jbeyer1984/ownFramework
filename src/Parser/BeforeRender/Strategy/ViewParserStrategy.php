<?php


namespace MyApp\src\Parser\BeforeRender\Strategy;

use MyApp\src\Parser\BeforeRender\Strategy\IFace\ParserStrategyInterface;
use MyApp\src\Parser\BeforeRender\Wrapper\AbstractWrapper;
use MyApp\src\Parser\BeforeRender\Wrapper\IFace\WrapperInterface;
use MyApp\src\Parser\BeforeRender\Wrapper\Text\VariableText;
use MyApp\src\Parser\BeforeRender\Wrapper\ViewWrapper;

class ViewParserStrategy extends AbstractParserStrategy
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
  public function init()
  {
    parent::init();
    
    $this->viewArray = [];
    $this->lineReferenceView = [];
    $this->renderLinesArray = [];
    
    return $this;
  }

  /**
   * @param array[string] $allLines
   */
  public function buildParserArrays($allLines)
  {
    $viewArray = $this->getViewArray(); // is like [ varOne => [ [0] => 4 ]] 
    $lineReferenceView = $this->getLineReferenceView(); // is like [ 4 => varOne ]
    
    $renderCount = 0;

    foreach ($allLines as $lineNum => $line) {
//            preg_match('/.*$this->view->(\w\+)\s*=/', $line, $viewVar);
      preg_match('/.*\$this->view->(\w+)\s*=/', $line, $viewVarMatch);
      if (!empty($viewVarMatch)) {
        array_shift($viewVarMatch);
        $viewVar = $viewVarMatch[0]; // get rid of first whole match

        $lineReferenceView[$lineNum] = $viewVar; // @todo think about double existence
        if (empty($viewArray[$viewVar])) {
          $viewArray[$viewVar] = [];
        }
        $viewArray[$viewVar][] = $lineNum;
      }
      if (false !== strpos($line, 'render()')) {
        $renderCount++;
      } else {
        if (!isset($this->renderLinesArray[$renderCount])) {
          $this->renderLinesArray[$renderCount] = [];
        }
        $this->renderLinesArray[$renderCount][] = $lineNum;
      }
    }

    $this->setViewArray($viewArray);
    $this->setLineReferenceView($lineReferenceView);
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
    $allLines[$lineNum] = str_replace(
      '$this->view->' . $varName,
      '$this->view->' . $variableText->getManipulatedString(),
      $allLines[$lineNum]
    );

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