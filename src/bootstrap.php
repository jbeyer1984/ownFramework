<?php

use MyApp\src\Autoloader;

define( 'ROOT_PATH', dirname( dirname( __FILE__ ) ) . '/' );
define( 'APP_PATH', ROOT_PATH . 'app/' );
define( 'CONFIG_PATH', ROOT_PATH . 'config/' );
define( 'SRC_PATH', ROOT_PATH . 'src/' );
define( 'DB_PATH', ROOT_PATH . 'db/' );
define( 'WEB_PATH', ROOT_PATH . 'public/' );

require_once SRC_PATH . 'Autoloader.php';

$autoloader = new Autoloader('MyApp');
$autoloader->register();
