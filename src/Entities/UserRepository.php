<?php


namespace MyApp\src\Entities;

use MyApp\src\Tasks\Tasks;
use MyApp\src\Entities\User;
use MyApp\src\Components\Components;

class UserRepository extends Tasks
{
  /**
   * @var User
   */
  private $user;

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
}