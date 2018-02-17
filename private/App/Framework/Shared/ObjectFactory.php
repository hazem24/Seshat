<?php

         namespace Framework\Shared;
         use Framework\Exception\CoreException;
         

         /***
         *This Class Is A Factory Class For All Commands Class Simple Factory 
         */

         Class ObjectFactory 
         {
             CONST COMMAND_PATH = BASE_URI . 'App' . DS . 'DomainMapper' . DS . 'DomainObjectFactory' . DS;

             public static function getObjectFactory(string $ObjectName){
                    $ObjectName = explode('\\',$ObjectName);
                    $ObjectName = str_ireplace('mapper','',array_pop($ObjectName));
                    $filePath = self::COMMAND_PATH . $ObjectName . 'ObjectFactory.php';
                    if(file_exists($filePath)){
                            $cmd =   "App\\DomainMapper\\DomainObjectFactory\\". $ObjectName . 'ObjectFactory';
                            return new $cmd;
                    }else{
                            throw new CoreException("You Try To Create Object Of A Object (Domain Object) That Does Not Exists @class " . __CLASS__);
                    }
             }

         }
