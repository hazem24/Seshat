<?php

        namespace Framework\Shared;
        use Framework\ConstructorClass;
        use Framework\Lib\DataBase\DataMapper\AbstractDataMapper as Mapper;

        /**
        *This Class Provide And Interface Of All Model Child 
        *Each Model Must Extends From This Class
        */
        Abstract class Model extends ConstructorClass
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