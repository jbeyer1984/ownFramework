<?php


namespace MyApp\src\Parser;


use MyApp\src\Parser\Text\VariableText;
use MyApp\src\Parser\Wrapper\ViewWrapper;

class BeforeRenderParser
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
   * @var array
   */
  private $viewArray;

  /**
   * @var array
   */
  private $lineReferenceView;

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
    $this->viewArray = [];
    $this->lineReferenceView = [];
    $this->allLines = [];
  }

  /**
   * @return BeforeRenderParser
   */
  public static function initialized()
  {
    $self = new self();
    $self->init();

    return $self;
  }

  /**
   * @param array[string] $allLines
   */
  public function parse($allLines)
  {
    $this->allLines = $allLines;

    $this->buildParserArrays($this->allLines);

    // search backward and grep $varOne that has been assigned to $this->view->varOne
    $viewArray = $this->viewArray;
    $lineReferenceView = $this->lineReferenceView;

    // go backward through lines with var's
    for (end($lineReferenceView); key($lineReferenceView) !== null; prev($lineReferenceView)) {
      $lineNum = key($lineReferenceView);
      $varName = current($lineReferenceView);
      $dump = print_r($varName, true);
      error_log(PHP_EOL . '-$- in ' . basename(__FILE__) . ':' . __LINE__ . ' in ' . __METHOD__ . PHP_EOL . '*** $currentElement ***' . PHP_EOL . " = " . $dump . PHP_EOL);

      // look in view Array whether there is one occurrence and then decide
      if (isset($viewArray[$varName])) {
        // @todo wrap with class and make bold
        $this->wrapViewVar($varName, $lineNum);
        
        $this->outputText = implode("", $this->allLines);
        

        $oneAssignmentPerRenderer = true;
        // if multiple lines exists search last render and watch from there on assignment
        if (1 < count($viewArray[$varName])) {
          $oneAssignmentPerRenderer = false;
          // @todo try to solve situation
        }

        if (!$oneAssignmentPerRenderer) {
          // @todo mark as overwrite to nth render()
        }


        if ($oneAssignmentPerRenderer) {
          // build var backward tree if no double exists

          // @todo create backward tree
        }
      }
    }


  }

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
   * @param $allLines
   * @internal param $viewVarMatch
   */
  protected function buildParserArrays($allLines)
  {
    $viewArray = $this->viewArray; // is like [ varOne => [ [0] => 4 ]] 
    $lineReferenceView = $this->lineReferenceView; // is like [ 4 => varOne ] 

    $renderFuncLineNum = -1;

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
        $renderFuncLineNum = $lineNum;
      }
    }

    $this->viewArray = $viewArray;
    $this->lineReferenceView = $lineReferenceView;
  }

  /**
   * @param $varName
   * @param $lineNum
   */
  protected function wrapViewVar($varName, $lineNum)
  {
    $variableText = VariableText::initialized();
    $variableText->setIdentifier($varName);


    $viewWrapper = ViewWrapper::initialized();
    $variableText->addWrapper($viewWrapper, 'boldWrapper');
    $variableText->apply();

    $this->allLines[$lineNum] = str_replace(
      '$this->view->' . $varName,
      '$this->view->' . $variableText->getManipulatedString(),
      $this->allLines[$lineNum]
    );
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
   * @return BeforeRenderParser
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
   * @return BeforeRenderParser
   */
  public function setOutputText($outputText)
  {
    $this->outputText = $outputText;
    return $this;
  }
}