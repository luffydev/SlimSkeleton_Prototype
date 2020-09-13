<?php
    abstract class Router_base
    {

        private $mConfig = null;
        private $mRouterName = "";
        private $mSubRouteName = "";
        private $mStrings = null;
        private $mVars = null;

        // c_tor
        public function __construct()
        {
        }

        public function init($pConfigObject, $pSubroute = 'default')
        {
            $this->mConfig = $pConfigObject;
            $this->mRouterName = $pConfigObject->name;
            $this->mSubRouteName = $pSubroute;
            
            $this->mVars = new stdClass();
            $this->mStrings = new stdClass();

            // Init our base config
            $this->initConfigVar();
        }

        public function initConfigVar()
        {
            global $Core;

            if(!property_exists($this->mConfig, "data"))
                return;

            // TODO : here parse global var !
            if(property_exists($this->mConfig->data, 'global'))
            {
                foreach($this->mConfig->data->global as $lKey => $lData)
                    $this->mVars->$lKey = $lData;
            }

            // parse specific route var
            if(!property_exists($this->mConfig->data, $this->mSubRouteName))
            {
                $Core->Logger->Write("Router.Base", "Var for ".$this->mSubRouteName." doesn't exist, skip !");
                return;
            }

            foreach($this->mConfig->data->{$this->mSubRouteName} as $lKey => $lData )
                $this->mVars->$lKey = $lData;

            $this->updateConfigStrings();
        }

        public function pushVariables($pValue, $pIndex = null)
        {
            $lCurrValue = $pValue;

            if($pIndex != null && ( is_array($pValue) && array_key_exists($pIndex, $pValue)) )
            {
                $lCurrValue = $pValue[ $pIndex ];
            }

            foreach($lCurrValue as $lKey => $lValue)
                $this->mVars->$lKey = $lValue;
        }

        public function pushStrings($pStrings, $pIndex = null)
        {
            $lCurrValue = $pStrings;

            if($pIndex != null && ( is_array($pStrings) && array_key_exists($pIndex, $pStrings) ) )
                $lCurrValue = $pStrings[ $pIndex ];

            foreach($lCurrValue as $lKey => $lValue)
                $this->mStrings->$lKey = $lValue;

            $this->updateConfigStrings();
        }

        public function updateConfigStrings()
        {
            if(property_exists($this->mConfig, "strings"))
            {

                if(property_exists($this->mConfig->strings, 'global'))
                {
                    foreach($this->mConfig->strings->global as $lI => $lString)
                    {
                        foreach($this->mVars as $lJ => $lVar)
                        {
                            $lString = str_replace('{'.$lJ.'}', $lVar, $lString);
                        }

                        $this->mStrings->$lI = $lString;
                    }
                }

                if(property_exists($this->mConfig->strings, $this->mSubRouteName))
                {
                    foreach($this->mConfig->strings->{$this->mSubRouteName} as $lI => $lString)
                    {
                        foreach($this->mVars as $lJ => $lVar)
                        {
                            $lString = str_replace('{'.$lJ.'}', $lVar, $lString);
                        }

                        $this->mStrings->$lI = $lString;
                    }
                }

            }
        }
                
        // Function for Building template var
        public function getConfig()
        {
            return $this->mConfig;
        }
    }
?>