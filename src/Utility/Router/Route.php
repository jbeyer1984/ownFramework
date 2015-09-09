<?php

namespace MyApp\src\Utility\Router;

use \Exception;

class Route
{
  /**
   * @var array
   */
  private $params;

  /**
   * @var string 'get'|'post'
   */
  private $method;

  /**
   * @var string
   */
  private $subject;

  /**
   * @var string
   */
  private $action;

  public function __construct()
  {
  }

  /**
   * @param $router \MyApp\src\Utility\Route\Router
   * @throws Exception
   */
  public function generate($router)
  {
    $requestUrl = str_replace('/index.php/', '', $_SERVER['REQUEST_URI']);
    $this->method = strtolower($_SERVER['REQUEST_METHOD']);
    $urlParams = explode('/', $requestUrl);
    $this->verifyCountOfParams($urlParams);
    $this->subject = array_shift($urlParams);
    $this->action = array_shift($urlParams);
    $this->params = $urlParams;
    $this->params = $this->retVerifiedRoutingParams($router->getRoutingConfig());
  }

  /**
   * @param $urlParams
   * @throws Exception
   */
  private function verifyCountOfParams($urlParams)
  {
    if (2 > count($urlParams)) {

      $str =  "This Framework uses at least 2 parameters after index.php<br>";
      $str .=  "first parameter is subject<br>";
      $str .=  "second parameter is action<br>";
      $str .=  "example would be index.php/task1/show<br>";
      $str .=  "routings are configured in src/Utility/Router.php<br>";
      echo $str;
      throw new Exception("not enough params declared");
    }
  }

  /**
   * @param $routingConfig
   * @return array
   * @throws Exception
   */
  private function retVerifiedRoutingParams($routingConfig)
  {
    $params = array();
    if ('get' == $this->method) {
      $paramsMethodConfig = explode('/', $routingConfig[$this->subject][$this->action]['params'][$this->method]);
      if (count($paramsMethodConfig) != count($this->params)) {
        $str = "wrong ". $this->method ." parameters for route: " . $this->subject . ":" . $this->action;
        echo $str;
        throw new Exception($str);
      }
      $params = $this->params;
    } elseif ('post' == $this->method) {
      $post = $_POST;
      $postParams = array();
      $paramsMethodConfig = explode('/', $this->routing[$this->subject][$this->action]['params'][$this->method]);
      foreach ($paramsMethodConfig as $identifier => $data) {
        if (!isset($post[$identifier])) {
          $str = $this->method.' parameter: '.$identifier.' not sent in params';
          echo $str;
          throw new Exception($str);
        } else {
          $postParams[$identifier] = $data;
        }
      }
      $params = $postParams;
    }
    return $params;
  }

  /**
   * @return array
   */
  public function getParams()
  {
    return $this->params;
  }

  /**
   * @param array $params
   */
  public function setParams($params)
  {
    $this->params = $params;
  }

  /**
   * @return string
   */
  public function getMethod()
  {
    return $this->method;
  }

  /**
   * @param string $method
   */
  public function setMethod($method)
  {
    $this->method = $method;
  }

  /**
   * @return string
   */
  public function getSubject()
  {
    return $this->subject;
  }

  /**
   * @param string $subject
   */
  public function setSubject($subject)
  {
    $this->subject = $subject;
  }

  /**
   * @return string
   */
  public function getAction()
  {
    return $this->action;
  }

  /**
   * @param string $action
   */
  public function setAction($action)
  {
    $this->action = $action;
  }
}