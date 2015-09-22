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

  public function isLoggedIn()
  {
    if (PHP_SESSION_NONE == session_status()) {
      Components::getInstance()->get('logger')->log('"session will start"', "true");
      session_start();
    }

    if (!isset($_SESSION['id_user'])) {
      return false;
    }

    $user = UserFactory::getInstance()->retCreatedUser($_SESSION['id_user']);
    $logout = !isset($_SESSION['id_user'])
      || !isset($_SESSION['password'])
      || $_SESSION['password'] != hash('sha512', $user->getPassword().$this->salt)
    ;
    if ($logout) {
//      if (isset($_COOKIE[session_name()])) {
      setcookie(session_name(), "", time() - 3600, "/" );
      //clear session from globals
      $_SESSION = array();
      //clear session from disk
      session_destroy();
      return false;
    }

    return true;
  }

  /**
   * @param $email
   * @param $password
   * @internal param $user
   */
  public function login($email = '', $password = '')
  {
    if ('get' == strtolower($_SERVER['REQUEST_METHOD'])) {
      return;
    }
    // show/login switch with session
    if (PHP_SESSION_NONE == session_status()) {
      Components::getInstance()->get('logger')->log('"hallo"', "hallo");
      session_start();
    }
    $db = Components::getInstance()->get('db');
    $query = "select * from User"
      ." where email LIKE ':email' and password LIKE ':password'"
    ;

    $result = $db->execute($query, array(
      'email' => $email,
      'password' => $password
    ))->getData();

    Components::getInstance()->get('logger')->log('$----------result', $result);

    // set session data for user
    $salt = 'nonTheLess';
    $session = $_SESSION;
    $session['id_user'] = $result[0]['id'];
    $session['email'] = $email;
    $session['password'] = hash('sha512', $password.$salt);
    // @todo encrypt session data

    $_SESSION = $session;
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