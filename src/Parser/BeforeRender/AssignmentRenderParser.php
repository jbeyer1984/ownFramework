<?php


namespace MyApp\src\Parser\BeforeRender;

use MyApp\src\Parser\BeforeRender\Strategy\AbstractParserStrategy;
use MyApp\src\Parser\BeforeRender\Strategy\ViewAssignmentStrategy;
use MyApp\src\Parser\BeforeRender\Template\StrategyParserAssignmentTemplate;

class AssignmentRenderParser
{

    public function __construct()
    {
    }

    /**
     * @param string $text
     * @return string
     */
    public function parseStrategyTemplates($text)
    {
        $strategyParserTemplate = new StrategyParserAssignmentTemplate();

        /** @var AbstractParserStrategy $viewAssignmentParserStrategy */
        $viewAssignmentParserStrategy = ViewAssignmentStrategy::initialized();
        $allLines = $viewAssignmentParserStrategy->explodeText($text);

        $viewAssignmentParserStrategy->setAllLines($allLines);

        $strategyParserTemplate->parse($viewAssignmentParserStrategy);      

        return $viewAssignmentParserStrategy->getOutputText();
    }
}