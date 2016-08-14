<?php

namespace MyApp\src\Parser\Text;

use Exception;
use MyApp\src\Parser\Text\IFace\ManipulatedStringInterface;
use MyApp\src\Parser\Wrapper\AbstractWrapper;
use MyApp\src\Parser\Wrapper\IFace\IdentifierInterface;
use MyApp\src\Parser\Wrapper\IFace\WrapperInterface;

class VariableText implements IdentifierInterface, WrapperInterface, ManipulatedStringInterface
{

  /**
   * @var string
   */
  private $identifier;

  /**
   * @var array[Wrapper]
   */
  private $wrappers;

  /**
   * @var array[string];
   */
  private $wrapperOrder;

  /**
   * @var string
   */
  private $manipulatedString;
  
  public function init()
  {
    $this->wrappers = [];
    $this->wrapperOrder = [];
  }

  /**
   * @return VariableText
   */
  public static function initialized()
  {
    $self = new self();
    $self->init();
    
    return $self;
  }

  /**
   * @param AbstractWrapper $wrapper
   * @param string $str
   * @throws Exception
   */
  public function addWrapper($wrapper, $str)
  {
    if (empty($str)) {
      throw new Exception('wrapper should have an identifier ($str)');
    }
    
    if (!empty($str)) {
//      $wrapper->setIdentifier($this->identifier);
      $this->wrappers[$str] = $wrapper;
      $this->wrapperOrder[] = $str;
    }
  }

  public function deleteWrapper($str)
  {
    if (empty($str)) {
      throw new Exception('need identifier ($str) to delete wrapper');
    }
  }
  
  public function apply()
  {
    $manipulatedString = $this->identifier;
    foreach ($this->wrapperOrder as $str) {
      $wrapper = $this->wrappers[$str];
      if ($wrapper instanceof AbstractWrapper) {
        $wrapper->setIdentifier($manipulatedString);
        $wrapper->writeIdentifier();
        $manipulatedString = $wrapper->getManipulatedString();
      }
//      if ($wrapper instanceof BoldWrapper) {
//        $wrapper->
//      }
    }
    
//    $dump = print_r($manipulatedString, true);
//    error_log(PHP_EOL . '-$- in ' . basename(__FILE__) . ':' . __LINE__ . ' in ' . __METHOD__ . PHP_EOL . '*** $manipulatedString ***' . PHP_EOL . " = " . $dump . PHP_EOL);
    
    $this->manipulatedString = $manipulatedString;
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
   * @return VariableText
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
   * @return VariableText
   */
  public function setManipulatedString($manipulatedString)
  {
    $this->manipulatedString = $manipulatedString;
    return $this;
  }
}