<?php
    abstract class View_Base
    {
        private $mConfig;
        private $mRoutePtr;

        public function __construct()
        {
            $this->maxime();
        }

        public function maxime(){
            global $Core;
            $Core->Template->setVar('voila', 'hey');
        }

        public function setConfig($pConfig)
        {
            $this->mConfig = $pConfig;
        }

        public function setRoute($pRoute)
        {
            $this->mRoutePtr = $pRoute;
        }

        public function getConfig()
        {
            return $this->mConfig;
        }

        public function getRoute()
        {
            return $this->mRoutePtr;
        }
    }
?>