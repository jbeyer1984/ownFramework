<?php


namespace MyApp\src\Entities;

use MyApp\src\Tasks\Blog\Message;
use MyApp\src\Entities\UserRepository;
use MyApp\src\Tasks\Interfaces\ResetInterface;


class User implements ResetInterface
{
  private $id;
  private $prename;
  private $aftername;
  private $nick;
  private $email;
  private $password;

  private $repository;

  /**
   * @var array(Message)
   */
  private $messages;

  public function __construct()
  {
  }

  public function reset()
  {
  }

  public function init()
  {

  }

  public function getMessages()
  {
    return $this->messages;
  }

  /**
   * @param $messages array(MyApp\src\Entities\Message)
   */
  public function setMessages($messages)
  {
    $this->messages = $messages;
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
  public function getPrename()
  {
    return $this->prename;
  }

  /**
   * @param mixed $prename
   */
  public function setPrename($prename)
  {
    $this->prename = $prename;
  }

  /**
   * @return mixed
   */
  public function getAftername()
  {
    return $this->aftername;
  }

  /**
   * @param mixed $aftername
   */
  public function setAftername($aftername)
  {
    $this->aftername = $aftername;
  }

  /**
   * @return mixed
   */
  public function getNick()
  {
    return $this->nick;
  }

  /**
   * @param mixed $nick
   */
  public function setNick($nick)
  {
    $this->nick = $nick;
  }

  /**
   * @return mixed
   */
  public function getEmail()
  {
    return $this->email;
  }

  /**
   * @param mixed $email
   */
  public function setEmail($email)
  {
    $this->email = $email;
  }

  /**
   * @return UserRepository
   */
  public function getRepository()
  {
    return $this->repository;
  }

  /**
   * @param $repository UserRepository
   */
  public function setRepository($repository)
  {
    $this->repository = $repository;
  }

  /**
   * @return mixed
   */
  public function getPassword()
  {
    return $this->password;
  }

  /**
   * @param mixed $password
   */
  public function setPassword($password)
  {
    $this->password = $password;
  }
}