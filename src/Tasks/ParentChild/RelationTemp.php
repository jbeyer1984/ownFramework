<?php

namespace MyApp\src\Tasks\ParentChild;

class RelationTemp
{
    /**
     * @var array
     */
    private $temp;

    /**
     * @var array
     */
    private $template;
    
    public function __construct()
    {
    }

    /**
     * @return array
     */
    public function getTemp()
    {
        return $this->temp;
    }

    /**
     * @param array $temp
     * @return RelationTemp
     */
    public function setTemp($temp)
    {
        $this->temp = $temp;

        return $this;
    }

    /**
     * @return array
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * @param array $template
     * @return RelationTemp
     */
    public function setTemplate($template)
    {
        $this->template = $template;

        return $this;
    }
}
