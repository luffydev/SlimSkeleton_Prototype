<?php

use \Firebase\JWT\JWT;

class Blog_Model
{
    public function __construct()
    {
    }
    public function GetArticleFromID($pID)
    {
        global $Core;
        $lArticle = $Core->Database->Query("SELECT * FROM articles WHERE id = :id", array(':id' => $pID));
        if (!$lArticle)
            return false;
            if(empty($lArticle->img)){
                $lArticle->img = "https://picsum.photos/600/400";
            }
        return $lArticle;
    }
    public function ListLatestArticles($pLimit = '')
    {
        $sql_limit = '';
        if (!empty($pLimit)) {
            $sql_limit = "LIMIT $pLimit";
        }
    
        global $Core;
        $lRelated = $Core->Database->QueryAll("SELECT * FROM articles ORDER BY id DESC $sql_limit");
        if (!$lRelated)
            return false;
        
            foreach ($lRelated as $key => &$value) {
                if(empty($value->img)){
                    $value->img = "https://picsum.photos/600/400";
                }
                
                
            }

        return $lRelated;
    }
    public function ListRelativesArticles($pLimit = '', $pID = '')
    {
        $sql_limit = '';
        if (!empty($pLimit)) {
            $sql_limit = "LIMIT $pLimit";
        }
    
        global $Core;
        $lRelated = $Core->Database->QueryAll("SELECT * FROM articles WHERE id != :id ORDER BY id DESC $sql_limit", array(':id' => $pID));
        if (!$lRelated)
            return false;
        
            foreach ($lRelated as &$value) {
                if(empty($value->img)){
                    $value->img = "https://picsum.photos/600/400";
                }
                
                
            }
            
        return $lRelated;
    }
}
