<?php


namespace MyApp\src\Utility;

use MyApp\src\Components\Components;
use MyApp\src\Factories\UserFactory;

class Session
{
  private $salt;

  public function __construct()
  {
  }

  public function reset()
  {
  }

  public function init()
  {
    $this->salt = 'nonTheLess';
  }

  public function checkLogin()
  {
    if (PHP_SESSION_NONE == session_status()) {
      Components::getInstance()->get('logger')->log('"hallo"', "hallo");
      session_start();
    }
    Components::getInstance()->get('logger')->log('isset($_SESSION[password])', !isset($_SESSION['password']));
    $user = UserFactory::getInstance()->retCreatedUser($_SESSION['id_user']);

    $this->salt = 'nonTheLess';

    if (!isset($_SESSION['password']) || $_SESSION['password'] != hash('sha512', $user->getPassword().$this->salt) ) {
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
//      return;
    }
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
    $user = UserFactory::getInstance()->retCreatedUser($_SESSION['id_user']);

    if (isset($_SESSION['password']) && $_SESSION['password'] == hash('sha512', $user->getPassword().$this->salt) ) {
//      $this->show();
//      return;
      header('Location: http://ownframework/index.php/blog/show');
      die();
    }

    $db = Components::getInstance()->get('db');
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
      echo Components::getInstance()->get('view')->render($template, array(
        'templateContext' => 'login'
      ));
    } else {
//      session_start();
      // set session data for user
      $this->salt = 'nonTheLess';
      $session = $_SESSION;
      $session['id_user'] = $result[0]['id'];
      $session['email'] = $email;
      $session['password'] = hash('sha512', $password.$this->salt);
      // @todo encrypt session data

      $_SESSION = $session;
      header('Location: http://ownframework/index.php/blog/show');
      die();
//      $this->show();
    }
  }

  /**
   * @return mixed
   */
  public function getSalt()
  {
    return $this->salt;
  }

  /**
   * @param mixed $salt
   */
  public function setSalt($salt)
  {
    $this->salt = $salt;
  }


}