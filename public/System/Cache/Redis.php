<?php

// Redis Class cache

class Redis_Cache extends Base_Cache
{
    private $mPtr = null;
    private $mConnected = false;

    private $mHost = '';
    private $mPort = '';
    private $mAuth = '';

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

        if(property_exists($Core->Config->cache, 'connection') && property_exists($Core->Config->cache->connection, 'auth'))
            $this->mAuth = $Core->Config->cache->connection->auth;
        else
            $this->mAuth = '';

    }

    public function connect()
    {
        global $Core;

        if($Core->Config->cache->type != "redis")
            return false;

        $this->mPtr = new Redis();

        if(!$this->mPtr->connect($this->GetHost(), $this->GetPort()))
        {
            $Core->Logger->Write('Redis', 'Unable to connect to Redis server : '.$this->GetHost().':'.$this->GetPort().' abort');
            return false;
        }

        if($this->GetAuth() != '' && !$this->mPtr->auth($this->GetAuth()))
        {
            $Core->Logger->Write('Redis', 'Unable to Auth to Redis server : '.$this->GetHost().':'.$this->GetPort().' abort');
            return false;
        }

        $this->mConnected = true;

        return true;
    }

    public function Get($pKey)
    {
        if($this->IsAvailable())
            return $this->mPtr->get($pKey);

        return null;
    }

    public function Set($pKey, $pValue, $pExpire = null)
    {

        if($this->Exist($pKey))
            $this->Remove($pKey);

        if($this->IsAvailable())
            $this->mPtr->set($pKey, $pValue, $pExpire);
    }

    public function Exist($pKey)
    {
       if($this->IsAvailable())
           return $this->mPtr->exists($pKey) > 0;

       return false;
    }

    public function Remove($pKey)
    {
        if($this->IsAvailable())
            $this->mPtr->unlink($pKey);
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

    public function GetAuth()
    {
        return $this->mAuth;
    }
}