<?php

namespace Myapp\src\Factories;

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
  public function retCreatedUser($id)
  {
    $user = new User();
    if (0 < $id) {
      $user->setId($id);
    } else {
      $user->setId(0); // dump user
    }
    $userRepository = new UserRepository($user);
    $user->setUserRepository($userRepository);
    return $user;
  }
}