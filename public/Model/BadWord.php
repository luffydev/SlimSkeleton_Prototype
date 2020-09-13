<?php

include(dirname(__DIR__).'/Lib/Gibberish/Gibberish.class.php');

class BadWord_Model
{

    private $mWordList = array();

    public function __construct()
    {
        $this->loadBadWords();
    }

    public function loadBadWords()
    {
        global $Core;
        
        $lResult = $Core->Database->QueryAll("SELECT * FROM word_test WHERE enabled = 1");

        if(!$lResult)
            return;

        foreach($lResult as $lKey => $lObject)
            $this->mWordList[] = $lObject->word;
    }

    public function GetScoredComment()
    {
        global $Core;

        $lBadWordList = implode('* ', $this->mWordList).'* ';

        $lResult = $Core->Database->ExecAll("SELECT MATCH(commentary.`text`) AGAINST('( ".$lBadWordList." )' IN BOOLEAN MODE) score, id, TEXT FROM commentary 
                                             ORDER BY score");

        
        return $lResult;
    }

    public function parseText($pText)
    {
        $lBadWordList = implode('|', $this->mWordList);
        preg_match_all('~\b('.strtolower($lBadWordList).')\b~i', strtolower($pText), $lMatches);

        return array('wordMatch' => sizeof($lMatches[0]));
    }

    public function GibberishTest($pText)
    {
        $lGibberishDir = dirname(__FILE__).'/../Lib/Gibberish/phrases/matrix.txt';

        return array('isGibberish' => boolval(Gibberish::test($pText, $lGibberishDir)));
    }


    public function getBadwords()
    {
        return $this->mWordList;
    }
}

?>