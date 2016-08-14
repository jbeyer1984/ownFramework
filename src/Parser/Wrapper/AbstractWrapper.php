<?php

namespace MyApp\src\Parser\Wrapper;

use MyApp\src\Parser\Text\IFace\ManipulatedStringInterface;
use MyApp\src\Parser\Wrapper\IFace\IdentifierInterface;

abstract class AbstractWrapper implements IdentifierInterface, ManipulatedStringInterface
{

  /**
   * @var string
   */
  private $identifier;

  /**
   * @var string
   */
  private $manipulatedString;

  abstract public function writeIdentifier();

  /**
   * @return string
   */
  public function getIdentifier()
  {
    return $this->identifier;
  }

  /**
   * @param string $identifier
   * @return AbstractWrapper
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
   * @return $this
   */
  public function setManipulatedString($manipulatedString)
  {
    $this->manipulatedString = $manipulatedString;
    
    return $this;
  }
}