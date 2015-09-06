<?php

use MyApp\src\Tasks\Task1;
use MyApp\src\Components\Components;

require_once('src/bootstrap.php');

Components::getInstance()->get('router')->route();

//$task1 = new Task1();

Components::getInstance()->get('logger')->log('--------------------', '--------------------');
