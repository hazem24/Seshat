<?php

        namespace Framework\Lib\Mailer\Service;
        use Framework\ConstructorClass;
        use Framework\Lib\Mailer\Message\Message;
        /**
        *This Class Provide An Abstract Class For All Mailer Service Like SwiftMailer Or PhpMailer
        */

        Abstract CLass AbstractMailer extends ConstructorClass
        {
                protected static $msgFormat;

                public static function setMsgFormat(Message $msgFormat){//Text
                        self::$msgFormat = $msgFormat;
                }

                Abstract public static function send($sender , array $msg);



        }