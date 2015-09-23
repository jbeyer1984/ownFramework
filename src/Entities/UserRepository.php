<?php


namespace MyApp\src\Entities;

use MyApp\src\Tasks\Interfaces\ResetInterface;
use MyApp\src\Tasks\Tasks;
use MyApp\src\Entities\User;
use MyApp\src\Utility\Db;
use MyApp\src\Components\Components;

class UserRepository extends Tasks implements ResetInterface
{
  /**
   * @var User
   */
  private $user;

  /**
   * @var Db
   */
  private $db;

  /**
   * @param $user User
   * @throws \Exception
   */
  public function __construct($user)
  {
    parent::__construct();
    $this->user = $user;
    $this->db = $this->components->get('db');
  }

  public function reset()
  {
  }

  public function init()
  {

  }

  public function getUserData()
  {
    $sql = "select * from User u where u.id=:id_user";
    $result = $this->db->execute($sql, array(
      'id_user' => $this->user->getId()
    ))->getData();
    return $result;
  }

  public function getMessages()
  {
    $sql = "select u.nick, m.message from Message m  inner join User u on u.id = m.id_user where id_user=:id_user";
    $result = $this->db->execute($sql, array(
      'id_user' => $this->user->getId()
    ))->getData();
    return $result;
  }

  /**
   * @return \MyApp\src\Entities\User
   */
  public function getUser()
  {
    return $this->user;
  }

  /**
   * @param \MyApp\src\Entities\User $user
   */
  public function setUser($user)
  {
    $this->user = $user;
  }
}