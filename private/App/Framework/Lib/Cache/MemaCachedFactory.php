<?php

        namespace Framework\Lib\Cache;

        use Framework\Lib\Cache\FactoryCache as FactoryCache;
        use Framework\Registry as Registry;
        Class MemaCachedFactory extends FactoryCache
        {
            /**
            *@method createCache 
            *@return Object From Specfic Cache
            */

            public static  function createCache():MemaCached{

                /**
                *1- Create Instance Of MemaCached 
                *2- Registry It In Registry Class
                *3- return the instance from regisrty
                **/

                // Note When I Create This In Production Or Liunx Server I Must Use NameSpace Of MemCached In This File
                $memaCached =  new MemaCached(["host"=>self::$host , "port"=>self::$port]); 
                Registry::setInstance('Memacached',$memaCached);
                return  Registry::getInstance('Memacached');
            }


        }