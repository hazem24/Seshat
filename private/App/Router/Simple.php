<?php


        namespace App\Router;

        /**
        *This Class Provide Simple Example Of How Route Can Be Initilize 
        */

        Class Simple extends Route
        {
            public function match(string $url):bool{
                    parent::match($url);
                    if($this->controller[0] == $this->pattern){
                           $this->controller = substr($this->controller , 1); 
                           return true; 
                    }
                    return false;
            }
        }