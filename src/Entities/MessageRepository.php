<?php


namespace MyApp\src\Entities;

use MyApp\src\Tasks\Interfaces\ResetInterface;
use MyApp\src\Tasks\Tasks;
use MyApp\src\Utility\Db;

class MessageRepository extends Tasks implements ResetInterface
{
  /**
   * @var Db
   */
  private $db;

  public function __construct()
  {
    parent::__construct();
    $this->db = $this->components->get('db');
  }

  public function reset()
  {
  }

  public function init()
  {

  }

  public function insertMessageByUserId($idUser, $message)
  {
    $sql = "insert into Message set id_user=':id_user', message=':message'";
    $this->db->execute($sql, array(
      'id_user' => $idUser,
      'message' => $message
    ));
  }

  public function getAllMessagesWithNick()
  {
    $sql = "select u.nick, m.message from Message m inner join User u on u.id = m.id_user";
    $data = $this->db->execute($sql)->getData();
    return $data;
  }

  /**
   * @return Db
   */
  public function getDb()
  {
    return $this->db;
  }

  /**
   * @param Db $db
   */
  public function setDb($db)
  {
    $this->db = $db;
  }
}