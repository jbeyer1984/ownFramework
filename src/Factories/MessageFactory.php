<?php


namespace MyApp\src\Factories;

use MyApp\src\Entities\MessageRepository;

class MessageFactory
{
  private static $instance;

  private function __construct()
  {
  }

  private function __clone()
  {
  }

  public static function getInstance()
  {
    if (!self::$instance) {
      self::$instance = new self;
    }
    return self::$instance;
  }

  /**
   * @return MessageRepository
   */
  public function retCreatedMessageRepository()
  {
    $messageRepository = new MessageRepository();
    return $messageRepository;
  }
}