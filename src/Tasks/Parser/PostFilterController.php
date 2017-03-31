<?php

namespace MyApp\src\Tasks\Parser;

use MyApp\src\PostFilter\PostFilter;
use MyApp\src\Tasks\Tasks;

class PostFilterController extends Tasks
{
    public function __construct()
    {
        parent::__construct();
    }

    public function start($inputString = '')
    {
        $postmanBulkEdit = $inputString;
        $postFilter = new PostFilter($postmanBulkEdit);
//        $this->postFilter->setOriginPostFilter($postmanBulkEdit);
        $postFilter->execute();
        $outputString = $postFilter->getPostFilterText();


        $template = 'PostFilter/' . strtolower(__FUNCTION__) . '/' . strtolower(__FUNCTION__);
        if ('post' == strtolower($_SERVER['REQUEST_METHOD'])) {
            $template .= '_output_rendered.twig';
        } else {
            $template .= '.twig';
        }

        echo $this->components->get('view')->render($template, array(
            'templateContext' => 'start',
            'inputString'     => $inputString,
            'outputString'    => $outputString,
        ));
    }

    public function entry()
    {

    }

    public function prepare()
    {

    }

    public function render()
    {

    }
}
