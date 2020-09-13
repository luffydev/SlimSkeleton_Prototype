<?php

class Utils_Model
{
    public function __contruct()
    {
    }

    public function checkHasURL($pText)
    {
        $lRegExp =  "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";

        preg_match_all($lRegExp, $pText, $lResult);

        var_dump($lResult[0]);
    }
}

?>