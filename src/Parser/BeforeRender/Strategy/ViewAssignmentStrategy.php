<?php


namespace MyApp\src\Parser\BeforeRender\Strategy;

use MyApp\src\Parser\BeforeRender\Strategy\Expression\Assignment\AssignmentVar;
use MyApp\src\Parser\BeforeRender\Strategy\Expression\Expression;
use MyApp\src\Parser\BeforeRender\Strategy\Expression\LineExpression;
use MyApp\src\Parser\BeforeRender\Wrapper\AbstractWrapper;
use MyApp\src\Parser\BeforeRender\Wrapper\Text\VariableText;

class ViewAssignmentStrategy extends AbstractParserStrategy
{

    /**
     * @var array
     */
    private $viewArray;

    /**
     * @var array
     */
    private $lineReferenceView;

    /**
     * @var array
     */
    private $renderLinesArray;

    public function __construct()
    {
    }

    /**
     * @return AbstractParserStrategy
     */
    public static function initialized()
    {
        $self = new self();
        $self->init();

        return $self;
    }

    /**
     * @return $this
     */
    protected function init()
    {
        parent::init();

        $this->viewArray = [];
        $this->lineReferenceView = [];
        $this->renderLinesArray = [];

        return $this;
    }

    /**
     * @param array [string] $allLines
     */
    public function buildParserArrays($allLines)
    {
        $viewArray = $this->getViewArray(); // is like [ varOne => [ [0] => 4 ]]
        $lineReferenceView = $this->getLineReferenceView(); // is like [ 4 => varOne ]

        $renderCount = 0;

        /**
         * crated because of lines to check for equality
         */
        $storedIdentifiersArray = [];
        
        $eachLineImploded = [];

        $markerArray = [
            'assignment_var' => [],
            'count_var' => [],
            'overwrite_var' => [], 
            'connection' => [], 
        ];
        foreach ($allLines as $lineNum => $line) {
            if (empty(trim($line))) {
                continue;
            }
            
//            $dump = print_r($line, true);
//            error_log(PHP_EOL . '-$- in ' . basename(__FILE__) . ':' . __LINE__ . ' in ' . __METHOD__ . PHP_EOL . '*** $line ***' . PHP_EOL . " = " . $dump . PHP_EOL);
            
            // create expression tree for each line
            $lineExpression = new LineExpression();
            $lineExpression->setLine(trim($line));
            
            $expression = new Expression();
            $expression->setLineExpression($lineExpression);
            
            if (false !== strpos($line, '=')) {
                $expression->setLeft($line);
            } else {
                $expression->setRight($line);
            }

            $expressionTree = new Expression();
            $expression->setChild($expressionTree);  // build link backward
            $expressionTree->linkParent($expression);
            $expressionTree->evaluate();

            $potentialAssignments = $expression
                ->getLineExpression()
                ->getPotentialAssignments()
            ;
            
            
            
            if (empty($potentialAssignments)) { /** @todo jens | check other conditions later */
                continue;
            }
            
            
//            $identifierArray = array_map( function ($assignmentVar) {
//                $operator = $assignmentVar->getOperator();
//                $assignmentExpression = $assignmentVar->getVar();
//                if (in_array($operator, [
//                    AssignmentVar::OPERATOR_EQUAL,
//                    AssignmentVar::OPERATOR_DELEGATE, 
//                ])) {
//                    $assignmentExpression = $assignmentVar->getVar() . $operator;
//                }
//                
//                return trim($assignmentExpression);
//            }, $potentialAssignments);
            
            if ($expressionTree->isAssignment()) {
//                $identifierArray = array_map( function ($assignmentVar) {
//                    $assignmentExpression = $assignmentVar->getVar();
//                    return trim($assignmentExpression);
//                }, $potentialAssignments);
                $identifierArray = array_map( function ($assignmentVar) {
                    $operator = $assignmentVar->getOperator();
                    $assignmentExpression = $assignmentVar->getVar();
                    if (in_array($operator, [
                        AssignmentVar::OPERATOR_EQUAL,
                        AssignmentVar::OPERATOR_DELEGATE,
                    ])) {
                        $assignmentExpression = $assignmentVar->getVar() . $operator;
                    }

                    return trim($assignmentExpression);
                }, $potentialAssignments);
                
                list($markerArray) = $this->handleAssignment($identifierArray, $markerArray, $lineNum, $eachLineImploded);
            } else { // implode current identifiers and search for same
                $identifierArray = array_map( function ($assignmentVar) {
                    $operator = $assignmentVar->getOperator();
                    $assignmentExpression = $assignmentVar->getVar();
                    if (in_array($operator, [
                        AssignmentVar::OPERATOR_EQUAL,
                        AssignmentVar::OPERATOR_DELEGATE,
                    ])) {
                        $assignmentExpression = $assignmentVar->getVar() . $operator;
                    }

                    return trim($assignmentExpression);
                }, $potentialAssignments);
                
                list($markerArray, $eachLineImploded) = $this->handleStatement($identifierArray, $storedIdentifiersArray, $lineNum, $markerArray, $eachLineImploded, $expression);    
            }
            
            $storedIdentifiersArray[] = $identifierArray;
        }
        
        $dump = print_r($markerArray, true);
        error_log(PHP_EOL . '-$- in ' . basename(__FILE__) . ':' . __LINE__ . ' in ' . __METHOD__ . PHP_EOL . '*** $markerArray ***' . PHP_EOL . " = " . $dump . PHP_EOL);
        

//    $this->setViewArray($viewArray);
//    $this->setLineReferenceView($lineReferenceView);
    }

