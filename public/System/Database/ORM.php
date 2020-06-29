<?php

class Database_ORM extends stdClass
{

    private $mPrefix = '_table';
    private $mTable = '';
    private $mIDField = '';
    private $mFieldList = array();
    private $mIgnoredField = array('mPrefix', 'mTable', 'mIgnoredField', 'mIDField', 'mFieldList');

    public function __construct()
    {
    }

    public function Exist($pTable)
    {
        global $Core;

        return $Core->Cache->Exist($pTable.$this->mPrefix);
    }

    public function Build($pTable)
    {
        global $Core;

        $this->mTable = $pTable;

        if(!$this->Exist($pTable))
        {

            // We build our table struct cache
            $lResult = $Core->Database->ExecAll('SHOW COLUMNS FROM '.$pTable, false);

            foreach($lResult as $lKey => $lData)
            {
                if($lData['Key'] == 'PRI')
                    $this->mIDField = $lData['Field'];

                $this->{$lData['Field']} = null;
                $this->mFieldList[] = $lData['Field'];
            }

            if(!$this->mIDField)
                $this->mIDField = $lResult[0]['Field'];

            $Core->Cache->Set($pTable.$this->mPrefix, $this);

        }else
            $this->loadCache($pTable);
    }

    public function save()
    {
        global $Core;

        $lRequest = "UPDATE ".$this->mTable." SET ";
        $lValue = array();

        foreach($this as $lKey => $lField)
        {
            if(!in_array($lKey, $this->mIgnoredField) && in_array($lKey, $this->mFieldList))
            {
                $lRequest .= $lKey.' = ?,';
                $lValue[] = $lField;
            }
        }

        $lRequest = substr($lRequest, 0, strlen($lRequest) - 1).' WHERE '.$this->mIDField.' = ?';
        $lValue[] = $this->{ $this->mIDField };

        $Core->Database->Query($lRequest, $lValue, false);
   }

   public function insert()
   {
       global $Core;

       $lRequest = "INSERT INTO ".$this->GetTable()." VALUES(NULL, ";
       $lValue = array();

       foreach($this as $lKey => $lField)
       {

           if($lKey != $this->mIDField && ( !in_array($lKey, $this->mIgnoredField) ) )
           {
               $lRequest .= "?,";
               $lValue[] = $this->{ $lKey };
           }
       }

       $lRequest = substr($lRequest, 0, strlen($lRequest) - 1).')';

       print_r($lRequest);

       $Core->Database->Query($lRequest, $lValue, false);
       $this->{$this->mIDField} = $Core->Database->GetLastInsertID();
   }

    public function loadCache($pTable)
    {
        global $Core;

        $this->mTable = $pTable;

        $lObject = $Core->Cache->Get($pTable.$this->mPrefix);

        foreach($lObject as $lKey => $lData) {

            if(!in_array($lKey, $this->mIgnoredField)) {
                $this->mFieldList[] = $lKey;
                $this->{$lKey} = $lData;
            }
        }

        $this->mIDField = $lObject->mIDField;
    }

    public function GetTable()
    {
        return $this->mTable;
    }
}
