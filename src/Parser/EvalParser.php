<?php


namespace MyApp\src\Parser;


class EvalParser
{
  /**
   * @var string
   */
  private $inputString;

  /**
   * @var string
   */
  private $outputString;

  public function init()
  {
    $this->initAttributes();

    return $this;
  }

  private function initAttributes()
  {
    $this->inputString = '';
    $this->outputString = '';
  }

  public function evalIt()
  {
    ob_start();
    eval("$this->inputString");
    $this->outputString = ob_get_clean();
    
    
  }

  /**
   * @return string
   */
  public function getInputString()
  {
    return $this->inputString;
  }

  /**
   * @param string $inputString
   * @return EvalParser
   */
  public function setInputString($inputString)
  {
    $this->inputString = $inputString;

    return $this;
  }

  /**
   * @return string
   */
  public function getOutputString()
  {
    return $this->outputString;
  }

  /**
   * @param string $outputString
   * @return EvalParser
   */
  public function setOutputString($outputString)
  {
    $this->outputString = $outputString;

    return $this;
  }
}