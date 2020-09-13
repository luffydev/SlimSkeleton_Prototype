<?php

use \Firebase\JWT\JWT;

class User_Model
{
    public function __construct()
    {
    }

    public function GetUserFromID($pID)
    {
        global $Core;

        $lAccount = $Core->Database->Query("SELECT id, username, email FROM users WHERE id = :id", array(':id' => $pID));

        if(!$lAccount)
            return false;

        return $lAccount;    
    }

    public function BuildSession($pUserID)
    {
        global $Core;

        $lAccount = $Core->Database->Query("SELECT id, username, email FROM users WHERE id = :id", array(':id' => $pUserID));

        if(!$lAccount)
            return false;

        $lDateTime = new DateTime();

        $lDateTime->setTimestamp(time());
        $lDateTime->modify($Core->Config->session->expire);
            
        $lPayload = array(  'id' => $lAccount->id,
                            'username' => $lAccount->username,
                            'email' => $lAccount->email,
                            'expire' => $lDateTime->getTimestamp() );    

        if($Core->Config->session->key_type == "rsa")
            $lToken = JWT::encode($lPayload, $this->GetPrivateKey(), 'RS256');
        else
            $lToken = JWT::encode($lPayload, $Core->Config->session->key);

        

        return $lToken;
    }

    public function CheckToken($pToken)
    {
        global $Core;

        if($Core->Config->session->key_type == "rsa")
            $lPayload = JWT::decode($pToken, $this->GetPublicKey(), array('RS256'));
        else    
            $lPayload = JWT::decode($pToken, $Core->Config->session->key, array('HS256'));

        if(!$lPayload)
            return false;

        if(property_exists($lPayload, "expire") && $lPayload->expire < time())
            return false;

        return $lPayload;
    }

    public function CheckCredential($pUsername, $pPassword)
    {
        global $Core;

        $pPassword = $this->EncryptPassword($pPassword, $Core->Config->session->password_salt);

        $lResult = $Core->Database->Query("SELECT id FROM users WHERE username = :username AND password = :password", 
                                          array(':username' => $pUsername, ':password' => $pPassword));

        if(!$lResult)
            return false;

        return $lResult;
    }

    public function EncryptPassword($pPassword, $pSalt)
    {
        return sha256($pPassword.':'.$pSalt);
    }

    public function GetPrivateKey()
    {

        $lFile = dir(__DIR__).'/../keys/key.priv';

        if(!file_exists($lFile))
            return '';

        $lPrivateKey = file_get_contents($lFile);

        return $lPrivateKey;
    }

    public function GetPublicKey()
    {
        $lFile = dir(__DIR__).'/../keys/key.pub';

        if(!file_exists($lFile))
            return '';

        $lPublicKey = file_get_contents($lFile);

        return $lPublicKey;
    }
}

?>