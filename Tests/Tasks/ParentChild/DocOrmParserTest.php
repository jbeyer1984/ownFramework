<?php

use MyApp\src\Tasks\ParentChild\DocOrmParser;
use MyApp\Tests\Tasks\ParentChild\DocOrmParser\DocOrmParserChildExample;
use MyApp\Tests\Tasks\ParentChild\DocOrmParser\DocOrmParserParentExample;

define('VAR_WWW', '/var/www/ownFramework');
//define( 'VAR_WWW', 'D:\\Programme\\xampp\\htdocs\\ownFramework\\' );
require_once(VAR_WWW . '/src/bootstrap.php');
require_once(ROOT_PATH . '/vendor/autoload.php');

class DocOrmParserTest extends PHPUnit_Framework_TestCase
{   
    public function setUp()
    {
    }

    public function tearDown()
    {
    }

    public function testOptions_DocOrmParser_success()
    {
        $docOrmParser = new DocOrmParser(new DocOrmParserChildExample());
        $docOrmParser->parse();
        $dump = print_r($docOrmParser->getDocOrmMethodAttributes(), true);
        error_log(PHP_EOL . '-$- in ' . basename(__FILE__) . ':' . __LINE__ . ' in ' . __METHOD__ . PHP_EOL . '*** $docOrmParser->getDocOrmMethodAttributes() ***' . PHP_EOL . " = " . $dump . PHP_EOL);

        $docOrmParser = new DocOrmParser(new DocOrmParserParentExample());
        $docOrmParser->parse();
        $dump = print_r($docOrmParser->getDocOrmMethodAttributes(), true);
        error_log(PHP_EOL . '-$- in ' . basename(__FILE__) . ':' . __LINE__ . ' in ' . __METHOD__ . PHP_EOL . '*** $docOrmParser->getDocOrmMethodAttributes() ***' . PHP_EOL . " = " . $dump . PHP_EOL);
    }
}


