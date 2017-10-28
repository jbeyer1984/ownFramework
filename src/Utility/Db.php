<?php

namespace MyApp\src\Utility;

use MyApp\src\Components\Components;

class Db
{
  private $connection;
  private $result;
  
  public function __construct()
  {
  }
  
  public function getData()
  {
    return $this->result;
  }

  /**
   * @param $sql
   * @param array $vars
   * @return $this
   * @throws \Exception
   */
  public function execute($sql, $vars = [])
  {
    $this->connect();
    $escapedString = mysqli_real_escape_string($this->connection, $sql);

    if (empty($vars)) {
      $this->executeNoArgs($sql);      
    } else {
      $this->executeWithArgs($sql, $vars);
    }
//    $this->disconnect();
    return $this;
  }

  /**
   * @param $sql
   */
  private function executeNoArgs($sql)
  {
    if (!$result = $this->connection->query($sql)) {
      die('There was an error running the query [' . $this->connection->error . ']');
    }
//    Components::getInstance()->get('logger')->log('$result', $result);
    
    if (!is_object($result)) {
      $this->result = array();
      return;
    }
    
    $this->result = [];
    while ($row = $result->fetch_assoc()) {
        $this->result[] = $row;
    }
    $result->free();
  }
  
  public function executeWithArgs($sql, $vars)
  {
    foreach ($vars as $identifier => $value)
    {
//      Components::getInstance()->get('logger')->log('$identifier', $identifier);
      $sql = str_replace(
        ':'.$identifier,
        mysqli_escape_string($this->connection, $value),
        $sql
      );
    }
//    Components::getInstance()->get('logger')->log('$sql', $sql);
    $this->executeNoArgs($sql);
  }

  private function connect()
  {
    $this->connection = mysqli_connect("localhost","root","user20","ownframework");
    mysqli_set_charset($this->connection, 'utf8');

//    if ($this->connection->connect_error) {
//      die('Connect Error (' . $this->connection->connect_errno . ') '
//      . $this->connection->connect_error);
//    }

    if(mysqli_connect_error()){
//      die('Unable to connect to database [' . $this->connection->connect_error . ']');
        die('Connect Error (' . mysqli_connect_errno() . ') '
            . mysqli_connect_error());
    }
  }

  private function disconnect()
  {
    mysqli_close($this->connection);
  }
}
