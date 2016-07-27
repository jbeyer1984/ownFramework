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
    $sql = "
      INSERT INTO Message
        SET id_user=':id_user',
        message=':message',
        `date`= NOW()
    ";
    $this->db->execute($sql, array(
      'id_user' => $idUser,
      'message' => $message,
    ));
  }

  public function getAllMessagesWithNick()
  {
    $sql = "
      SELECT u.nick, m.message, m.date
      FROM Message m
      INNER JOIN User u ON u.id = m.id_user
      ORDER BY m.date DESC, u.nick
    ";
    $data = $this->db->execute($sql)->getData();
    foreach ($data as $key => $row) {
      $data[$key]['message'] = nl2br($data[$key]['message']);
    }
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
