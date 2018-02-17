<?php 

        namespace Framework\Lib\Cache;
        use Framework\ConstructorClass as ConstructorClass;
        /**
        *@class This Class Is Factory Class For All Cache System
        */
        Abstract Class FactoryCache extends ConstructorClass
        {
                /**
                *@property host string  for localhost '127.0.0.1'
                *@property port string || int Example 11211 For MemaCached 
                */
                protected static $host;

                protected static $port;
                /* ['server'=>[
                    'host'=>'',
                    'port'=>''
                ]] 
                
                */
                  /**
                *initlize The Connection To Cache System
                */
                public static  function init(array $setting = []){
                           if(!empty($setting) && array_key_exists('server',$setting)){
                                    self::$host = $setting['server']['host'];
                                    self::$port = $setting['server']['port'];
                            }
                }
              
                Abstract public static  function createCache():Cache;
              
        }