    /**
     * @param $identifierArray
     * @param $markerArray
     * @param $lineNum
     * @param $eachLineImploded
     * @return array
     */
    protected function handleAssignment($identifierArray, $markerArray, $lineNum, $eachLineImploded)
    {
        $oneIdentifierArray = $identifierArray[0]; // gets whole assignment state before =

        // check override
        if (in_array($oneIdentifierArray, $markerArray['assignment_var'])) {
            $markerArray['overwrite_var'][$oneIdentifierArray][] = $lineNum;
        }

        // check not override but assignment, could also be an implicit override
        if (!in_array($oneIdentifierArray, $markerArray['assignment_var'])) {
            // solution to know if $var has been overwritten
//            $lenAssignmentVarArray = count($markerArray['assignment_var']); 
//            for ($i = $lenAssignmentVarArray-1; -1 < $i; $i--) {
//                $assignmentVar = $markerArray['assignment_var'][$i];
//                if (false !== strpos($assignmentVar, $oneIdentifierArray)) {
//                    if (isset($markerArray['count_var'][$oneIdentifierArray])) {
//                        $lastIndex = count($markerArray['count_var'][$oneIdentifierArray]) - 1;
//                        $markerArray['overwrite_var'][$oneIdentifierArray][] = [
//                            'line' => $lineNum,
//                            'overwritten_statement' => $assignmentVar,
//                            'overwritten_line' => $lastIndex
//                        ];    
//                    } elseif(isset($markerArray['count_var'][$assignmentVar])) {
//                        $lastIndex = count($markerArray['count_var'][$assignmentVar]) - 1;
//                        $markerArray['overwrite_var'][$assignmentVar][] = [
//                            'line' => $lineNum,
//                            'overwritten_statement' => $assignmentVar,
//                            'overwritten_line' => $lastIndex
//                        ];
//                    }
//                    
//                }
//            }
            
//                    $pieces = [];
            $matches = [];
            preg_match('/.*(->|=)/', $oneIdentifierArray, $matches); // greedy
            $dump = print_r($matches, true);
            error_log(PHP_EOL . '-$- in ' . basename(__FILE__) . ':' . __LINE__ . ' in ' . __METHOD__ . PHP_EOL . '*** $matches ***' . PHP_EOL . " = " . $dump . PHP_EOL);
            if (isset($matches[1])) {
                $matchOperator = $matches[1];
                $strippedAssignment = str_replace($matchOperator, '', $matches[0]);
                // search backward through imploded lines
                $lenEachLineImploded = count($eachLineImploded);
                for ($i = $lenEachLineImploded - 1; 0 < $i; $i--) {
                    $lineToCheck = $eachLineImploded[$i]['assignment_imploded'];
                    if (false !== strpos($lineToCheck, $strippedAssignment)) {
                        $dump = print_r("found IMPLICIT override", true);
                        error_log(PHP_EOL . '-$- in ' . basename(__FILE__) . ':' . __LINE__ . ' in ' . __METHOD__ . PHP_EOL . '*** "found IMPLICIT override" ***' . PHP_EOL . " = " . $dump . PHP_EOL);
                        if (!isset($markerArray['overwrite_var'][$strippedAssignment])) {
                            $markerArray['overwrite_var'][$strippedAssignment] = [];
                        }
                        $markerArray['overwrite_var'][$strippedAssignment][] = [
                            'line' => $lineNum,
                            'overwritten_statement' => $lineToCheck,
                            'overwritten_line' => $i
                        ];
                        break;
                    }
                }
            }


            $markerArray['assignment_var'][] = $oneIdentifierArray;


        }

        if (in_array($oneIdentifierArray, $markerArray['assignment_var'])) {
            $markerArray['count_var'][$oneIdentifierArray][] = $lineNum;

            return array($markerArray);
        }

        return array($markerArray);
    }

