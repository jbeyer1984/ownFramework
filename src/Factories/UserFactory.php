<?php

namespace MyApp\src\Factories;

use MyApp\src\Entities\User;
use MyApp\src\Entities\UserRepository;

class UserFactory
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
   * @param $id
   * @return User
   */
  public function retCreatedUser($id = 0)
  {
    $user = new User();
    if (0 < $id) {
      $user->setId($id);
    } else {
      $user->setId(0); // dump user
    }
    $userRepository = new UserRepository($user);
    $user->setRepository($userRepository);
    $data = $user->getRepository()->getUserData();
    foreach ($data as $identifier => $value) {
      $func = 'set'.ucfirst($identifier);
      $user->$func($value);
    }
    return $user;
  }
}