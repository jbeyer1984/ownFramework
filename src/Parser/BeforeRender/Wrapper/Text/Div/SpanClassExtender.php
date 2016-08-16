<?php

namespace MyApp\src\Parser\BeforeRender\Wrapper\Text\Div;

use MyApp\src\Parser\BeforeRender\Wrapper\Text\IFace\PrePostWrapInterface;
use MyApp\src\Parser\BeforeRender\Wrapper\IFace\IdentifierInterface;

class SpanClassExtender implements IdentifierInterface, PrePostWrapInterface
{

  /**
   * @var string
   */
  private $identifier;

  /**
   * @var string
   */
  private $manipulatedString;

  public function init()
  {
  }

  /**
   * @param array[string] $cssClassArray
   */
  public function preWrap($cssClassArray)
  {
    $cssClasses = implode(' ', $cssClassArray);
    $spanStr = <<< TXT
<span class="{$cssClasses}" identifier"{$this->identifier}">
TXT;
    
    $this->manipulatedString .= $spanStr;
    $this->manipulatedString .= $this->identifier;
  }

  public function postWrap()
  {
    $spanCloseStr = <<< TXT
</span>
TXT;
    
    $this->manipulatedString .= $spanCloseStr;
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
   * @return SpanClassExtender
   */
  public function setIdentifier($identifier)
  {
    $this->identifier = $identifier;
    return $this;
  }

  /**
   * @return string
   */
  public function getManipulatedString()
  {
    return $this->manipulatedString;
  }

  /**
   * @param string $manipulatedString
   * @return SpanClassExtender
   */
  public function setManipulatedString($manipulatedString)
  {
    $this->manipulatedString = $manipulatedString;
    return $this;
  }
}