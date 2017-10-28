<?php

namespace MyApp\Tests\Tasks\ParentChild\DocOrmParser;

use MyApp\src\Tasks\ParentChild\DbOrmInterface;
use MyApp\src\Tasks\ParentChild\ModelDbInterface;

class DocOrmParserChildExample implements ModelDbInterface
{
    public function getDbData()
    {
        // TODO: Implement getDbData() method.
    }

    /**
     * @var DocOrmParserParentExample.php
     */
    private $parent;

    /**
     * @return DocOrmParserParentExample
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @myOrm|oneToMany(targetEntity="DocOrmParserParentExample")
     * @param DocOrmParserParentExample $parent
     * @return DocOrmParserChildExample
     */
    public function setParent($parent)
    {
        $this->parent = $parent;

        return $this;
    }
}
