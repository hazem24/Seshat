<?php
        namespace Framework\Lib\Cache;

        use Framework\Lib\Cache\FactoryCache as FactoryCache;
        use Framework\Registry as Registry;
        
        Class RedisCachedFactory extends FactoryCache
        {
              /**
              *@property schema string example tcp for redis cache only
              */  
              private static $schema;
                /* ['server'=>[
                    'schema'=>'tcp'
                    'host'=>'',
                    'port'=>''
                ]] 
                
                */
                /**
                *@method createCache 
                *@return Object From Specfic Cache
                */
                public static function init(array $setting = []){
                          parent::init($setting);
                          if(isset($setting['server']['schema'])){
                                        self::$schema = $setting['server']['schema'];
                            }else{
                                        // Default Schema 
                                        self::$schema = 'tcp';
                            }
                }
                public static function createCache():Cache{

                    /**
                    *1- Create Instance Of Redis Cache 
                    *2- Registry It In Registry Class
                    *3- return the instance from regisrty
                    * scheme Not schema :)
                    **/
                    $redisCached =  new Redis(["scheme"=>self::$schema , "host"=>self::$host , "port"=>self::$port]);
                    Registry::setInstance('Redis',$redisCached);
                    return  Registry::getInstance('Redis');
                }


        }