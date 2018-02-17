<?php

        

        namespace Framework\Lib\Mailer\Service;

        

        /**

        *This Class Uses Php Mailer System To Send Mail 

        *Please Note That The Service Name Must Be Library Class Name + Service Word

        *For Example php Mailer Class Name PHPMailer So This Class Name "PHPMailerService" And So On

        */

        class PHPMailerService extends AbstractMailer

        {







            /**

            *@param sender Will Be An Instance From PhpMailer That Created By init Method With Setting Of (Take From Registry)

            *MailerConfig Class

            */    

            public static function send( $sender , array $msg):bool{

                

                $sender->setFrom(MAIL, COMPANY_MAIL);

                $sender->addAddress($msg['userMail'], $msg['userName']);     // Add a recipient

                $sender->Subject = $msg['subject'];

                if(isset($msg['html'])){

                        $sender->isHTML((bool)$msg['html']); // To Set The Msg To Html Form

                }

                $sender->Body    = self::$msgFormat->format($msg['body']);

                $sender->AltBody = self::$msgFormat->format($msg['altBody']);

                if(!$sender->send()) {

                        return false;

                } else {

                        return true;      

                }

            }



            public static function init(array $setting = []){



                    $mailer = new \PHPMailer(); // From Ouside The NameSpace You Will Find It Inside Autoload Composer

                    //$mailer->isSMTP();

                    $mailer->Host     = $setting['host'];

                    $mailer->Port     = $setting['port'];

                    if(!empty($setting['smtpAuth'])){

                                $mailer->SMTPAuth   = $setting['smtpAuth']; // Enable SMTP authentication

                    }

                    if(!empty($setting['username']) &&  $setting['password']){

                        $mailer->Username   = $setting['username'];    // SMTP username

                        $mailer->Password   = $setting['password'];   // SMTP password

                    }

                    if(!empty($setting['secure'])){

                        $mailer->SMTPSecure = $setting['secure'];    // Enable TLS encryption, `ssl` also accepted

                    }

                    return $mailer;

            }



        }