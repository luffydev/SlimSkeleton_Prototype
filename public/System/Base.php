<?php


// we load our system
class System extends stdClass
{
    private $mContext;
    private $mApplication;
    private $mRoute = '';

    public function __construct()
    {
    }
    public function push($pName, $pObject)
    {
        $this->$pName = $pObject;
    }

    public function setApplication($pApplication)
    {
        $this->mApplication = $pApplication;
    }

    public function getApplication()
    {
        return $this->mApplication;
    }

    public function setContext($pContext)
    {
        $this->mContext = $pContext;
    }

    public function getContext()
    {
        return $this->mContext;
    }

    public function setCurrentRoute($pRoute)
    {
        $this->mRoute = $pRoute;
    }

    public function getCurrentRoute()
    {
        return $this->mRoute;
    }
}

$Core = new System();
$lList = glob('System/*');

foreach($lList as $lI => $lObject)
{
    if(is_dir($lObject))
    {
        $lFiles = glob('System/'.$lObject.'/*.php');

        foreach($lFiles as $lJ => $lFile)
        {
            include dirname(__DIR__).'/'.$lObject.'/'.$lFile;
        }
    }else
    {

        if(!strstr($lObject, 'Base'))
            include dirname(__DIR__).'/'.$lObject;
    }
}