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
            $tag = $this->equipTag($key, $space);
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
                            $tag = $this->equipTag($key, $space);
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
                        
                        if (1 < count($explodedNestedContent)) { // nested Content
                            $tag = $this->getCreatedNestedTag($tag);
                        } elseif (1 == count($explodedNestedContent)) {
                            $tag = $this->getCreatedTag($tag);
                        } else {
                            $tag = $this->getCreatedTagNoContent($tag);
                        }
                        
                        $tag = str_replace(self::ATTRIBUTES_TO_REPLACE, $attributes, $tag);
                        $tag = str_replace(self::CONTENT_TO_REPLACE, $nestedContent, $tag);
                        $xmlText .= $tag;
                    }
                }
            }
        }

        return $xmlText;
    }

    /**
     * @param $key
     * @param $space
     * @return array
     */
    protected function equipTag($key, $space)
    {
        $tag = [
            'key' => $key,
            'space' => implode('', $space)
        ];
        
        return $tag;
    }

    /**
     * @param $tag
     * @return string
     */
    protected function getCreatedTag($tag)
    {
        $tagText = PHP_EOL
            . $tag['space']
            . '<'
            . $tag['key']
            . self::ATTRIBUTES_TO_REPLACE
            . '>'
            . self::CONTENT_TO_REPLACE
            . '</' . $tag['key'] . '>'
        ;

        return $tagText;
    }

    /**
     * @param $tag
     * @return string
     */
    protected function getCreatedTagNoContent($tag)
    {
        $tagText = PHP_EOL
            . $tag['space']
            . '<'
            . $tag['key']
            . self::ATTRIBUTES_TO_REPLACE
            . '/>'
        ;

        return $tagText;
    }

    /**
     * @param $tag
     * @return string
     */
    protected function getCreatedNestedTag($tag)
    {
        $key = $tag['key'];
        $tag = PHP_EOL
            . $tag['space']
            . '<' . $key
            . self::ATTRIBUTES_TO_REPLACE
            . '>'
            . self::CONTENT_TO_REPLACE
            . PHP_EOL
            . $tag['space']
            . '</' . $key . '>'
        ;

        return $tag;
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
