<?php


namespace MyApp\src\Tasks\Blog;

use MyApp\src\Components\Components;
use MyApp\src\Tasks\Interfaces\ResetInterface;
use MyApp\src\Tasks\Tasks;


class Message extends Tasks implements ResetInterface
{
  public function __construct()
  {
    parent::__construct();
  }

  public function reset()
  {
  }

  public function init()
  {

  }

  public function create($message)
  {
    // show/login switch with session
    if (PHP_SESSION_NONE == session_status()) {
      session_start();
    }
    Components::getInstance()->get('logger')->log('isset($_SESSION[password])', !isset($_SESSION['password']));
    if (isset($_SESSION['password']) && -1 < strpos($_SESSION['password'], 'nonTheLess')) {
      $db = Components::getInstance()->get('db');
      $sql = "insert into Message set id_user=':id_user', message=':message'";
      $db->execute($sql, array(
        'id_user' => $_SESSION['id_user'],
        'message' => $message
      ));
    }
    header('Location: http://ownframework/index.php/blog/show');
    die();
  }
}