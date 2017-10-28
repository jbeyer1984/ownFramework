<?php

namespace MyApp\src\Tasks\ParentChild;

interface DbOrmInterface
{

    /**
     * @param $fetchedArray
     * @param ModelDbInterface $model
     */
    public function equip($fetchedArray, $model);

    /**
     * @param $data
     * @param Model $model
     */
    public function fetchByData($data, $model);

    /**
     * @param ModelDbInterface $model
     */
    public function save($model);


    /**
     * @return array
     */
    public function getFetchedData();

    /**
     * @param array $fetchedData
     * @return $this
     */
    public function setFetchedData($fetchedData);

    /**
     * @return string
     */
    public function getTable();
}
