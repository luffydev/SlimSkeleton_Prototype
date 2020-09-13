<?php

    use MatthiasMullie\Minify;

    class ResourceBuilder
    {

        private $mExternalCSS = [];
        private $mExternalJS = [];

        private $mCSSList = [];
        private $mJSList = [];

        private $mCSSMinifier = null;
        private $mJSMinifier = null;

        public function __construct()
        {
            $this->mCSSMinifier = new Minify\CSS();
            $this->mJSMinifier = new Minify\JS();
        }

        // CSS 
        public function BuildCSS($pRoute)
        {
            global $Core;

            $pRoute = strtolower($pRoute);
            $lFile = dirname(__DIR__).'/resources.json';

            if(!file_exists($lFile))
            {
                $Core->Logger->Write("ResourceBuilder", "Unable to find config :".$lFile);
                return;
            }

            $lConfig = json_decode(file_get_contents($lFile));

            if(!property_exists($lConfig, $pRoute))
            {
                $Core->Logger->Write("ResourceBuilder", "Unable to find route ".$pRoute." in config :");
                return;
            }

            $this->BuildExternalCSS();
            
            if($Core->Config->dev_mode == true)
            {
                if(property_exists($lConfig->$pRoute, 'css'))
                {
                    foreach($lConfig->$pRoute->css as $lKey => $lCSS)
                    {
                        $lFolder = dirname(__DIR__).'/templates/static/';
                        $this->mCSSList[] = '/templates/static/'.$lCSS;
                    }
                }

            }else
            {
                if(property_exists($lConfig->$pRoute, 'css'))
                {
                    foreach($lConfig->$pRoute->css as $lKey => $lCSS)
                    {
                        $lFolder = dirname(__DIR__).'/templates/static/';
                        $this->mCSSMinifier->add($lFolder.''.$lCSS);
                    }

                    $lFolderName = md5($pRoute);
                    $lOutputFolder = dirname(__DIR__).'/templates/static/css/build/'.$lFolderName.'/';

                    if(!file_exists($lOutputFolder))
                        mkdir($lOutputFolder);   

                    if(file_exists($lOutputFolder.'/'.$pRoute.'.css'))
                    {
                        $this->mCSSList[] = '/templates/static/css/build/'.$lFolderName.'/'.$pRoute.'.css';
                        return;
                    }
                    
                    $this->mCSSMinifier->minify($lOutputFolder.'/'.$pRoute.'.css');
                    $this->mCSSList[] = '/templates/static/css/build/'.$lFolderName.'/'.$pRoute.'.css';
                }

            }
        }

        public function getBuildedCSS()
        {
            $lStr = '';

            foreach($this->mCSSList as $lKey => $lCSS)
            {
                $lStr .= '<link href="'.$lCSS.'" rel="stylesheet">';
            }

            return $lStr;
        }

        public function BuildExternalCSS()
        {
            $lResourceConfig = dirname(__DIR__).'/resources.json';

            if(!file_exists($lResourceConfig))
            {
                $Core->Logger->Write("ResourceBuilder", "Unable to find external config :".$lResourceConfig);
                return;
            }

            $lConfig = json_decode(file_get_contents($lResourceConfig));

            if(property_exists($lConfig, 'external'))
            {
                $this->mCSSList = array_merge($lConfig->external->css, $this->mCSSList);
            }
        }

        // JS

        public function BuildJS($pRoute)
        {
            global $Core;

            $pRoute = strtolower($pRoute);
            $lFile = dirname(__DIR__).'/resources.json';

            if(!file_exists($lFile))
            {
                $Core->Logger->Write("ResourceBuilder", "Unable to find config :".$lFile);
                return;
            }

            $lConfig = json_decode(file_get_contents($lFile));

            if(!property_exists($lConfig, $pRoute))
            {
                $Core->Logger->Write("ResourceBuilder", "Unable to find route ".$pRoute." in config :");
                return;
            }

            $this->BuildExternalJS();
            
            if($Core->Config->dev_mode == true)
            {
                if(property_exists($lConfig->$pRoute, 'js'))
                {
                    foreach($lConfig->$pRoute->js as $lKey => $lJS)
                    {
                        $lFolder = dirname(__DIR__).'/templates/static/';
                        $this->mJSList[] = '/templates/static/'.$lCSS;
                    }
                }

            }else
            {
                if(property_exists($lConfig->$pRoute, 'js'))
                {
                    foreach($lConfig->$pRoute->js as $lKey => $lJS)
                    {
                        $lFolder = dirname(__DIR__).'/templates/static/';
                        $this->mJSMinifier->add($lFolder.''.$lJS);
                    }

                    $lFolderName = md5($pRoute);
                    $lOutputFolder = dirname(__DIR__).'/templates/static/js/build/'.$lFolderName.'/';

                    if(!file_exists($lOutputFolder))
                        mkdir($lOutputFolder);   

                    if(file_exists($lOutputFolder.'/'.$pRoute.'.js'))
                    {
                        $this->mJSList[] = '/templates/static/js/build/'.$lFolderName.'/'.$pRoute.'.js';
                        return;
                    }
                        
                    $this->mJSMinifier->minify($lOutputFolder.'/'.$pRoute.'.js');

                    $this->mJSList[] = '/templates/static/js/build/'.$lFolderName.'/'.$pRoute.'.js';
                }

            }
        }

        public function BuildExternalJS()
        {
            $lResourceConfig = dirname(__DIR__).'/resources.json';

            if(!file_exists($lResourceConfig))
            {
                $Core->Logger->Write("ResourceBuilder", "Unable to find external config :".$lResourceConfig);
                return;
            }

            $lConfig = json_decode(file_get_contents($lResourceConfig));

            if(property_exists($lConfig, 'external'))
            {
                $this->mJSList = array_merge($lConfig->external->js, $this->mJSList);
            }
        }

        public function GetBuildedJS()
        {
            $lStr = '';

            foreach($this->mJSList as $lKey => $lJS)
            {
                $lStr .= "<script src='$lJS'></script>";
            }

            return $lStr;
        }
    }

    $Core->push('ResourceBuilder', new ResourceBuilder);
?>