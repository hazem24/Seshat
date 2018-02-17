<?php
        namespace App\Commands;
        use Framework\Shared\AbstractCommand;


        /**
        *This Class Have All Commands That Interact with twitter Api Model.
        */

        Class TwitterApiCommand extends AbstractCommand
        {
            private static  $TwitterModel = "App\\Model\\WebServices\\Twitter\\Api\\";    
            public function execute(array $data = []){
                    $className = self::$TwitterModel.$data['ModelClass'];
                    if(class_exists($className) === true){
                                $TwitterApi_object = new $className($data['paramater']);
                                if(isset($data['Method']['parameters']) && is_array($data['Method']['parameters']) && !empty($data['Method']['parameters'])){
                                        $method_to_call = $TwitterApi_object->startProcess(['method'=>$data['Method']['Name'],'user_auth'=>$data['Method']['user_auth'],'parameter'=>$data['Method']['parameters']]);
                                }else{
                                        $method_to_call = $TwitterApi_object->startProcess(['method'=>$data['Method']['Name'],'user_auth'=>$data['Method']['user_auth']]);
                                }


                        return $method_to_call;
                    }
            }



        }
