<?php

namespace MyApp\src\Tasks;

use MyApp\src\Components\Components;
use MyApp\src\Utility\Logger;

class Task1 extends Tasks
{
  public function __construct()
  {
    parent::__construct();
    $dump = "hallo";
    $this->components->get('logger')->log('dump', $dump);
    $this->init();
  }
  
  public function init()
  {
    $this->components->get('vars')->set('tmp', 'hallo2');
  }
  
  public function show($id, $str)
  {
//    var_dump($id);
//    var_dump($str);
    $db = $this->components->get('db');
//    $db->execute(
//      "insert into Student set prename=':prename', aftername=':aftername', grade=:grade",
//      [
//        'prename' => 'jonas',
//        'aftername' => 'inject'.html_entity_decode('<script>alert("here")</script>'),
//        'grade' => 5
//      ]
//    );
    $result = $db->execute('select * from Student')->getData();
    $this->components->get('logger')->log('student', $result);
//    Components::getInstance()->get('logger')->log('$_SERVER[REQUEST_METHOD]', $_SERVER['REQUEST_METHOD']);

    $arrData = array(
      'name' => 'Fabien',
      'id' => $id,
      'str' => $str,
    );
    if ('post' == strtolower($_SERVER['REQUEST_METHOD'])) {
      $template = 'Task1/show_rendered_content.twig';
    } else {
      $template = 'Task1/show.twig';
    }
    echo $this->components->get('view')->render($template, array(
      'students' => $result,
      'data' =>$arrData
    ));
  }
}
