<?php

abstract class Base_Cache
{
    // Get cache value
    abstract public function Get($pKey);

    // Set cache value
    abstract public function Set($pKey, $pValue, $pExpire = null);

    // Define if cache key exist
    abstract public function Exist($pkey);

    // Clear cache value
    abstract public function Remove($pKey);

    abstract public function IsAvailable();
}