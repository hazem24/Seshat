<?php

        namespace Framework\Shared;
        use Framework\Lib\DataBase\DataMapper\AbstractDataMapper as Mapper;
        use Framework\ConstructorClass as Base;


        /**
        *This Class Provide And Interface Of All Model Child 
        *Each Model Must Extends From This Class
        */
        Abstract class Model extends Base
        {

                protected function updateDirtyObject(){
                          self::getFinder()->update($this);      
                }

                protected function deleteObject(){
                        self::getFinder()->delete($this);
                }

                protected function insertObject(){
                        self::getFinder()->insert($this);
                }

        
                Abstract protected static function getFinder();
        }