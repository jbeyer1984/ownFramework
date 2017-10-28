<?php

namespace MyApp\src\Tasks\ParentChild;

class DocOrmParser
{
    /**
     * @var ModelDbInterface
     */
    private $model;

    /**
     * @var array
     */
    private $docOrmMethodAttributes;
    
    public function __construct(ModelDbInterface $model)
    {
        $this->model = $model;
        $this->init();
    }

    protected function init()
    {
        $this->docOrmMethodAttributes = [];
    }

    public function parse()
    {
        $model = $this->model;
        
        $childReflected = new ExtendedReflectionClass($model);
        $methods        = $childReflected->getMethods();
        $useStatements  = $childReflected->getUseStatements();
        
        foreach ($methods as $method) {
            $doc = $childReflected->getMethod($method->name)->getDocComment();
            if (!empty($doc) && false !== strpos($doc, '@myOrm')) {
                $this->parseOrm($doc, $childReflected, $method);
            }
        }
    }

    /**
     * @param $doc
     * @param $childReflected
     * @param $method
     */
    protected function parseOrm($doc, ExtendedReflectionClass $childReflected, $method)
    {
        preg_match_all('/\@myOrm\|(.*?)\n/', $doc, $annotations);
        if ('a:2:{i:0;a:0:{}i:1;a:0:{}}' != serialize($annotations)) { // empty [[],[]]
            $docToParseArray = array_pop($annotations);
            foreach ($docToParseArray as $docToParse) {
                $docOrmParser = new DocOrmAttributes($docToParse);
                $docOrmParser->parse();
                $command = $docOrmParser->getCommand();
                $tuple = $docOrmParser->getTupleValues();
                if (isset($tuple['targetEntity'])) {
                    $targetEntity = $tuple['targetEntity'];
                    $nsClass      = $this->getParsedNsClassName($childReflected, $targetEntity);
                    $setMethodName = 'set' . str_replace(['set', 'get'], '', $method->name);

                    $this->docOrmMethodAttributes[$command][$nsClass] = [
                         'setMethodName' => $setMethodName
                    ];
                }
            }
        }
    }

    /**
     * @param ExtendedReflectionClass $childReflected
     * @param $targetEntity
     * @return string
     */
    protected function getParsedNsClassName(ExtendedReflectionClass $childReflected, $targetEntity)
    {
        $useStatements  = $childReflected->getUseStatements();
        $foundNamespace = $childReflected->getNamespaceName();
        $foundNamespace = str_replace("\\", '_', $foundNamespace);
        foreach ($useStatements as $useStatement) {
            $useClass = '';
            if (isset($useStatement['class'])) {
                $useClass = $useStatement['class'];
            }
            if (!empty($useClass)
                && false !== strpos($useStatement['class'], $targetEntity))
            {
                $classAtEnd = basename($useStatement['class']);
                if ($targetEntity == $classAtEnd) {
                    $namespace         = $useStatement['class'];
                    $explodedNamespace = explode("\\", $namespace);
                    array_pop($explodedNamespace);
                    $foundNamespace = implode('_', $explodedNamespace);
                }
            }
        }
        $nsClass = $foundNamespace . '__' . $targetEntity;

        return $nsClass;
    }

    /**
     * @return array
     */
    public function getDocOrmMethodAttributes()
    {
        return $this->docOrmMethodAttributes;
    }
}