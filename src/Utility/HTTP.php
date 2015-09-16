<?php


namespace MyApp\src\Utility;


class HTTP
{
  public function __construct()
  {
  }

  public function reset()
  {
  }

  public function init()
  {

  }

  public static function redirect($path)
  {
    if ('get' == strtolower($_SERVER['REQUEST_METHOD']) || !isset($_POST['ajaxCall'])) {
      header('Location: http://ownframework/index.php/'.$path);
    } elseif (isset($_POST['ajaxCall'])) {
      header('Location: http://ownframework/index.php/'.$path.'?ajax=true');
    }
    die();
  }
}