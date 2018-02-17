<?php

        namespace App\Model\WebServices;
		use \Curl\Curl;
		


        /**

        *This Class Is An Abstract Class For All Services Uses Selenium Servers

        */



        Abstract  Class __Services 

        {
			protected $curl;
            
            public function init(string $userAgent = ''){
				   $this->curl = new Curl();
				   $this->curl->setUserAgent($userAgent);	
			}
        }