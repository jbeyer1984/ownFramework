<?php

define( 'VAR_WWW', '/var/www/ownFramework' );
require_once(VAR_WWW.'/src/bootstrap.php');
require_once(ROOT_PATH.'/vendor/autoload.php');

use MyApp\src\Utility\Router\Router;
use MyApp\src\Utility\Router\Route;
use MyApp\src\Utility\HTTP;
use \Mockery as m;

class RouteTest extends PHPUnit_Framework_TestCase
{
  /**
   * @var Router
   */
  public $router;

  public $routerConfig;

  /**
   * @var Route
   */
  public $route;

  public function setUp()
  {
    $this->router = new Router();
    $this->route = new Route();
    $_SERVER['REQUEST_URI'] = '/index.php/blog/login';
    HTTP::reset();
    // params without get or post parameter
    $this->routerConfig = [
      'blog' => [
        'login' => [
          'params' => [
          ],
          'class' => 'MyApp\src\Tasks\Blog\Blog',
        ]
      ]
    ];
    $this->router->setRoutingConfig($this->routerConfig);
  }

  /**
   * Call protected/private method of a class.
   *
   * @param object &$object    Instantiated object that we will run method on.
   * @param string $methodName Method name to call
   * @param array  $parameters Array of parameters to pass into method.
   *
   * @return mixed Method return.
   */
  public function invokeMethod(&$object, $methodName, array $parameters = array())
  {
    $reflection = new \ReflectionClass(get_class($object));
    $method = $reflection->getMethod($methodName);
    $method->setAccessible(true);

    return $method->invokeArgs($object, $parameters);
  }

  /**
   * @param $method string
   */
  private function checkMethodRouteWithoutMethodParameterInConfig($method)
  {
    $_SERVER['REQUEST_METHOD'] = $method;
    $this->route->generate($this->router);
    $this->assertEquals($this->route->getParams(), array());
  }

  /**
   * @param $method string
   */
  private function checkMethodRouteWithGetParameterButEmptyString($method)
  {
    $_SERVER['REQUEST_METHOD'] = $method;
    $this->routerConfig['blog']['login']['params'][strtolower($method)] = '';
    $this->router->setRoutingConfig($this->routerConfig);
    $this->route->generate($this->router);
    $this->assertEquals($this->route->getParams(), array());
  }

  /**
   * @param $method string
   */
  private function checkMethodRouteWithGetParameters($method)
  {
    $_SERVER['REQUEST_METHOD'] = $method;
    $_SERVER['REQUEST_URI'] = '/index.php/blog/login/test@yes.com/success';
    $this->routerConfig['blog']['login']['params'][strtolower($method)] = 'email/password';
    $this->router->setRoutingConfig($this->routerConfig);
    $this->route->generate($this->router);
    $params = [
      0 => 'test@yes.com',
      1 => 'success'
    ];
    $this->assertEquals($this->route->getParams(), $params);
  }

  /**
   * @param $method string
   */
  private function checkMethodRouteWithPostParameterButEmptyString($method)
  {
    $_SERVER['REQUEST_METHOD'] = $method;
    $this->routerConfig['blog']['login']['params'][strtolower($method)] = '';
    $this->router->setRoutingConfig($this->routerConfig);
    $this->route->generate($this->router);
    $this->assertEquals($this->route->getParams(), array());
  }

  /**
   * @param $method string
   */
  private function checkMethodRouteWithPostParameters($method)
  {
    $_SERVER['REQUEST_METHOD'] = $method;
    $_POST = [
      'email' => 'test@yes.com',
      'password' => 'success'
    ];
    $_SERVER['REQUEST_URI'] = '/index.php/blog/login';
    $this->routerConfig['blog']['login']['params'][strtolower($method)] = 'email/password';
    $this->router->setRoutingConfig($this->routerConfig);
    $this->route->generate($this->router);
    $params = [
      0 => 'test@yes.com',
      1 => 'success'
    ];
    $this->assertEquals($this->route->getParams(), $params);
  }

  public function testGetWithLessParamsInVerifier()
  {
    $urlParams = [
      0 => 'blog',
      1 => ''
    ];
    $this->invokeMethod($this->route, 'verifyCountOfParams', array($urlParams));
    $urlParams = [
      0 => 'blog',
      1 => 'login'
    ];
    $this->invokeMethod($this->route, 'verifyCountOfParams', array($urlParams));
  }

  /**
   * @expectedException Exception
   */
  public function testGetWithLessParamsInVerifierWithExcpetion()
  {
    $urlParams = [
      0 => 'blog'
    ];
    $this->invokeMethod($this->route, 'verifyCountOfParams', array($urlParams));
  }

  /**
   * @expectedException Exception
   */
  public function testGetWithLessParams()
  {
    $_SERVER['REQUEST_URI'] = '/index.php';
    $_SERVER['REQUEST_METHOD'] = 'GET';
    $this->routerConfig['blog']['login']['params']['get'] = '';
    $this->router->setRoutingConfig($this->routerConfig);
    $this->route->generate($this->router);

    $_SERVER['REQUEST_URI'] = '/index.php/';
    $this->route->generate($this->router);

    $_SERVER['REQUEST_URI'] = '/index.php/blog';
    $this->route->generate($this->router);
  }

  public function testSubjectAndAction()
  {
    $_SERVER['REQUEST_METHOD'] = 'GET';
    $this->route->generate($this->router);
    $this->assertEquals($this->route->getSubject(), 'blog');
    $this->assertEquals($this->route->getAction(), 'login');
  }

  public function testGetRouteWithoutGetParameterInConfig()
  {
    $this->checkMethodRouteWithoutMethodParameterInConfig('GET');
  }

  public function testGetRouteWithGetParameterButEmptyString()
  {
    $this->checkMethodRouteWithGetParameterButEmptyString('GET');
  }

  public function testGetRouteWithParameters()
  {
    $_SERVER['REQUEST_METHOD'] = 'GET';
    $_SERVER['REQUEST_URI'] = '/index.php/blog/login/test@yes.com/success'; // joke no get with login
    $this->routerConfig['blog']['login']['params']['get'] = 'email/password';
    $this->router->setRoutingConfig($this->routerConfig);
    $this->route->generate($this->router);
    $params = [
      0 => 'test@yes.com',
      1 => 'success'
    ];
    $this->assertEquals($this->route->getParams(), $params);
  }

  public function testPostRouteWithoutPostParameterInConfig()
  {
    $this->checkMethodRouteWithoutMethodParameterInConfig('POST');
  }

  public function testPostRouteWithPostParameterButEmptyString()
  {
    $this->checkMethodRouteWithPostParameterButEmptyString('POST');
  }

  public function testPostRouteWithParameters()
  {
    $this->checkMethodRouteWithPostParameters('POST');
  }
}