<?php

         namespace Framework\Shared;
         use Framework\Exception\CoreException;
         

         /***
         *This Class Is A Factory Class For All Commands Class Simple Factory 
         */

         Class CommandFactory 
         {
             CONST COMMAND_PATH = BASE_URI . 'App' . DS . 'Commands' . DS;

             public static function getCommand(string $commandName){
                    $commandName = ucfirst(strtolower($commandName));	    
                    $filePath = self::COMMAND_PATH . $commandName . 'Command.php';
                    if(file_exists($filePath)){
                            $cmd =   "App\\Commands\\". $commandName . 'Command';
                            return new $cmd;
                    }else{
                            throw new CoreException("You Try To Create Object Of A Command That Does Not Exists @class " . __CLASS__);
                    }
             }

         }
