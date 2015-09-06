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
    $this->printer();
    $db = $this->components->get('db');
    $db->execute(
      "insert into Student set prename=':prename', aftername=':aftername', grade=:grade",
      [
        'prename' => 'test',
        'aftername' => 'nach',
        'grade' => 5
      ]
    );
//    $result = $db->execute('select * from Student')->getData();
//    $this->components->get('logger')->log('student', $result);
  }
  
  
  public function printer()
  {
    echo Components::getInstance()->get('vars')->get('tmp');
  }
}
