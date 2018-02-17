<?php 

            namespace Configuration;
            use Framework\Exception\MailerException;
            use Framework\Lib\Mailer\Service;

            /**
            *This Class Create Setup For Mailer System
            */
            class MailerConfig extends Config
            {

                private static $setting = [];
                public static  function setup(array $setting){
                        self::$setting = $setting;
                        $operation = self::operation();
                        if($operation && is_object($operation)){
                                return $operation;
                        }

                        throw new MailerException("Error @Configuration Of Mailer System In Your App Please Fix This @class " .__CLASS__);
                }


                protected static function operation(){
                        switch (strtolower(self::$setting['service'])) {
                            case 'phpmailer':
                                return Service\PHPMailerService::init(self::$setting['option']); // Return Instance From PhpMailer
                                break;
                            
                            default:
                                return false;
                                break;
                        }
                }
            }