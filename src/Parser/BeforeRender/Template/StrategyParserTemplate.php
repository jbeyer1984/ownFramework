<?php

namespace MyApp\src\Parser\BeforeRender\Template;


use MyApp\src\Parser\BeforeRender\Strategy\AbstractParserStrategy;
use MyApp\src\Parser\BeforeRender\Strategy\ViewParserStrategy;
use MyApp\src\Parser\BeforeRender\Wrapper\ViewWrapper;

class StrategyParserTemplate
{

  /**
   * @var ViewParserStrategy
   */
  private $strategy;
  
  public function __construct()
  {
  }

  /**
   * @param ViewParserStrategy $strategy
   */
  public function parse($strategy = null)
  {
    if (!empty($this->strategy)) {
      $strategy = $this->strategy;
    }
    
    $strategy->buildParserArrays($strategy->getAllLines());

    // search backward and grep $varOne that has been assigned to $this->view->varOne
    $viewArray = $strategy->getViewArray();
    
    $lineReferenceView = $strategy->getLineReferenceView();

    $varNameAlreadyHandled = [];
    // go backward through lines with var's
    for (end($lineReferenceView); key($lineReferenceView) !== null; prev($lineReferenceView)) {
      $lineNum = key($lineReferenceView);
      $varName = current($lineReferenceView);
      
      if (in_array($varName, $varNameAlreadyHandled)) {
        continue;
      }
      $varNameAlreadyHandled[] = $varName;

      // look in view Array whether there is one occurrence and then decide
      if (isset($viewArray[$varName])) {
        if (1 == count($viewArray[$varName])) { // view variable is only used one time
          // @todo wrap with class and make bold
          $viewWrapper = ViewWrapper::initialized(['click_able', 'one_time', 'bold', 'view_highlight']);
          $strategy->wrapVar($varName, $lineNum, $viewWrapper);

//          $imploded = implode("", $strategy->getAllLines());
//          $strategy->setOutputText($imploded);
        }

        $oneAssignmentInView = true;
        // if multiple lines exists search last render and watch from there on assignment
        if (1 < count($viewArray[$varName])) {
          $oneAssignmentInView = false;
          $tempViewArray = $viewArray[$varName];

          $occurrences = 0;
          $brightness = 0;
          foreach ($tempViewArray as $tempLineNum) { // go from begin
            // if all good is one, store in render overview array
            // @todo jbeyer render overview array
            
            foreach ($strategy->getRenderLinesArray() as $linesOfRenderSection) {
              $viewVarFoundInRenderSection = false;
              $brightnessCssClass = 'bright_10';
              $viewWrapper = ViewWrapper::initialized(['click_able', $brightnessCssClass, 'bold', 'view_highlight']);
              if (in_array($tempLineNum, $linesOfRenderSection)) {
                // brightness up
                $viewVarFoundInRenderSection = true;
                $brightness += 25;
                $brightnessCssClass = 'bright_' . $brightness;
                $occurrences++;
              }
              
              if (1 < $occurrences) {
                if (1 < count(array_intersect($tempViewArray, $linesOfRenderSection))) { // double assignment
                  $viewWrapper = ViewWrapper::initialized([
                    'click_able', $brightnessCssClass, 'underline', 'bold', 'view_highlight', 'double_assignment'
                  ]);
                } else {
                  $viewWrapper = ViewWrapper::initialized([
                    'click_able', $brightnessCssClass, 'underline', 'bold', 'view_highlight'
                  ]);
                }
              }
              
              if ($viewVarFoundInRenderSection) {
//                $viewWrapper = ViewWrapper::initialized(['click_able', $brightnessCssClass,'underline', 'bold', 'view_highlight']);
                $strategy->wrapVar($varName, $tempLineNum, $viewWrapper);  
              }
            }
          }
          
          // @todo try to solve situation
        }

        $imploded = implode(PHP_EOL, $strategy->getAllLines());
        $strategy->setOutputText($imploded);

        if (!$oneAssignmentInView) {
          // @todo mark as overwrite to nth render()
        }


        if ($oneAssignmentInView) {
          // build var backward tree if no double exists

          // @todo create backward tree
        }
      }
    }

    
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
   * @return StrategyParserTemplate
   */
  public function setStrategy($strategy)
  {
    $this->strategy = $strategy;
    return $this;
  }
}