<?php


namespace MyApp\src\Tasks\Parser;


use MyApp\src\Parser\BeforeRender\BeforeRenderParser;
use MyApp\src\Tasks\Tasks;

class BeforeRenderParserController extends Tasks
{
  public function __construct()
  {
    parent::__construct();
  }
  
  public function start()
  {
    
    $beforeRenderParser = new BeforeRenderParser();
    $text = '
$this->view->whatFirst = 0;

if (true) {
  $var0 = 0;
  $this->render();
}

$this->view->whatFirst = 1;
$this->view->whatFirst = 1;
$this->view->whatSecond = 1;

$this->render();

$this->view->whatFirst = 2;
$this->view->whatSecond = 2;
$this->view->whatThird = 2;

$this->render();
';

    $outputText = $beforeRenderParser->parseStrategyTemplates($text);
    $outputString = $outputText;
    
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