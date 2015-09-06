<?php


namespace MyApp\src\Utility;

use MyApp\src\Components\Components;
use \Exception;

class Router
{
  private $routing;
  
  public function __construct()
  {
    $this->init(); 
  }
  
  public function init()
  {
    $this->routing = [
      'task1' => [
        'show' => [
          'params' => '${id}/${str}',
          'class' => 'MyApp\src\Tasks\Task1',
        ]
      ]
    ];
  }
  
  public function route()
  {
    $requestUrl = str_replace('/index.php/', '', $_SERVER['REQUEST_URI']);
    $urlParams = explode('/', $requestUrl);
    Components::getInstance()->get('logger')->log('$sections', $urlParams);
    $subject = array_shift($urlParams);
    $action = array_shift($urlParams);
    if (!isset($this->routing[$subject])) {
      throw new Exception("subject does not exist for route: ".$subject.":".$action);
    }
    if (!isset($this->routing[$subject][$action])) {
      throw new Exception("action does not exist for route: ".$subject.":".$action);
    }
    $routeSettings = $this->routing[$subject][$action];
    $class = $routeSettings['class'];
    $params = explode('/', $routeSettings['params']);
    $obj = new $class();
    //@todo validate params
    if (count($params) != count($urlParams)) {
      throw new Exception("wrong parameters for route: ".$subject.":".$action);
    }
    call_user_func_array(array($obj, $action), $urlParams);
    
    Components::getInstance()->get('logger')->log('$params', $params);
  }
}