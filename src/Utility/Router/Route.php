<?php

namespace MyApp\src\Utility\Router;

use \Exception;
use MyApp\src\Components\Components;

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
    Components::getInstance()->get('logger')->log('$requestUrl', $requestUrl);
    $this->method = strtolower($_SERVER['REQUEST_METHOD']);
    $urlParams = explode('/', $requestUrl);
    $this->verifyCountOfParams($urlParams);
    $this->subject = array_shift($urlParams);
    $this->action = array_shift($urlParams);
    $this->params = $urlParams;
    Components::getInstance()->get('logger')->log('$this', $this);
    $this->verifyRouting($router->getRoutingConfig());
    $this->params = $this->retVerifiedRoutingParams($router->getRoutingConfig());
    Components::getInstance()->get('logger')->log('$this->params', $this->params);
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
    $paramsMethodConfigStr = $routingConfig[$this->subject][$this->action]['params'][$this->method];
    Components::getInstance()->get('logger')->log('$paramsMethodConfigStr', $paramsMethodConfigStr);
    $paramsMethodConfig = array();

//    Components::getInstance()->get('logger')->log('$paramsMethodConfig', $paramsMethodConfig);
//    Components::getInstance()->get('logger')->log('count($paramsMethodConfig)', count($paramsMethodConfig));

    if ('get' == $this->method) {
      if (!empty($paramsMethodConfigStr) && 0 < substr_count($paramsMethodConfigStr, '/')) {
        $paramsMethodConfig = explode('/', $routingConfig[$this->subject][$this->action]['params'][$this->method]);
      }
      Components::getInstance()->get('logger')->log('$paramsMethodConfig', $paramsMethodConfig);
      if (count($paramsMethodConfig) != count($this->params)) {
        $str = "wrong ". $this->method ." parameters for route: " . $this->subject . ":" . $this->action.', should be ';
        $str .= implode(', ', $paramsMethodConfig);
        echo $str;
        throw new Exception($str);
      }
      $params = $this->params;
    } elseif ('post' == $this->method) {
      $post = $_POST;
      $postParams = array();
//      Components::getInstance()->get('logger')->log('$post', $post);
      if (!empty($paramsMethodConfigStr) && 0 < substr_count($paramsMethodConfigStr, '/')) {
        $paramsMethodConfig = explode('/', $routingConfig[$this->subject][$this->action]['params'][$this->method]);
      }
      Components::getInstance()->get('logger')->log('$paramsMethodConfig', $paramsMethodConfig);
      foreach ($paramsMethodConfig as $identifier) {
        if (!isset($post[$identifier])) {
          $str = $this->method.' parameter: '.$identifier.' not sent in params, should be '.implode(', ', $paramsMethodConfig);
          echo $str;
          throw new Exception($str);
        } else {
          $postParams[$identifier] = $post[$identifier];
        }
      }
      $params = $postParams;
    }
    return $params;
  }

  /**
   * @param $routingConfig
   * @throws Exception
   * @internal param Route $route
   */
  private function verifyRouting($routingConfig)
  {
    if (!isset($routingConfig[$this->getSubject()])) {
      throw new Exception("subject does not exist for route: " . $this->subject . ":" . $this->action);
    }
    if (!isset($routingConfig[$this->getSubject()][$this->getAction()])) {
      throw new Exception("action does not exist for route: " . $this->subject . ":" . $this->action);
    }
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