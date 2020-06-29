<?php

require 'Database/ORM.php';

use PhpMyAdmin\SqlParser\Parser;

class Database
{
    private $mPtr = null;
    private $ORM  = null;

    private $mHost = '';
    private $mPort = 0;
    private $mUsername = '';
    private $mPassword = '';
    private $mDatabase = '';
    private $mConnected = false;

    public function __construct()
    {
        global $Core;

        $this->mHost = $Core->Config->database->host;
        $this->mPort = $Core->Config->database->port;
        $this->mUsername = $Core->Config->database->username;
        $this->mPassword = $Core->Config->database->password;
        $this->mDatabase = $Core->Config->database->database;
    }

    public function Init()
    {
        global $Core;

        try
        {
            // TODO : handle multiple driver : mysql, postgreSQL ...
            $this->mPtr = new PDO("mysql:host=".$this->GetHost().';port='.$this->GetPort().';dbname='.$this->GetDatabase(),
                                 $this->GetUsername(), $this->GetPassword());

            $this->ORM = new Database_ORM();
            $this->mConnected = true;

        }catch(PDOException $pException)
        {
            $Core->Logger->write("Database", "Unable to connect to database '".$this->GetDatabase()."' on server ".$this->GetHost().":".$this->GetPort()."");
            $this->mConnected = false;
            die();
        }

    }

    public function Query($pQuery, $pArgs = array(), $pBuildORM = true)
    {
        if($this->IsConnected())
        {
            $lORM = null;
            $lStatement = $this->mPtr->prepare($pQuery);
            $lStatement->execute($pArgs);

            $lParser = new Parser($pQuery);

            $lORM = new Database_ORM();

            if(property_exists($lParser, 'statements') && $pBuildORM)
            {



                $lTable = $lParser->statements[0]->from[0]->table;

                if(!$lORM->Exist($lTable))
                    $lORM->Build($lTable);
                else
                    $lORM->loadCache($lTable);
            }

            $lData = $lStatement->fetch(PDO::FETCH_ASSOC);

            if($pBuildORM)
            {
                foreach($lData as $lKey => $lValue)
                    $lORM->{$lKey} = $lValue;

                return $lORM;
            }else
                return $lData;

        }

        return null;
    }

    public function QueryAll($pQuery, $pArgs = array(), $pBuildORM = true)
    {
        if($this->IsConnected())
        {
            $lORM = null;
            $lStatement = $this->mPtr->prepare($pQuery);
            $lStatement->execute($pArgs);

            $lParser = new Parser($pQuery);

            $lData = $lStatement->fetchAll(PDO::FETCH_ASSOC);

            if(property_exists($lParser, 'statements') && $pBuildORM)
            {
                $lArray = [];
                $lTable = $lParser->statements[0]->from[0]->table;

                foreach($lData as $lRow)
                {
                    $lORM = new Database_ORM();

                    if(!$lORM->Exist($lTable))
                        $lORM->Build($lTable);
                    else
                        $lORM->loadCache($lTable);

                    foreach ($lRow as $lKey => $lField)
                    {
                        $lORM->{$lKey} = $lField;
                    }

                    $lArray[] = $lORM;
                }

                return $lArray;
            }else
                return $lData;
        }
        return null;
    }

    public function Exec($pQuery, $pBuildORM = true)
    {
        if($this->IsConnected())
        {
            $lParser = new Parser($pQuery);

            $lResult = $this->mPtr->query($pQuery)->fetch(PDO::FETCH_ASSOC);

            if(property_exists($lParser, 'statements') && $pBuildORM)
            {
                $lORM = new Database_ORM();

                $lTable = $lParser->statements[0]->from[0]->table;

                if(!$lORM->Exist($lTable))
                    $lORM->Build($lTable);
                else
                    $lORM->loadCache($lTable);

                foreach($lResult as $lKey => $lValue)
                    $lORM->{$lKey} = $lValue;

                return $lORM;

            }else
                return $lResult;
        }
    }

    public function ExecAll($pQuery, $pBuildORM = true)
    {
        if($this->IsConnected())
        {
            $lParser = new Parser($pQuery);

            $lResult = $this->mPtr->query($pQuery)->fetchAll(PDO::FETCH_ASSOC);

            if(property_exists($lParser, 'statements') && $pBuildORM)
            {
                $lArray = [];
                $lTable = $lParser->statements[0]->from[0]->table;

                foreach($lResult as $lRow)
                {
                    $lORM = new Database_ORM();

                    if(!$lORM->Exist($lTable))
                        $lORM->Build($lTable);
                    else
                        $lORM->loadCache($lTable);

                    foreach ($lRow as $lKey => $lField)
                    {
                        $lORM->{$lKey} = $lField;
                    }

                    $lArray[] = $lORM;
                }

                return $lArray;
            }else
                return $lResult;
        }
    }

    public function GetLastInsertID()
    {
        return $this->mPtr->lastInsertId();
    }

    public function Build($pTable)
    {
        $lORM = new Database_ORM();
        $lORM->Build($pTable);

        return $lORM;
    }

    public function GetHost()
    {
        return $this->mHost;
    }

    public function GetPort()
    {
        return $this->mPort;
    }

    public function GetUsername()
    {
        return $this->mUsername;
    }

    public function GetPassword()
    {
        return $this->mPassword;
    }

    public function GetDatabase()
    {
        return $this->mDatabase;
    }

    public function IsConnected()
    {
        return $this->mConnected;
    }
}

$Core->push("Database", new Database());
