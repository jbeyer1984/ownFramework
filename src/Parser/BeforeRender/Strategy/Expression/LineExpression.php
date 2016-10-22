<?php


namespace MyApp\src\Parser\BeforeRender\Strategy\Expression;


class LineExpression extends ExpressionAbstract
{

    /**
     * @var array
     */
    protected $potentialVars;

    public function __construct()
    {
        $this->init();
    }

    public function init()
    {
        $this->potentialVars = [];
    }

    /**
     * @param  string|array $identifier
     */
    public function addToPotentialVars($identifier)
    {
        if (is_array($identifier)) {
            $this->potentialVars = array_merge(
                $this->potentialVars,
                $identifier
            );
            
            return;
        }
        $this->potentialVars[] = $identifier;
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
}