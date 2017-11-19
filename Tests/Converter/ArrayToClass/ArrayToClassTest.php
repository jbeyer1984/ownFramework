<?php

define('VAR_WWW', '/var/www/ownFramework');
//define( 'VAR_WWW', 'D:\\Programme\\xampp\\htdocs\\ownFramework\\' );
require_once(VAR_WWW . '/src/bootstrap.php');
require_once(ROOT_PATH . '/vendor/autoload.php');

use MyApp\src\Converter\ArrayToClass\ArrayToClass;
use MyApp\src\Converter\ArrayToClass\Template\SkeletonClass;

class ArrayToClassTest extends PHPUnit_Framework_TestCase
{
    public function testSkeleton()
    {
        $skeleton = <<<TXT
/**
 * @author jbeyer
 */

class classOne
{
    private \$varOne;
    
    private \$varTwo;
    
}
TXT;
        $classData = [
            'attributes' => [
                'varOne',
                'varTwo',
            ]
        ];
        $printClass = new SkeletonClass('classOne');
        $template = $printClass->getCreatedTemplate($classData);
        
        $expect = preg_replace("/\s/", '', $skeleton);
        $toCheck = preg_replace("/\s/", '', $template);
        $this->assertEquals($expect, $toCheck);
        

    }

    public function testArrayToClass()
    {
        $classArray = [
            'one' => [
                'two',
                'three',
                'four' => [
                    'five'
                ],
                'six',
                'seven' => [
                    'eight'
                ]
            ]
        ];
        $arrayToClass = new ArrayToClass($classArray);
        
        $data = $arrayToClass->translate();
    }
}