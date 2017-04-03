<?php

namespace MyApp\src\Tasks\Flexible\Xml;

class Creator
{
    const ATTRIBUTES_TO_REPLACE = 'ATTRIBUTES_TO_REPLACE';
    const CONTENT_TO_REPLACE = 'CONTENT_TO_REPLACE';
    const TAG_CLOSE_BEGIN = 'TAG_CLOSE_ONE';
    /**
     * @var Parser
     */
    private $parser;

    /**
     * @var string
     */
    private $xmlText;
    
    public function __construct(Parser $parser)
    {
        $this->parser = $parser;
        
        $this->init();
    }

    protected function init()
    {
        $this->xmlText = '';
    }

    /**
     * @return string
     */
    public function getConvertedData()
    {
        if (empty($this->xmlText)) {
            $this->convertToXmlText($this->parser);
        }
        
        return $this->xmlText;
    }

    /**
     * @param Parser $parser
     */
    protected function convertToXmlText(Parser $parser)
    {
        $dataArray = $parser->getParsedData();
//        $dump = print_r($dataArray, true);
//        error_log(PHP_EOL . '-$- in ' . basename(__FILE__) . ':' . __LINE__ . ' in ' . __METHOD__ . PHP_EOL . '*** $dataArray ***' . PHP_EOL . " = " . $dump . PHP_EOL);
        
        $xmlText = '<overview>';
        $spaceIndent = '    '; // 4 spaces for tab
        $space = [
            $spaceIndent
        ];
        
        $xmlText .= $this->getRecursiveXmlText($dataArray, $space);
        $xmlText .= PHP_EOL
            . '</overview>'
        ;

        $this->xmlText = $xmlText; 
    }

    /**
     * @param $dataArray
     * @param $space
     * @return string
     */
    protected function getRecursiveXmlText(&$dataArray, $space)
    {
        $xmlText = '';
        $spaceIndent = '    '; // 4 spaces for tab

        foreach ($dataArray as $key => $data) {
            $tag = $this->createTag($key, $space);
            $tagArray = [
                $tag
            ];
            
            $attributes = $this->createAttributes($data);
            $attributesArray = [
                $attributes
            ];
            
            if (!is_array($data)) {
                $xmlText .= PHP_EOL
                    . implode('', $space)
                    . '<' . $key . '>' . $data . '</' . $key . '>'
                ;
                continue;
            }
            
            $nestedContentArray = [];
            if ('attributes' !== $key) {
                if (is_array($data)) {
                    $closeNested = true;
                    if (isset($data[0])) {
                        $closeNested = false;
                    }
                    
                    if ($closeNested) {
                        $space[] = $spaceIndent;
                        if (is_array($data)) {
                            $nestedContent = $this->getRecursiveXmlText($data, $space);
                        } else {
                            $nestedContent = $data;
                        }
                        $nestedContentArray[] = $nestedContent;
                        array_pop($space);
                    } else {
                        $tagArray = [];
                        $attributesArray = [];
                        
                        foreach ($data as $nestedData) {
                            $tag = $this->createTag($key, $space);
                            $tagArray[] = $tag;
                            $attributes = $this->createAttributes($nestedData);
                            $attributesArray[] = $attributes;
                            if (is_array($data)) {
                                $space[] = $spaceIndent;
                                $nestedContent = $this->getRecursiveXmlText($nestedData, $space);
                                array_pop($space);
                            } else {
                                $nestedContent = $data;
                            }
                            $nestedContentArray[] = $nestedContent;
                        }
                    }
                    foreach ($tagArray as $index => $tag) {
                        $attributes = $attributesArray[$index];
                        $nestedContent = $nestedContentArray[$index];
                        
                        $explodedNestedContent = explode(PHP_EOL, $nestedContent);
                        array_shift($explodedNestedContent); // take away first PHP_EOL entry
                        
                        if (empty($explodedNestedContent)) { // one line Tag, no indent
                            $explodedTag = explode(PHP_EOL, $tag);
                            array_pop($explodedTag);
                            $tag = implode(PHP_EOL, $explodedTag);
                            $tag = str_replace(self::TAG_CLOSE_BEGIN, '/>', $tag);
                        }
                        
                        $tag = str_replace(self::ATTRIBUTES_TO_REPLACE, $attributes, $tag);
                        $tag = str_replace(self::CONTENT_TO_REPLACE, $nestedContent, $tag);
                        $tag = str_replace(self::TAG_CLOSE_BEGIN, '>', $tag);
                        $xmlText .= $tag;
                    }
                }
            }
        }

        return $xmlText;
    }

