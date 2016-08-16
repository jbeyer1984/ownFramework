<?php


namespace MyApp\src\Parser\BeforeRender;

use MyApp\src\Parser\BeforeRender\Strategy\ViewParserStrategy;
use MyApp\src\Parser\BeforeRender\Template\StrategyParserTemplate;

class BeforeRenderParser
{

  public function __construct()
  {
  }

  /**
   * @param string $text
   * @return string
   */
  public function parseStrategyTemplates($text)
  { 
    $strategyParserTemplate = new StrategyParserTemplate();
    
    $viewParserStrategy = ViewParserStrategy::initialized();
    $allLines = $viewParserStrategy->explodeText($text);
    
    $viewParserStrategy->setAllLines($allLines);
    
    $strategyParserTemplate->parse($viewParserStrategy);
    
    return $viewParserStrategy->getOutputText();
  }
}