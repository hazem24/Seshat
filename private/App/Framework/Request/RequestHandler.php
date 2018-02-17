<?php

        namespace Framework\Request;

        /***
        *This Class Handle All Request That Take From The App Version One Of This Class
        *@Written @23/06/2017 @01:23 Am
        */

        Class RequestHandler
        {
            protected static $_postData = [];
            protected static $_getData  = [];

            private function  __construct(){
                //Cannot Initilize Instance From This Class Only Allow As Static
            }

            private function __clone(){
                //Cannot Clone Instance From This Class Only Allow As Static    
            }

            public  static function postRequest():array{
                if($_SERVER['REQUEST_METHOD'] === 'POST'){
                    foreach($_POST as $name => $value){
                        self::$_postData[$name] = $value;
                    }
                    return  self::$_postData;
                }
                return [];

            }

            public static function getRequest():array{
                if($_SERVER['REQUEST_METHOD'] === 'GET'){
                     foreach($_GET as $name => $value){
                        self::$_getData[$name] = $value;
                    }
                    return self::$_getData;
                }
                    return [];
            }

            public static  function get(string $name):string{
                if(array_key_exists($name , self::$_getData)){
                    return self::$_getData[$name];

                }
                return '';
            }


            public static function post(string $name):string{
                if(array_key_exists($name , self::$_postData)){
                    return self::$_postData[$name];
                }
                return '';
            } 

        }