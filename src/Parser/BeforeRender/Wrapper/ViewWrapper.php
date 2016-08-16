<?php


namespace MyApp\src\Parser\BeforeRender\Wrapper;

use MyApp\src\Parser\BeforeRender\Wrapper\Text\Div\SpanClassExtender;

class ViewWrapper extends AbstractWrapper
{

  /**
   * @var SpanClassExtender
   */
  private $spanClassExtender;

  /**
   * @var array[string]
   */
  private $cssClasses;

  /**
   * @return $this
   */
  public function init()
  {
    $this->spanClassExtender = new SpanClassExtender();
    
    return $this;
  }

  /**
   * @param $cssClasses
   * @return ViewWrapper
   */
  public static function initialized($cssClasses)
  {
    $self = new self();
    $self
      ->init()
      ->setCssClasses($cssClasses)
    ;
    
    return $self;
  }
  
  function writeIdentifier()
  {
    $this->spanClassExtender->setIdentifier($this->getIdentifier());
    $this->spanClassExtender->preWrap($this->cssClasses);
    $this->spanClassExtender->postWrap();
    
    $this->setManipulatedString($this->spanClassExtender->getManipulatedString());
  }

  /**
   * @return array
   */
  public function getCssClasses()
  {
    return $this->cssClasses;
  }

  /**
   * @param array $cssClasses
   * @return ViewWrapper
   */
  public function setCssClasses($cssClasses)
  {
    $this->cssClasses = $cssClasses;
    return $this;
  }
}