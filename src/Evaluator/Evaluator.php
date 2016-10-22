<?php

namespace MyApp\src\Evaluator;

class Evaluator
{
    /**
     * @var array
     */
    protected $conditionArray;

    /**
     * @var string
     */
    protected $printString;

    /**
     * @var $this
     */
    protected static $instance;
    
//    public function __construct()
//    {
//        $this->init();
//    }

    public static function getInstance()
    {
        if (null == self::$instance) {
            self::$instance = new Evaluator();
            self::$instance
                ->init();
        }
        
        return self::$instance;
    }

    public function init()
    {
        $this->conditionArray = [];
        $this->printString = '';
        
        return $this;
    }

    /**
     * @param int $width
     * @param string $identifier
     */
    public function pushConditionArray($width, $identifier)
    {
        if (!isset($this->conditionArray[0])) {
            $this->conditionArray = [];
        }
        
        $arrayToPush = [];
        $arrayToPush['width']      = $width * 5;
        $arrayToPush['identifier'] = $identifier;
        
        $this->conditionArray[] = $arrayToPush;
    }

    public function getPrintedConditionArray()
    {
        $this->printString .= "\n";
        foreach ($this->conditionArray as $pushedArray) {
            for ($i = 0; $i < $pushedArray['width']; $i++) {
                $this->printString .= ' ';
            }
            
            $this->printString .= '(' . $pushedArray['width']/5 . ')';
            $this->printString .= ' -- ';
            $this->printString .= $pushedArray['identifier'];
            $this->printString .= PHP_EOL;
        }
        
        return $this->printString;
    }

    public function getCreatedHtmlPage()
    {
        $printedIfArray = nl2br($this->getPrintedConditionArray());
        
        $html = <<< HTML
<html>
    <head>
        <title>evaluation</title>
    </head>
    <body>
        <pre>
            {$printedIfArray}
        
        </pre>
    </body>
</html>
HTML;
        
        return $html;

    }
}
