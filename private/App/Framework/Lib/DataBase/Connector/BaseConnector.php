<?php

        namespace Framework\Lib\DataBase\Connector;
        use Framework\Exception\DbException as DbException;
        use Framework\ConstructorClass as ConstructorClass;

        /***
        *@class This Class Is The Base Class For All 
         DataBase Driver Such As Mysql
        */

        Abstract Class BaseConnector extends ConstructorClass
        {
                /**
                *@property connect_param Take The Parameters THat Connector Need To Work
                */ 
                protected $connect_param = [];

                public function __construct(array $option){
                        $this->connect_param = $option;
                }

        }