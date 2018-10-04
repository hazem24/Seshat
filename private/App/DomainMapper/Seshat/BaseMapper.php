<?php 

    namespace App\DomainMapper\Seshat;
    use Framework\Lib\DataBase\DataMapper\AbstractDataMapper;
    use Framework\Registry as Registry;
    use Framework\Shared\Model;
    use App\DomainHelper\FastCache;
    use Framework\Lib\DataBase\DataMapper\Collections\AbstractGeneratorCollection as Collection;

 
        /**
        *This Class Is Provide Base Mapper for all mapper class. 
        */
        Class BaseMapper extends AbstractDataMapper
        {

            /**
             * redisDB.
             */
            protected function redis(){
                return new FastCache('redis');
            }

            protected function doSave(Model $model){    
            }
            protected function createObject(array $fields):Model{
            }
            protected function getCollection(array $raw): Collection{
            }
            protected function selectAllStatement():\PDOStatement{
            }

           
        }
