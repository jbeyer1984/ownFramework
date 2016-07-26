<?php
/**
 * Created by jbeyer
 * Date: 26.07.2016
 * Time: 08:54
 */

namespace MyApp\src\Parser;


class FuncParameterParser
{

    /**
     * @var string
     */
    private $parametersString;

    /**
     * @var
     */
    private $identifiersArray;

    /**
     * @var string
     */
    private $stringOut;

    public function __construct()
    {
    }

    public function init()
    {
        $this->parametersString = "";
    }

    public function parse()
    {
        if (empty($this->parametersString)) {
            throw new Exception('$this->parametersString should not be empty');
        }

        $this->identifiersArray = $this->generatedIdentifiersArray();
        $this->stringOut = $this->builtArrayString($this->identifiersArray);
    }

    public function generatedIdentifiersArray()
    {
        $parameterString = $this->parametersString;

        $piecesComma = explode(',', $parameterString);

        $clearVars = [];
        foreach ($piecesComma as $key => $piece) {
            $pieceToClear = $piece;

            $posEqualSign = strpos($piece, '=');
            if (false !== $posEqualSign) {
                $pieceToClear = substr($piece, 0, $posEqualSign - 1);
            }

            $clearPiece = str_replace(' ', '', $pieceToClear);
            $clearPiece = str_replace('$', '', $clearPiece);

            $clearVars[] = $clearPiece;
        }

        return $clearVars;
    }

    public function builtArrayString($identifiersArray)
    {
        $arrayString = '';

        $elements = array_map(function ($identifier) {
            return '    ' . "'" . $identifier . "'" . ' => ' . '$' . $identifier . "," . "\n";
        }, $identifiersArray);

        $arrayString =
            '[' . "\n"
            . implode('', $elements)
            . ']'
        ;

        return $arrayString;
    }

    /**
     * @return string
     */
    public function getParametersString()
    {
        return $this->parametersString;
    }

    /**
     * @param string $parametersString
     * @return $this
     */
    public function setParametersString($parametersString)
    {
        $this->parametersString = $parametersString;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getIdentifiersArray()
    {
        return $this->identifiersArray;
    }

    /**
     * @param mixed $identifiersArray
     * @return $this
     */
    public function setIdentifiersArray($identifiersArray)
    {
        $this->identifiersArray = $identifiersArray;

        return $this;
    }

    /**
     * @return string
     */
    public function getStringOut()
    {
        return $this->stringOut;
    }

    /**
     * @param string $stringOut
     * @return $this
     */
    public function setStringOut($stringOut)
    {
        $this->stringOut = $stringOut;

        return $this;
    }
}