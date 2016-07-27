<?php


namespace MyApp\src\Tasks\Blog;

use MyApp\src\Components\Components;
use MyApp\src\Factories\MessageFactory;
use MyApp\src\Tasks\Interfaces\ResetInterface;
use MyApp\src\Tasks\Tasks;
use MyApp\src\Utility\HTTP;
use MyApp\src\Utility\Session;

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

  /**
   * @param $message
   * @throws \Exception
   */
  public function create($message)
  {
    /** @var Session $session */
    $session = $this->components->get('session');
    $session->isLoggedIn();
    $messageRepository = MessageFactory::getInstance()->retCreatedMessageRepository();
    $messageRepository->insertMessageByUserId($_SESSION['id_user'], $message);

    HTTP::redirect('blog/show');
  }
}