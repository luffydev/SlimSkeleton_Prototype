<?php

class MemorySharing
{
    public function __construct()
    {
        $GLOBALS['var_share'] = new stdClass;
    }

    public function set($pKey, $pVal)
    {
        $GLOBALS['var_share']->$pKey = $pVal;
    }

    public function get($pKey)
    {
        if($this->exist($pKey))
            return $GLOBALS['var_share']->$pKey;

        return null;
    }

    public function exist($pKey)
    {
        return property_exists($GLOBALS['var_share'], $pKey);
    }
}

$Core->push('variables', new MemorySharing());

?>