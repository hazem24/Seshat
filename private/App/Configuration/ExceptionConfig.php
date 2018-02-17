<?php

    namespace Configuration;
    use Configuration\Config as Config;
    use Framework\Exception\CoreException as CoreException;

    Class ExceptionConfig extends Config
    {
        private   static $setting = [];

          /**
        *@method extend From Config.php Class
        * [
            "production"=>"true||false",
            "Log"=>"["LogFilePath"=>'path' , "true||false"]",
            ""=>"",

          ]
        */

        public static function setup(array $setting = []){
            self::$setting = $setting;
            
            if(self::operation()){
                    // Setup Completed..!
                    return true;
            }
                    //  Exception Error Kind Of Core
                   throw new CoreException("Error @ Class: " . __CLASS__ . ' In Error  Setup' );


        }

        public static function excepetionHandler($exception){


                if(self::$setting['production']){
                    /** 
                    *Log Exception At Exception.log File With Some Details About The Exception
                    *With Some Details
                    */ 
                    $userId   = self::initSessionDataForError()[0];
                    $userName = self::initSessionDataForError()[1];

                    $Message  = "This Exception  Happen ( " . $exception->getMessage() . " ) at File ( ".$exception->getFile()." )  at Line (" . $exception->getLine() . ") (".$exception->getTraceAsString().")From Ip: " . $_SERVER['REMOTE_ADDR'] . "  His\Her ID: " . $userId . "  His\Her UserName : $userName";
                    file_put_contents(self::$setting['logPath'] , $Message . "\r\n" , FILE_APPEND | LOCK_EX);

                }else{
                    echo $exception->getMessage();
                }
        }
        /**
        * This Function Save Instance From The Error To Registry Class And Create The Config Opertion To Do It ..
        */
        protected static function operation():bool{
             /**
             * Create Instance Of Exception Class
             * Save It In Registry Class
             * Set Exception Handler Setting 
             * Log Exception In The Any Of the Two Way 
             * @return true in success false if not
             */
            
             set_exception_handler(array(ExceptionConfig::Class,'excepetionHandler'));
            return true;   
   
        }




    }
 
