<?php
    abstract class View_Base
    {

        private $mConfig;
        private $mRoutePtr;

        public function _construct()
        {
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