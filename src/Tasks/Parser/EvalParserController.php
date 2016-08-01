<?php

namespace MyApp\src\Tasks\Parser;

use MyApp\src\Parser\EvalParser;
use MyApp\src\Tasks\Tasks;

class EvalParserController extends Tasks
{
  public function __construct()
  {
    parent::__construct();
  }

  public function start($inputString = '')
  {
    $dump = print_r("herer", true);
    error_log(PHP_EOL . '-$- in ' . basename(__FILE__) . ':' . __LINE__ . ' in ' . __METHOD__ . PHP_EOL . '*** "herer" ***' . PHP_EOL . " = " . $dump . PHP_EOL);
    
    $evalParser = new EvalParser();
    $evalParser
        ->init()
        ->setInputString($inputString)
    ;
    
    $evalParser->evalIt();
    
    $outputString = $evalParser->getOutputString();
    
    $template = 'EvalParser/' . strtolower(__FUNCTION__) . '/' . strtolower(__FUNCTION__);
    if ('post' == strtolower($_SERVER['REQUEST_METHOD'])) {
      $template .= '_output_rendered.twig';
    } else {
      $template .= '.twig';
    }
    
    echo $this->components->get('view')->render($template, array(
      'templateContext' => 'start',
      'inputString' => $inputString,
      'outputString' => $outputString,
    ));
  }
}
