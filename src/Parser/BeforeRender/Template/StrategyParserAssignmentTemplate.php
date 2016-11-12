<?php

namespace MyApp\src\Parser\BeforeRender\Template;


use MyApp\src\Parser\BeforeRender\Strategy\AbstractParserStrategy;
use MyApp\src\Parser\BeforeRender\Strategy\ViewAssignmentStrategy;
use MyApp\src\Parser\BeforeRender\Strategy\ViewParserStrategy;

class StrategyParserAssignmentTemplate
{

  /**
   * @var ViewParserStrategy
   */
  private $strategy;
  
  public function __construct()
  {
  }

  /**
   * @param ViewAssignmentStrategy $strategy
   */
  public function parse($strategy = null)
  {
    if (!empty($this->strategy)) {
      $strategy = $this->strategy;
    }
    
    $strategy->buildParserArrays($strategy->getAllLines());

//    // search backward and grep $varOne that has been assigned to $this->view->varOne
//    $viewArray = $strategy->getViewArray();
    
  }

  /**
   * @return AbstractParserStrategy
   */
  public function getStrategy()
  {
    return $this->strategy;
  }

  /**
   * @param AbstractParserStrategy $strategy
   * @return StrategyParserAssignmentTemplate
   */
  public function setStrategy($strategy)
  {
    $this->strategy = $strategy;
      
    return $this;
  }
}