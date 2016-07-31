<?php

namespace MyApp\src\Tasks\Tools;

use MyApp\src\Tasks\Tasks;

class ToolsController extends Tasks
{
  public function __construct()
  {
    parent::__construct();
  }

  public function overview()
  {  
    $template = 'Tools/' . strtolower(__FUNCTION__) . '/' . strtolower(__FUNCTION__);
    if ('post' == strtolower($_SERVER['REQUEST_METHOD'])) {
      $template .= '_rendered.twig';
    } else {
      $template .= '.twig';
    }
    
    echo $this->components->get('view')->render($template, array(
      'templateContext' => 'overview'
    ));
  }
}
