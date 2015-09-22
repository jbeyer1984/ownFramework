<?php

namespace MyApp\src\Utility;

class HTTP
{
  private static $method;

  public function __construct()
  {
  }

  public static function reset()
  {
    self::$method = null;
  }

  public function init()
  {

  }

  public static function redirect($path)
  {
    $path = str_replace('/index.php/', '', $path);
    $path = str_replace('index.php/', '', $path);
    $host = $_SERVER['HTTP_HOST'];
    if ('get' == strtolower($_SERVER['REQUEST_METHOD']) || !isset($_POST['ajaxCall'])) {
      header('Location: http://'.$host.'/index.php/'.$path);
    } elseif (isset($_POST['ajaxCall'])) {
      header('Location: http://'.$host.'/index.php/'.$path.'?ajax=true');
    }
    die();
  }

  public static function getMethod()
  {
    if (!self::$method) {
      if (isset($_POST['method'])) {
        self::$method = strtolower($_POST['method']);
      } else {
        self::$method = strtolower($_SERVER['REQUEST_METHOD']);
      }
    }
    return self::$method;
  }
}