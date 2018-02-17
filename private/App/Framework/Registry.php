<?php


    namespace Framework;
    Class Registry
    {
        /**
         *@property array savedClasses This Saved Instances Of Classes 
         */

         private static $_savedClasses = [];


         /**
         *@method void __constructor 
         */

         private function __construct(){
             
         } 

         /**
         *@method void __clone 
         */

         private function __clone(){

         }

         /**
         *@method void setInstances (@param ClassName , @param Instance = '')
         */

         public static function setInstance($ClassName , $instance = null){
             if(!array_key_exists($ClassName , self::$_savedClasses)){
                 self::$_savedClasses[$ClassName] = $instance;
             }

         }

         /**
         *@method instance Of Class getInstances (@param ClassName)
         */

         public static function getInstance($ClassName){
                if(array_key_exists($ClassName , self::$_savedClasses)){
                    return self::$_savedClasses[$ClassName];
                }
                return false;
         }

          /**
         *@method Void deleteClassInstance (@param ClassName)
         */

         public static function deleteClassInstance($ClassName):void{

                // Some Operation To Delete The Class Instances
                unset(self::$_savedClasses[$ClassName]);

         } 

    }