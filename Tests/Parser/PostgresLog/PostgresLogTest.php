<?php

use MyApp\src\Parser\PostgresLog\PostgresLogParser;

define('VAR_WWW', '/var/www/ownFramework');
//define( 'VAR_WWW', 'D:\\Programme\\xampp\\htdocs\\ownFramework\\' );
require_once(VAR_WWW . '/src/bootstrap.php');
require_once(ROOT_PATH . '/vendor/autoload.php');

class PostgresLogTest extends PHPUnit_Framework_TestCase
{
    public function testPostgresLog_conditionsExtractText_simple()
    {
        $txt = <<<TXT
begin
XXX SELECT
xxx LINE 1
xxx LINE 2
end
TXT;
        
        $expected = <<<txt
XXX SELECT
xxx LINE 1
xxx LINE 2
txt;
        $parser = new PostgresLogParser();
        $actual = $parser->getParsedSelect($txt);
        
        $expected = str_replace(PHP_EOL, '', $expected);
        $actual = str_replace(PHP_EOL, '', $expected);
        
        $this->assertEquals($expected, $actual);
    }
}