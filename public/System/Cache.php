<?php

require 'Cache/Base.php';
require 'Cache/Redis.php';

class Cache extends Base_Cache
{

    private $mCachePtr = null;

    public function __construct()
    {
    }

    public function Init()
    {
        global $Core;

        switch ($Core->Config->cache->type)
        {
            case 'redis':
                $this->mCachePtr = new Redis_Cache();
                $this->mCachePtr->connect();
                break;

            // TODO : include default Disk cache
            default:
                break;
        }
    }

    public function Get($pKey)
    {
        if($this->mCachePtr->IsAvailable())
            return $this->mCachePtr->Get($pKey);

        return null;
    }

    public function Set($pKey, $pValue, $pExpire = null)
    {
        if($this->mCachePtr->IsAvailable()) {
            $this->mCachePtr->Set($pKey, $pValue, $pExpire);
        }
    }

    public function Exist($pkey)
    {
        return ($this->mCachePtr->IsAvailable()) ? $this->mCachePtr->Exist($pkey) : false;
    }

    public function Remove($pKey)
    {
        if($this->mCachePtr->IsAvailable())
            $this->mCachePtr->Remove($pKey);
    }

    // Unused
    public function IsAvailable()
    {
        throw new Exception("Call unused IsAvailable function");
    }
}

$Core->push("Cache", new Cache());