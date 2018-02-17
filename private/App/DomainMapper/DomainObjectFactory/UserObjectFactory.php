<?php


            namespace App\DomainMapper\DomainObjectFactory;
            use App\DomainMapper\DomainObjectFactory\DomainObjectFactory as DomainObject;
            use App\Model\UserModel as User;


            /**
            *This Class Such An Exmple How This System Can Be Use In Bussiness Logic
            */
            Class UserObjectFactory extends DomainObject
            {
                public function targetClass():string{

                        return Users::Class;

                }

                public  function doCreateObject(array $fields):User{
                    
                    $model = new User;
                    $model->setPoints($fields['points']);
                    $model->setId($fields['id']);
                    $model->setEmail($fields['email']);
                    $model->setTwAccounts($fields['tw_counts']);
                    $model->setEmailsUnused($fields['email_counts']);
                    $model->setProxyNumber($fields['proxy_counts']);
                    return $model;

                }
            }
