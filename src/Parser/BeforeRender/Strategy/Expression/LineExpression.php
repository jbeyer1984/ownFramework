<?php


namespace MyApp\src\Parser\BeforeRender\Strategy\Expression;


class LineExpression extends ExpressionAbstract
{

    /**
     * @var
     */
    protected $line;

    /**
     * @var array
     */
    protected $potentialVars;

    /**
     * @var
     */
    protected $potentialAssignments;

    public function __construct()
    {
        $this->init();
    }

    public function init()
    {
        $this->potentialVars = [];
        $this->potentialAssignments = [];
    }

    /**
     * @param  string|array $identifier
     */
    public function addToPotentialVars($identifier)
    {
        if (is_array($identifier)) {
//            $this->potentialVars = array_merge(
//                $this->potentialVars,
//                $identifier
//            );
        $this->potentialVars[] = array_unshift($identifier, $this->potentialVars);

            return;
        }
    }

    /**
     * @param  string|array $identifier
     */
    public function addToPotentialAssignments($identifier)
    {
        if (is_array($identifier)) {
//            $this->potentialAssignments = array_merge(
//                $this->potentialAssignments,
//                $identifier
//            );
            
            $dump = print_r("WTF", true);
            error_log(PHP_EOL . '-$- in ' . basename(__FILE__) . ':' . __LINE__ . ' in ' . __METHOD__ . PHP_EOL . '*** "WTF" ***' . PHP_EOL . " = " . $dump . PHP_EOL);
            
            

            return;
        }

        array_push($this->potentialAssignments, $identifier);
//        $dump = print_r($this->potentialAssignments, true);
//        error_log(PHP_EOL . '-$- in ' . basename(__FILE__) . ':' . __LINE__ . ' in ' . __METHOD__ . PHP_EOL . '*** $this->potentialAssignments ***' . PHP_EOL . " = " . $dump . PHP_EOL);
        
    }

    /**
     * @return array
     */
    public function getPotentialVars()
    {
        return $this->potentialVars;
    }

    /**
     * @param array $potentialVars
     * @return LineExpression
     */
    public function setPotentialVars($potentialVars)
    {
        $this->potentialVars = $potentialVars;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPotentialAssignments()
    {
        return $this->potentialAssignments;
    }

    /**
     * @param mixed $potentialAssignments
     * @return LineExpression
     */
    public function setPotentialAssignments($potentialAssignments)
    {
        $this->potentialAssignments = $potentialAssignments;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getLine()
    {
        return $this->line;
    }

    /**
     * @param mixed $line
     * @return LineExpression
     */
    public function setLine($line)
    {
        $this->line = $line;

        return $this;
    }
}