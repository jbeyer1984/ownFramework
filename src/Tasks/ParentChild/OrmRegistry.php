<?php

namespace MyApp\src\Tasks\ParentChild;

class OrmRegistry
{
    /**
     * @var array
     */
    private $registerArray;

    /**
     * @var array
     */
    private $relationArray;

    /**
     * @var string
     */
    private $relation;
    
    public function __construct()
    {
        $this->init();
    }

    protected function init()
    {
        
    }

    public function register($class, $method)
    {
        $method = str_replace(['get', 'set'], '', $method);
//        $md5Serialized = md5(serialize($class));
        $objectHash = spl_object_hash($class);
        
        if (isset($this->registerArray[$objectHash])) {
            if ($method != $this->registerArray[$objectHash]['method']) {
                $this->registerArray[$objectHash] = [
                    'method' => $method
                ];    
            }
        } else {
            $this->registerArray[$objectHash]['method'] = $method;
        }
    }

    public function oneToMany($fromClass, $method, $toClass) {
        $this->relation = 'oneToMany';
        $iterator = $this->join($fromClass, $method, $toClass);

        return $iterator;
    }

    public function manyToOne($fromClass, $method, $toClass) {
        $this->relation = 'manyToOne';
        $iterator = $this->join($fromClass, $method, $toClass);
        
        return $iterator;
    }
    
    protected function join($fromClass, $method, $toClass)
    {
        $method = str_replace(['get', 'set'], '', $method);
        $md5Serialized = md5(serialize($fromClass));
        $objectHash = spl_object_hash($fromClass);
        
        $state = '';
        if (!isset ($this->relationArray[$objectHash])
            && !isset($this->relationArray[$objectHash][$method])
        ) {
            $state = 'init';
            $this->relationArray[$objectHash][$method]['state'] = $state;
        } else {
            $state = 'fetched';
            $this->relationArray[$objectHash][$method]['state'] = $state;
            
//            if (isset($this->relationArray[$objectHash][$method])) {
//                $state = $this->relationArray[$objectHash][$method]['state'];
//            } else { // some data has changed
//                $this->relationArray[$objectHash][$md5Serialized] = $this->relationArray[$objectHash][$md5Serialized]
//                $state = 
//                
//            }
        }
        
        $iterator = new RecordSetIterator([]);
//        $state = $this->relationArray[$objectHash][$method]['state'];
        if ('init' != $state) {
            $iterator = $this->relationArray[$objectHash][$method]['iterator'];
            
            return $iterator;
        }
        
        if (isset($this->registerArray[$objectHash]) && // set iterator if state == 'init'
            $method == $this->registerArray[$objectHash]['method']
        ) {
            $newClass = new $toClass();
            $joiner = new Joiner();

            $iterator = $this->getCreatedJoinIterator($fromClass, $joiner, $newClass);

            $this->relationArray[$objectHash][$method]['iterator'] = $iterator;
        }

        return $iterator;
    }

    /**
     * @param $fromClass
     * @param Joiner $joiner
     * @param $newClass
     * @return mixed
     */
    protected function getCreatedJoinIterator($fromClass, $joiner, $newClass)
    {
        $joiner->from($fromClass);
        if ('oneToMany' == $this->relation) {
            $joiner->joinOneToMany();
        }
        if ('manyToOne' == $this->relation) {
            $joiner->joinManyToOne();
        }
        
        $joiner->to($newClass);

        $iterator = new RecordSetIterator($joiner->fetch());

        return $iterator;
    }


}
