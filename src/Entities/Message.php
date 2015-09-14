<?php


namespace MyApp\src\Entities;

use MyApp\src\Entities\MessageRepository;

class Message
{
  private $id;
  private $message;
  private $time;

  private $repository;

  public function __construct()
  {
  }

  public function reset()
  {
  }

  public function init()
  {

  }

  /**
   * @return mixed
   */
  public function getId()
  {
    return $this->id;
  }

  /**
   * @param mixed $id
   */
  public function setId($id)
  {
    $this->id = $id;
  }

  /**
   * @return mixed
   */
  public function getMessage()
  {
    return $this->message;
  }

  /**
   * @param mixed $message
   */
  public function setMessage($message)
  {
    $this->message = $message;
  }

  /**
   * @return mixed
   */
  public function getTime()
  {
    return $this->time;
  }

  /**
   * @param mixed $time
   */
  public function setTime($time)
  {
    $this->time = $time;
  }

  /**
   * @return MessageRepository
   */
  public function getRepository()
  {
    return $this->repository;
  }

  /**
   * @param $repository MessageRepository
   */
  public function setRepository($repository)
  {
    $this->repository = $repository;
  }
}