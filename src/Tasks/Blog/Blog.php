<?php


namespace MyApp\src\Tasks\Blog;

use MyApp\src\Entities\MessageRepository;
use MyApp\src\Factories\UserFactory;
use MyApp\src\Tasks\Tasks;
use MyApp\src\Components\Components;
use MyApp\src\Tasks\Interfaces\ResetInterface;
use MyApp\src\Utility\HTTP;

class Blog extends Tasks implements ResetInterface
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
//      Components::getInstance()->get('logger')->log('"session will be started"', "true");
      session_start();
    }
//    Components::getInstance()->get('logger')->log('$email', $email);
    /** @var \MyApp\src\Utility\Session $session */
    $session = $this->components->get('session');
    
    if ($session->isLoggedIn()) {
      HTTP::redirect('blog/show');
    } else {
      $session->login($email, $password);
      if ($session->isLoggedIn()) {
        HTTP::redirect('blog/show');
      }
    }

    $template = 'Blog/'.strtolower(__FUNCTION__).'/'.strtolower(__FUNCTION__);
    if ('post' == strtolower($_SERVER['REQUEST_METHOD'])) {
      $template .= '_rendered.twig';
    } else {
      $template .= '.twig';
    }

    echo $this->components->get('view')->render($template, array(
      'templateContext' => 'login'
    ));
//    $this->show();
  }

  public function show()
  {
    // login/show switch with session
    if (!$this->components->get('session')->isLoggedIn()) {
      HTTP::redirect('blog/login');
    }

    //user is logged in
//    Components::getInstance()->get('logger')->log('$_SESSION', $_SESSION);
    $db = $this->components->get('db');
    $sql = "select nick, prename, aftername from User";
//    $dump = print_r($sql, true);
//    error_log("\n" . '-$- in ' . __FILE__ . ':' . __LINE__ . ' in ' . __METHOD__ . "\n" . '*** $sql ***' . "\n = " . $dump);
    $resultUser = $db->execute($sql)->getData();
    $user = UserFactory::getInstance()->retCreatedUser($_SESSION['id_user']);
    $resultMessagesOwn = $user->getRepository()->getMessages();
    $messageRepo = new MessageRepository();
    $resultMessagesAll = $messageRepo->getAllMessagesWithNick();
    $resultMessagesByNick = $this->packMessagesByNick($resultMessagesAll);

//    Components::getInstance()->get('logger')->log('$resultMessage', $resultMessagesOwn);

    $template = 'Blog/'.strtolower(__FUNCTION__).'/'.strtolower(__FUNCTION__);
    
    $serverRequestMethod = HTTP::getMethod();
//    Components::getInstance()->get('logger')->log('$serverRequestMethod', $serverRequestMethod);
    if ('post' == $serverRequestMethod || isset($_GET['ajax'])) {
      $template .= '_rendered.twig';
    } else {
      $template .= '.twig';
    }
    echo $this->components->get('view')->render($template, array(
      'users' => $resultUser,
      'messagesOwn' => $resultMessagesOwn,
      'messagesAllByNick' => $resultMessagesByNick,
      'templateContext' => 'show'
    ));
  }

  public function logout()
  {
    if (PHP_SESSION_NONE == session_status()) {
      session_start();
      setcookie(session_name(), "", time() - 3600, "/" );
      //clear session from globals
      $_SESSION = array();
      //clear session from disk
      session_destroy();
    }
    
    if ($_POST['ajaxCall']) {
      $template = 'Blog/'.strtolower('login').'/'.strtolower('login');
      $template .= '_rendered.twig';
      echo $this->components->get('view')->render($template, array(
          'templateContext' => 'login'
      ));
      die();
    }
//    HTTP::redirect('blog/login');
//    $this->login();
  }

  /**
   * Store all messages in array by nickname
   * 
   * @param $resultMessagesAll
   * @return array
   */
  private function packMessagesByNick($resultMessagesAll)
  {
    $dump = print_r($resultMessagesAll, true);
    error_log("\n" . '-$- in ' . __FILE__ . ':' . __LINE__ . ' in ' . __METHOD__ . "\n" . '*** $resultMessagesAll ***' . "\n = " . $dump);
    $nestedMessages = array();
    foreach ($resultMessagesAll as $row) {
      $nick = $row['nick'];
      if (!isset($nestedMessages[$nick])) {
        $nestedMessages[$nick] = array();
      }
      $nestedMessages[$nick][] = $row;
    }
    return $nestedMessages;
  }


}