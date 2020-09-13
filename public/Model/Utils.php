<?php

class Utils_Model
{
    public function __contruct()
    {
    }
    public function ArraySearchKeyValue($array, $key, $value)
    {
        $results = array();
    
        if (is_array($array)) {
            if (isset($array[$key]) && $array[$key] == $value) {
                $results[] = $array;
            }
    
            foreach ($array as $subarray) {
                $results = array_merge($results, $this->ArraySearchKeyValue($subarray, $key, $value));
            }
        }
    
        return $results;
    }
    public function checkHasURL($pText)
    {
        $lRegExp =  "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";

        preg_match_all($lRegExp, $pText, $lResult);

        var_dump($lResult[0]);
    }
}

?>