    /**
     * @param $dataArray
     * @param $space
     * @return string
     */
    protected function ____OldGetRecursiveXmlText(&$dataArray, $space)
    {
        $spaceAttribute = ' ';
        $xmlText = '';
        $spaceIndent = '    '; // 4 spaces for tab
        $openTag = '';
        
        foreach ($dataArray as $key => $data) {
            $createTag = true;
            if ('attributes' === $key) {
                continue;
            }
            if (is_int($key)) { // if multiple rows of same xml Identifier
                $createTag = false;
            }
            
            if ($createTag) { // leave out if multiple rows of same xml Identifier
                // create open tag
                $openTag = PHP_EOL
                    . implode('', $space)
                    . '<' . $key
                ;
                 
                if (is_array($data) && isset($data['attributes'])) {
                    
                    foreach ($data['attributes'] as $attributeKey => $attributeValue) {
                        $openTag .= $spaceAttribute . $attributeKey . '="' . $attributeValue . '"';
                    }
                }
            }
            
            if (is_array($data)) {
                if ($createTag) {
                    $space[] = $spaceIndent;
                }
                $recursiveText = $this->getRecursiveXmlText($data, $space);
//                $xmlText .= 
//                $openTag .= '>';
                $xmlText .= $openTag; // $openTag can be '' look above .. multiple rows of same ..
                
                $recursiveIndented = false;
                $explodedRecursiveText = explode(PHP_EOL, $recursiveText);
                if (0 < count($explodedRecursiveText)) {
                    foreach ($explodedRecursiveText as $line) {
                        if (0 < count($space) && false === strpos($line, implode('', $space))) {
                            $recursiveIndented = true;
                        }
                    }
                }
                array_pop($space);
                if (!$recursiveIndented) {
                    $closedTag = PHP_EOL
                        . implode('', $space)
                        . '</' . $key . '>'
                    ;    
                } else {
                    $closedTag = '/>';
                }
                
                $xmlText .= $recursiveText;
                $xmlText .= $closedTag;
            } else {
                $xmlText .= PHP_EOL
                    . implode('', $space)
                    . '<' . $key . '>' . $data . '</' . $key . '>'
                ;
            }
        }

        return $xmlText;
    }

    /**
     * @return string
     */
    public function getXmlText()
    {
        return $this->xmlText;
    }

    /**
     * @param string $xmlText
     * @return Creator
     */
    public function setXmlText($xmlText)
    {
        $this->xmlText = $xmlText;

        return $this;
    }

    /**
     * @param $key
     * @param $space
     * @return string
     */
    protected function createTag($key, $space)
    {
        $tag = PHP_EOL
            . implode('', $space)
            . '<' . $key
            . self::ATTRIBUTES_TO_REPLACE
            . self::TAG_CLOSE_BEGIN
            . self::CONTENT_TO_REPLACE
            . PHP_EOL
            . implode('', $space)
            . '</' . $key . '>'
        ;

        return $tag;
    }

    /**
     * @param $space
     * @param $recursiveText
     * @return bool
     */
    protected function isNestedIndent($space, $recursiveText)
    {
        $recursiveIndented = false;
        $explodedRecursiveText = explode(PHP_EOL, $recursiveText);
        if (0 < count($explodedRecursiveText)) {
            foreach ($explodedRecursiveText as $line) {
                if (0 < count($space) && false === strpos($line, implode('', $space))) {
                    $recursiveIndented = true;
                }
            }

            return $recursiveIndented;
        }

        return $recursiveIndented;
    }

    /**
     * @param $data
     * @return string
     */
    protected function createAttributes($data)
    {
        $spaceAttribute = ' ';
        $attributes = '';
        if (is_array($data) && isset($data['attributes'])) {
            foreach ($data['attributes'] as $attributeKey => $attributeValue) {
                $attributes .= $spaceAttribute . $attributeKey . '="' . $attributeValue . '"';
            }

            return $attributes;
        }
    }
}
