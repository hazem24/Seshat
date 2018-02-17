<?php


        namespace App\DomainMapper\DomainObjectFactory;
        use Framework\ConstructorClass as ConstructorClass;
        use Framework\Shared\Model as Model;
        use Framework\Lib\Cache\ObjectWatcher\ObjectWatcher;



        Abstract Class DomainObjectFactory extends ConstructorClass
        {
           

            
            public function createObject(array $fields){
                    $exist = $this->getFromMap($fields['id']);
                    if(!is_null($exist)){
                        return $exist;
                    }   
                        $object =  $this->doCreateObject($fields);
                        $this->addToMap($object);
                        return $object;
           }

            protected function getFromMap(int $id){  
                        return ObjectWatcher::exists($this->targetClass() , $id);
           }


            protected function addToMap(Model $model){
                        return ObjectWatcher::addObject($model);   
           }
            Abstract protected function doCreateObject(array $fields);
            Abstract protected function targetClass():string;



        }