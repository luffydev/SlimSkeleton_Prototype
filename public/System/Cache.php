<?php

require 'Cache/Base.php';

require 'Cache/Redis.php';
require 'Cache/SSDB.php';
require 'Cache/Disk.php';

class Cache extends Base_Cache
{

    private $mCachePtr = null;

    public function __construct()
    {
    }

    public function Init()
    {
        global $Core;

       $lIsDisk = false;

        switch ($Core->Config->cache->type)
        {
            case 'redis':
                $this->mCachePtr = new Redis_Cache();
                $this->mCachePtr->connect();
            break;

            case 'ssdb':
                $this->mCachePtr = new SSDB_Cache();
                $this->mCachePtr->connect();
            break;

            default:
                $this->mCachePtr = new Disk_Cache();

            break;
        }

        if($lIsDisk || !$this->mCachePtr || !$this->mCachePtr->IsAvailable())
            $this->mCachePtr = new Disk_Cache();
    }

    public function Get($pKey)
    {
        if($this->mCachePtr && $this->mCachePtr->IsAvailable())
            return $this->mCachePtr->Get($pKey);

        return null;
    }

    public function Set($pKey, $pValue, $pExpire = null)
    {
        if($this->mCachePtr &&  $this->mCachePtr->IsAvailable()) {
            $this->mCachePtr->Set($pKey, $pValue, $pExpire);
        }
    }

    public function Exist($pkey)
    {
        return ($this->mCachePtr && $this->mCachePtr->IsAvailable()) ? $this->mCachePtr->Exist($pkey) : false;
    }

    public function Remove($pKey)
    {
        if($this->mCachePtr && $this->mCachePtr->IsAvailable())
            $this->mCachePtr->Remove($pKey);
    }

    // Unused
    public function IsAvailable()
    {
        throw new Exception("Call unused IsAvailable function");
    }
}

$Core->push("Cache", new Cache());