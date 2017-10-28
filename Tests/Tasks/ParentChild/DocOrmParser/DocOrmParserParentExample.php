<?php

namespace MyApp\Tests\Tasks\ParentChild\DocOrmParser;

use MyApp\src\Tasks\ParentChild\ModelDbInterface;

class DocOrmParserParentExample implements ModelDbInterface
{
    public function getDbData()
    {
        // TODO: Implement getDbData() method.
    }
    
    /**
     * @var DocOrmParserChildExample
     */
    private $child;

    /**
     * @return DocOrmParserChildExample
     */
    public function getChild()
    {
        return $this->child;
    }

    /**
     * @myOrm|manyToOne(targetEntity="DocOrmParserChildExample")
     * @param DocOrmParserChildExample $child
     * @return DocOrmParserParentExample
     */
    public function setChild($child)
    {
        $this->child = $child;

        return $this;
    }
}
