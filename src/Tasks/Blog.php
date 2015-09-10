<?php


namespace MyApp\src\Tasks;

use MyApp\src\Tasks\Tasks;
use MyApp\src\Components\Components;

class Blog extends Tasks
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

  /**
   * @param $email
   * @param $password
   * @internal param $user
   */
  public function login($email = '', $password = '')
  {
    // show/login switch with session
    if (PHP_SESSION_NONE == session_status()) {
      Components::getInstance()->get('logger')->log('"hallo"', "hallo");
      session_start();
    }
    Components::getInstance()->get('logger')->log('isset($_SESSION[password])', !isset($_SESSION['password']));
    if (isset($_SESSION['password']) && -1 < strpos($_SESSION['password'], 'nonTheLess')) {
      $this->show();
      return;
    }

    $db = $this->components->get('db');
//    $db->execute(
//      "insert into Student set prename=':prename', aftername=':aftername', grade=:grade",
//      [
//        'prename' => 'jonas',
//        'aftername' => 'inject'.html_entity_decode('<script>alert("here")</script>'),
//        'grade' => 5
//      ]
//    );
    $query = "select * from User"
      ." where email LIKE ':email' and password LIKE ':password'"
    ;

    $result = $db->execute($query, array(
      'email' => $email,
      'password' => $password
    ))->getData();

    Components::getInstance()->get('logger')->log('$----------result', $result);

    if (empty($result)) {
      $template = 'Blog/'.strtolower(__FUNCTION__).'/'.strtolower(__FUNCTION__);
      if ('post' == strtolower($_SERVER['REQUEST_METHOD'])) {
        $template .= '_rendered.twig';
      } else {
        $template .= '.twig';
      }
      echo $this->components->get('view')->render($template);
    } else {
      session_start();
      // set session data for user
      $salt = 'nonTheLess';
      $session = $_SESSION;
      $session['email'] = $email;
      $session['password'] = $password.$salt;
      // @todo encrypt session data

      $_SESSION = $session;
      header('Location: http://ownframework/index.php/blog/show');
      die();
//      $this->show();
    }

  }

  public function show()
  {
//      ." where email LIKE ':email' and password LIKE ':password'"
//    ;
    // login/show switch with session
    if (PHP_SESSION_NONE == session_status()) {
      Components::getInstance()->get('logger')->log('"hallo"', "hallo");
      session_start();
    }
    Components::getInstance()->get('logger')->log('isset($_SESSION[password])', !isset($_SESSION['password']));
    if (!isset($_SESSION['password']) || 0 > strpos($_SESSION['password'], 'nonTheLess')) {
//      if (isset($_COOKIE[session_name()])) {
        setcookie(session_name(), "", time() - 3600, "/" );
        //clear session from globals
        $_SESSION = array();
        //clear session from disk
        session_destroy();
//      }
      header('Location: http://ownframework/index.php/blog/login');
      die();
//      $this->login();
      return;
    }
    Components::getInstance()->get('logger')->log('$_SESSION', $_SESSION);
    $db = $this->components->get('db');
    $query = "select * from User";
    $result = $db->execute($query)->getData();

    $template = 'Blog/'.strtolower(__FUNCTION__).'/'.strtolower(__FUNCTION__);
    if ('post' == strtolower($_SERVER['REQUEST_METHOD'])) {
      $template .= '_rendered.twig';
    } else {
      $template .= '.twig';
    }
    echo $this->components->get('view')->render($template, array(
      'users' => $result,
    ));
  }

  public function logout()
  {
    if (PHP_SESSION_NONE == session_status()) {
      Components::getInstance()->get('logger')->log('"hallo"', "hallo");
      session_start();
      setcookie(session_name(), "", time() - 3600, "/" );
      //clear session from globals
      $_SESSION = array();
      //clear session from disk
      session_destroy();
    }
    header('Location: http://ownframework/index.php/blog/login');
    die();
//    $this->login();
  }



}