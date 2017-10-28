<?php

namespace MyApp\src\Tasks\ParentChild;

interface ModelDbInterface
{
    /**
     * @return DbOrmInterface
     */
    public function getDbData();
}
