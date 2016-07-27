<?php

namespace MyApp\src\Tasks\DocParser;

use MyApp\src\Parser\DocParser;
use MyApp\src\Tasks\Tasks;

class DocParserController extends Tasks
{
  public function __construct()
  {
    parent::__construct();
  }

  public function start($inputString = '')
  {
    $docParser = new DocParser();
    $docParser->init();
    $docParser->setText($inputString);
    $docParser->prepareLinesForConvert();
    $lines = $docParser->getLines();
    $numberTagStrings = $docParser->getNumberTagStrings();

    $docParser->convertNumberTagStringsToNumbers($numberTagStrings);
    $numberStrings = $docParser->getNumberStrings();

    $docParser->replaceConvertedLinesWithUsualText($lines, $numberTagStrings, $numberStrings);
    $outputString = $docParser->builtOutputText();
    
    $template = 'DocParser/' . strtolower(__FUNCTION__) . '/' . strtolower(__FUNCTION__);
    if ('post' == strtolower($_SERVER['REQUEST_METHOD'])) {
      $template .= '_rendered.twig';
    } else {
      $template .= '.twig';
    }
    
    echo $this->components->get('view')->render($template, array(
      'templateContext' => 'start',
      'outputString' => $outputString,
    ));
  }

  public function entry()
  {

  }

  public function prepare()
  {

  }

  public function render()
  {

  }
}
