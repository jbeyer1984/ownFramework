<?php
define('VAR_WWW', '/var/www/ownFramework');
//define( 'VAR_WWW', 'D:\\Programme\\xampp\\htdocs\\ownFramework\\' );
require_once(VAR_WWW . '/src/bootstrap.php');
require_once(ROOT_PATH . '/vendor/autoload.php');

use MyApp\src\Tasks\RecursiveTree\RecursiveTree;

class RecursiveTreeTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var RecursiveTree
     */
    private $recursiveTree;
    
    public function setUp()
    {
        $this->recursiveTree = new RecursiveTree();    
    }

    /**
     * @param $dataToTest
     * @dataProvider prepareRecursiveTreeProvider
     */
    public function testCreateRecursiveTree($dataToTest)
    {
        foreach ($dataToTest['in'] as $data) {
            $testArray = $data;
            $preparedArray = $this->recursiveTree->getPreparedArrayStructure($testArray);
            $this->assertEquals($dataToTest['out'], $preparedArray);
        }
    }

    public function prepareRecursiveTreeProvider()
    {
        return [
            [
                [
                    'in' => [
                        [
                            'test' => [
                                'inner_test' => 'inner_test_value'
                            ]    
                        ]
                    ],
                    'out' => [
                        'test' => [
                            'inner_test' => 'inner_test_value'
                        ]
                    ]    
                ],
                [
                    'in' => [
                        [
                            [
                                'test' => [
                                    'inner_test' => 'inner_test_value'
                                ]
                            ],
                            [
                                'test' => [
                                    'inner_test2' => 'inner_test_value2'
                                ]
                            ]    
                        ]
                    ],
                    'out' => [
                        'test' => [
                            'inner_test' => 'inner_test_value',
                            'inner_test2' => 'inner_test_value2'
                        ]
                    ]
                ]
            ],
        ];
    }
}
