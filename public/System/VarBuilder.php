<?php

class VarBuilder
{
    public function __construct()
    {
    }

    public function BuildElement($pParent, $pElement, $pIndex, $pVars = array())
    {

        $pParent = (array)$pParent;

        if(!array_key_exists($pElement, $pParent))
            return;
 

        $lArray = $pParent[$pElement];

        if(is_string($lArray))
            $lStr = $lArray;
        else
        {
            $lArray = (array)$lArray;

            if(!array_key_exists($pIndex, $lArray))
                return;

            $lStr = $lArray[$pIndex];
        }
        
        foreach($pVars as $lKey => $lValue)
        {
            $lStr = str_replace('<'.$lKey.'>', $lValue, $lStr);
        }

        return $lStr;
    }
}

$Core->push('VarBuilder', new VarBuilder());

?>