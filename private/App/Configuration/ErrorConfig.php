<?php

    namespace Configuration;
    use Configuration\Config as Config;
    use Framework\Exception\CoreException as CoreException;


    Class ErrorConfig extends Config
    {
          private   static $setting = [];  
          /**
        *@method extend From Config.php Class
        * [
            *"production"=>"true||false",
            *"Log"=>"["LogFilePath"=>'path' , "true||false"]",
            *""=>"",

          *]
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


        public static  function handleError($errno, $errstr, $errfile, $errline){

                    $userId   = self::initSessionDataForError()[0];
                    $userName = self::initSessionDataForError()[1];    
                    $Message  = "This Error Happen ($errstr) From Ip: " . $_SERVER['REMOTE_ADDR'] . "  His\Her ID: " . $userId . "  His\Her UserName : $userName";
                    $Message .= " This Error Happen At File " . $errfile . " At Line $errline Number Of Error Is $errno";
                    
                    if(self::$setting['production'])
                    {
                            error_log($Message);
                            if($errfile != E_NOTICE)
                            {
                                /** i Must Here Use Jquery And Some Thing Like That
                                *This Error Must Be Appear In Javascript And Design Under Name of Temporary Error 
                                **/
                                echo json_encode(['AppError'=>'AppError Please Contact The Support.']);
                                exit;
                            }
                            

                    }else{

                            var_dump($Message);
                    }

                            return true;
        } 
        /**
        * This Function Save Instance From The Error To Registry Class And Create The Config Opertion To Do It ..
        */
        protected static function operation():bool{
             /**
             * xx Error Class xx
             * xx Save It In Registry Class xx
             * Set Error Handler Setting 
             * Log Error In The Any Of the Two Way 
             * @return true in success false if not
             */
             if(file_exists(self::$setting['logPath']) && self::$setting['production'] ){
                    ini_set('error_log',self::$setting['logPath']);
             }
             set_error_handler(array(ErrorConfig::Class,'handleError'));

             return true;   
 
        }

    }
 
