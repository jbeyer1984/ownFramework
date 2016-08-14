<?php


namespace MyApp\src\Parser\Wrapper;

use MyApp\src\Parser\Text\Div\SpanClassExtender;

class ViewWrapper extends AbstractWrapper
{

  /**
   * @var SpanClassExtender
   */
  private $spanClassExtender;

  /**
   * @return $this
   */
  public function init()
  {
    $this->spanClassExtender = new SpanClassExtender();
    
    return $this;
  }

  /**
   * @return ViewWrapper
   */
  public static function initialized()
  {
    $self = new self();
    $self->init();
    
    return $self;
  }
  
  function writeIdentifier()
  {
    $this->spanClassExtender->setIdentifier($this->getIdentifier());
    $this->spanClassExtender->preWrap(['click_able', 'bold', 'view_highlight']);
    $this->spanClassExtender->postWrap();
    
    $this->setManipulatedString($this->spanClassExtender->getManipulatedString());
  }

  
  
  
}