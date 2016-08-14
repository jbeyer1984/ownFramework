<?php


namespace MyApp\src\Tasks\Parser;


use MyApp\src\Parser\BeforeRenderParser;
use MyApp\src\Tasks\Tasks;

class BeforeRenderParserController extends Tasks
{
  public function __construct()
  {
    parent::__construct();
  }
  
  public function start()
  {
    
    $beforeRenderParser = BeforeRenderParser::initialized();
    $text = '
$varOne = 1;
$varTwo = 2;

$this->view->varOne = $varTwo;
$this->view->varTwo = $varOne;
$this->render();
';

    $allLines = $beforeRenderParser->explodeText($text);
    $beforeRenderParser->parse($allLines);
    $outputString = nl2br($beforeRenderParser->getOutputText());
    
    $dump = print_r($outputString, true);
    error_log(PHP_EOL . '-$- in ' . basename(__FILE__) . ':' . __LINE__ . ' in ' . __METHOD__ . PHP_EOL . '*** $outputString ***' . PHP_EOL . " = " . $dump . PHP_EOL);
    
    
    $template = 'BeforeRenderParser/' . strtolower(__FUNCTION__) . '/' . strtolower(__FUNCTION__);
    if ('post' == strtolower($_SERVER['REQUEST_METHOD'])) {
      $template .= '_output_rendered.twig';
    } else {
      $template .= '.twig';
    }

    echo $this->components->get('view')->render($template, array(
      'templateContext' => 'start',
//      'inputString' => $inputString,
      'outputString' => $outputString,
//      'snippetsGrouped' => $snippetsGrouped,
    ));
  }
}