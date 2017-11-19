<?php

namespace MyApp\src\Converter\ArrayToClass\Template;

class SkeletonClass
{
    /**
     * @var string
     */
    private $className;

    /**
     * @var string
     */
    private $skeleton;

    /**
     * SkeletonClass constructor.
     * @param $className
     */
    public function __construct($className)
    {
        $this->className = $className;
    }

    /**
     * @param $classData
     * @return string
     */
    public function getCreatedTemplate($classData)
    {
        $className = $this->className;
        $author = 'jbeyer';
        $attributes = $classData['attributes'];
        
        $privateAttributes = '';
        foreach ($attributes as $attribute) {
            $privateAttribute = <<<TXT
    private \$VAR;

TXT;
            $privateAttributes .= str_replace('VAR', $attribute, $privateAttribute);    
        }
        
$classSkeleton = <<<TXT
/**
 * @author {$author}
 */

class {$className}
{
{$privateAttributes}
}
TXT;

        $this->skeleton = $classSkeleton;
        
        return $this->skeleton;
    }

    /**
     * @return string
     */
    public function getClassName()
    {
        return $this->className;
    }
}