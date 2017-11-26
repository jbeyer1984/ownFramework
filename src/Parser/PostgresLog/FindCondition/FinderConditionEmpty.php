<?php


namespace MyApp\src\Parser\PostgresLog\FindCondition;


class FinderConditionEmpty extends FinderCondition
{
    public function find($str)
    {
        return true;
    }

}