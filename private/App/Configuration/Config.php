<?php

    namespace Configuration;
    use Framework\ConstructorClass as ConstructorClass;
    use Framework\Registry;
    Abstract Class Config extends ConstructorClass
    {
        protected static $session;
        /**
        *@method setup  To Setup The Configuration Of The Framework Requirments
        */

        Abstract public static function setup(array $setting);

        /**
        *@method operation To Create Specific Operation For Specific Config Example : Specific Opertion For Database Configuration ..
        */

        Abstract protected static function  operation();


        /**
        *@method initSessionDataForError Just Create Essintal Session Data Which Need To Detect How Is Responsable
        *For Create This Error || Exception 
        *In Future This Method Must Be More Felxiable So Change The getSession Data For Any Thing Can Be Used In The App
        */
        protected static function initSessionDataForError(){
                self::$session = Registry::getInstance('session');
                $userId   = (self::$session->getSession('userId')) ? self::$session->getSession('userId') : ' Anoyoumus User Create This Error ';
                $userName = (self::$session->getSession('userName')) ? self::$session->getSession('userName') : ' Can Not Detect The User Name That Cause This Error ';
                return [$userId , $userName];


        }
        
    }