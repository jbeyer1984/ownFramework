<?php

namespace MyApp\src\Tasks\ParentChild;

class Relation
{
    private static $instance;

    /**
     * @var RelationTableOverview
     */
    private static $relationTableOverview;
    
    /**
     * @var RelationTemp
     */
    private static $relationTemp;
    
    private static $ormRegistry;
//
//    /**
//     * @var array
//     */
//    private $templateRelation;
    
//    public $manyToOne;

//    protected function __construct()
//    {
//        $this->init(); 
//    }

    /**
     * @return $this
     */
    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        
        return self::$instance;
    }

    /**
     * @return RelationTableOverview
     */
    public function getRelationTableOverview()
    {
        if (is_null(self::$relationTableOverview)) {
            self::$relationTableOverview = new RelationTableOverview();
        }
        
        return self::$relationTableOverview;
    }

    /**
     * @return RelationTemp
     */
    public function getRelationTemp()
    {
        if (is_null(self::$relationTemp)) {
            self::$relationTemp = new RelationTemp();
        }

        return self::$relationTemp;
    }
    
    /**
     * @return array
     */
    public function getTemplateRelation()
    {
        return [
            'PATH_PARENTMODEL' => [
                'identifier' => 'PARENTIDENTIFIER',
                'PATH_CHILDMODEL' => [
                    'identifier' => 'CHILDIDENTIFIER',
                ],
            ],
        ];
    }

    /**
     * @return OrmRegistry
     */
    public function getOrmRegistry()
    {
        if (is_null(self::$ormRegistry)) {
            self::$ormRegistry = new OrmRegistry();
        }
        
        return self::$ormRegistry;
    }
}