    /**
     * @param $identifierArray
     * @param $storedIdentifiersArray
     * @param $lineNum
     * @param $markerArray
     * @param $eachLineImploded
     * @param $expression
     * @return array
     */
    protected function handleStatement($identifierArray, $storedIdentifiersArray, $lineNum, $markerArray, $eachLineImploded, $expression)
    {
        $implodedIdentifier = implode('-', $identifierArray);
//                $implodedIdentifier = str_replace('=-', '-', $implodedIdentifier);
//                $implodedIdentifier = str_replace('->-', '-', $implodedIdentifier);
//                $implodedIdentifier = str_replace('->', '', $implodedIdentifier);
        $dump = print_r($implodedIdentifier, true);
        error_log(PHP_EOL . '-$- in ' . basename(__FILE__) . ':' . __LINE__ . ' in ' . __METHOD__ . PHP_EOL . '*** $implodedIdentifier ***' . PHP_EOL . " = " . $dump . PHP_EOL);

        $lenStoredIdentifiersArray = count($storedIdentifiersArray);
        for ($i = $lenStoredIdentifiersArray - 1; -1 < $i; $i--) { // search in stored identifiers
            $identifierArrayToCompare = $storedIdentifiersArray[$i];
            // array check new o-o old
            $diffArrayNewToOld = array_diff($identifierArray, $identifierArrayToCompare);

            $dump = print_r($diffArrayNewToOld, true);
            error_log(PHP_EOL . '-$- in ' . basename(__FILE__) . ':' . __LINE__ . ' in ' . __METHOD__ . PHP_EOL . '*** $diffArrayNewToOld ***' . PHP_EOL . " = " . $dump . PHP_EOL);

            if (0 == count($diffArrayNewToOld)) {

                $markerArray['connection'][] = [
                    'from' => $lineNum,
                    'to' => $i+1, // because of difference between $i and $lineNum
                ];

                break;
            } else {
                $diffArrayOldToNew = array_diff($identifierArrayToCompare, $identifierArray);

                $dump = print_r($diffArrayOldToNew, true);
                error_log(PHP_EOL . '-$- in ' . basename(__FILE__) . ':' . __LINE__ . ' in ' . __METHOD__ . PHP_EOL . '*** $diffArrayOldToNew ***' . PHP_EOL . " = " . $dump . PHP_EOL);

                if (0 == count($diffArrayOldToNew)) {
                    $markerArray['connection'][] = [
                        'from' => $lineNum,
                        'to' => $i+1,
                    ];
                }
                
                break;
            }
        }

        // used for implicit assignment
        $eachLineContainer = [
            'line' => $lineNum,
            'assignment_imploded' => $implodedIdentifier,
        ];
        $eachLineImploded[] = $eachLineContainer; // used for implicit assignment search


        if (in_array($identifierArray, $markerArray['count_var'])) {
            $markerArray['count_var'][$implodedIdentifier][] = $expression->getLineExpression()->getLine();

            return array($markerArray, $eachLineImploded);
        }

        return array($markerArray, $eachLineImploded);
    }

    /**
     * @param string $varName
     * @param int $lineNum
     * @param AbstractWrapper $wrapper
     */
    public function wrapVar($varName, $lineNum, AbstractWrapper $wrapper)
    {
        $variableText = VariableText::initialized();
        $variableText->setIdentifier($varName);

        $variableText->addWrapper($wrapper);
        $variableText->apply();

        $allLines = $this->getAllLines();
//    $allLines[$lineNum] = str_replace(
//      '$this->view->' . $varName,
//      '$this->view->' . $variableText->getManipulatedString(),
//      $allLines[$lineNum]
//    );

        $this->setAllLines($allLines);
    }

    /**
     * @return array
     */
    public function getViewArray()
    {
        return $this->viewArray;
    }

    /**
     * @param array $viewArray
     * @return AbstractParserStrategy
     */
    public function setViewArray($viewArray)
    {
        $this->viewArray = $viewArray;

        return $this;
    }

    /**
     * @return array
     */
    public function getLineReferenceView()
    {
        return $this->lineReferenceView;
    }

    /**
     * @param array $lineReferenceView
     * @return AbstractParserStrategy
     */
    public function setLineReferenceView($lineReferenceView)
    {
        $this->lineReferenceView = $lineReferenceView;

        return $this;
    }

    /**
     * @return array
     */
    public function getRenderLinesArray()
    {
        return $this->renderLinesArray;
    }

    /**
     * @param array $renderLinesArray
     * @return ViewParserStrategy
     */
    public function setRenderLinesArray($renderLinesArray)
    {
        $this->renderLinesArray = $renderLinesArray;

        return $this;
    }
}