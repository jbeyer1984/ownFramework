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
//        'prename' => 'test',
//        'aftername' => 'nach',
//        'grade' => 5
//      ]
//    );
//    $result = $db->execute('select * from Student')->getData();
//    $this->components->get('logger')->log('student', $result);
//    Components::getInstance()->get('logger')->log('$_SERVER[REQUEST_METHOD]', $_SERVER['REQUEST_METHOD']);
//    if ('post' == strtolower($_SERVER['REQUEST_METHOD'])) {
//      echo json_encode(array(
//        'id' => $id,
//        'str' => $str
//      ));
//    } else {
      echo $this->components->get('view')->render('Task1/show.twig', array(
        'name' => 'Fabien',
        'id' => $id,
        'str' => $str,
      ));  
//    }
  }
}
