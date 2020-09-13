<?php


class Disk_Cache extends Base_Cache
{
    private $mBaseDir = '';

    public function __construct()
    {
        $this->mBaseDir = dirname(__DIR__).'/../Cache';
    }

    public function Get($pKey)
    {
        $lFile = $this->mBaseDir.'/'.$pKey.'.cache';

        if(file_exists($lFile))
        {
            $lContent = unserialize(file_get_contents($this->mBaseDir.'/'.$pKey.'.cache'));

            if(!property_exists($lContent, 'expire'))
                return null;

            if($lContent->expire != 0 && $lContent->expire < time())
                return null;

            if(property_exists($lContent, 'data'))
                return unserialize($lContent->data);
        }

        return null;
    }

    public function Set($pKey, $pValue, $pExpire = null)
    {
        $lContent = new stdClass();
        $lContent->expire = 0;

        if($pExpire != null)
        {
            $lDate = new DateTime();
            $lDate->setTimestamp(time());

            $lDate->modify("+".$pExpire." second");
            $lContent->expire = $lDate->getTimestamp();
        }

        $lContent->data = serialize($pValue);

       file_put_contents($this->mBaseDir.'/'.$pKey.'.cache', serialize($lContent));
    }

    public function IsAvailable()
    {
        return true;
    }

    public function Remove($pKey)
    {
       if(file_exists($this->mBaseDir.'/'.$pKey.'.cache'))
           unlink($this->mBaseDir.'/'.$pKey.'.cache');
    }

    public function Exist($pKey)
    {

        if(file_exists($this->mBaseDir.'/'.$pKey.'.cache'))
        {
            $lContent = unserialize(file_get_contents($this->mBaseDir.'/'.$pKey.'.cache'));

            return ($lContent->expire == 0 || $lContent->expire > time());
        }

        return false;
    }


}
