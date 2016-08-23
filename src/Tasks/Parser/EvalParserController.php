<?php

namespace MyApp\src\Tasks\Parser;

use MyApp\src\Entities\Snippets\SnippetsRepository;
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
    $evalParser = new EvalParser();
    $evalParser
        ->init()
        ->setInputString($inputString)
    ;
    
    $evalParser->evalIt();

    $outputString = $evalParser->getOutputString();

    $snippetsGrouped = $this->fetchSnippetsGrouped();
    
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
      'snippetsGrouped' => $snippetsGrouped,
    ));
  }

  public function fetchSnippetsGrouped()
  {
    $snippetsRepository = new SnippetsRepository();
    $snippetsRepository
      ->init()
    ;
    $data = $snippetsRepository->getSnippetsData();
    
    $preparedData = [];
    foreach ($data as $row) {
      $evalGroupString = $row['eval_group_name'];
      if (empty($preparedData[$evalGroupString])) {
        $preparedData[$evalGroupString] = [];
      }
      $preparedData[$evalGroupString]['eval_template_name'] = $row['eval_template_name'];
      $preparedData[$evalGroupString]['eval_template_snippet'] = $row['eval_template_snippet'];
    }
    
    return $preparedData;
  }
}
