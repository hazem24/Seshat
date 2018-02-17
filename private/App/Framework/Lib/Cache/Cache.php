<?php 

        namespace Framework\Lib\Cache;
        use Framework\ConstructorClass as ConstructorClass;
        /**
        *@class This Class Is Factory Class For All Cache System
        */
        Abstract Class Cache extends ConstructorClass
        {

            protected static $setting = [];
            protected static $service;

            /**
            *@method constructor void
            */

            public function __construct(array $setting = []){
                    self::$setting = $setting;
            }

            /**
            *@method cacheConnect  Create Instance For Specific Third Party Services
            *@method setDataToCached Saved Cached Data
            *@method getData Get Data From Cache With Specific Key
            *@method updateCacheInRunTime @return bool
            */
            Abstract public static function cacheConnect();
            Abstract public static function setDataToCached(string $key , $values , int $expire = 0):bool;
            Abstract public static function getData(string $key);
            Abstract public static function updateCacheInRunTime(string $key , string $type ,  $values = null):bool;
                
        }