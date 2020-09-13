<?php

require dirname(__DIR__).'/../Lib/Cache/SSDB/SSDB.php';

class SSDB_Cache extends Base_Cache
{
    private $mPtr = null;
    private $mConnected = false;

    public function __construct()
    {
        global $Core;

        if(property_exists($Core->Config->cache, 'connection') && property_exists($Core->Config->cache->connection, 'host'))
            $this->mHost = $Core->Config->cache->connection->host;
        else
            $this->mHost = 'undefined';

        if(property_exists($Core->Config->cache, 'connection') && property_exists($Core->Config->cache->connection, 'port'))
            $this->mPort = $Core->Config->cache->connection->port;
        else
            $this->mPort = 0;

    }

    public function connect()
    {

        global $Core;

        try
        {
            $this->mPtr = new SSDB($this->GetHost(), $this->GetPort());
            $this->mConnected = true;

        }catch(Exception $pException)
        {
            $this->mConnected = false;
            $Core->Logger->Write('SSDB', 'Unable to connect to SSDB server : '.$this->GetHost().':'.$this->GetPort().' abort');
        }
    }

    public function Get($pKey)
    {
        if($this->IsAvailable())
        {

            $lResponse = $this->mPtr->get($pKey);

            if(property_exists($lResponse, 'data')) {

                if(is_serialized($lResponse->data))
                    $lResponse->data = unserialize($lResponse->data);

                return $lResponse->data;
            }

            return $lResponse;
        }


        return null;
    }

    public function Set($pKey, $pValue, $pExpire = null)
    {
        if($this->IsAvailable()) {

            if(is_object($pValue))
                $pValue = serialize($pValue);

            if(!$pExpire)
                $this->mPtr->set($pKey, $pValue);
            else
                $this->mPtr->setx($pKey, $pValue, $pExpire);
        }

    }

    public function Exist($pKey)
    {
        return ($this->IsAvailable()) ? $this->mPtr->exists() : false;
    }

    public function Remove($pKey)
    {
        if($this->IsAvailable())
            return $this->mPtr->del($pKey);

        return false;
    }

    public function IsAvailable()
    {
        return $this->mConnected;
    }

    public function GetHost()
    {
        return $this->mHost;
    }

    public function GetPort()
    {
        return $this->mPort;
    }


}