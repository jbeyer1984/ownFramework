<?php


namespace MyApp\src\Tasks\Blog;

use MyApp\src\Components\Components;
use MyApp\src\Factories\MessageFactory;
use MyApp\src\Tasks\Interfaces\ResetInterface;
use MyApp\src\Tasks\Tasks;
use MyApp\src\Utility\HTTP;

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
   */
  public function create($message)
  {
    $this->components->get('session')->isLoggedIn();

    $messageRepository = MessageFactory::getInstance()->retCreatedMessageRepository();
    $messageRepository->insertMessageByUserId($_SESSION['id_user'], $message);

    HTTP::redirect('blog/show');
  }
}