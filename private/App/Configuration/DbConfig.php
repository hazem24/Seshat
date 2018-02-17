<?php 

    namespace Configuration;
    use Framework\Registry as Registry;
    use Configuration\Config as Config;
    use Framework\Exception\DbException as DbException;
    use Framework\Lib\DataBase\Connector as Connector;
    Class DbConfig extends Config
    {
        private   static $setting = [];  
        /**
        *@method extend From Config.php Class
        * 'setting'=>
                    'driver' => 'mysql', (switch Inside opertion)
                    'host'=>'Localhost'
                    'username' => 'root',
                    'dbname'=> 'test',
                    'password' => '',
                    'option' => ['utf8'] (optional),

           @param $dbh = new PDO('mysql:host=localhost;dbname=test', $user, $pass);         
        */

        public static function setup(array $setting = []){
            self::$setting = $setting;
            $opertion_setup = self::operation();
            if($opertion_setup && is_object($opertion_setup)){
                    // Setup Completed..!
                    return $opertion_setup;
            }
                   // Exception Error Kind Of Db
                   throw new DbException("Error @ Class: " . __CLASS__ . ' In DataBase Setup' );
        }
        /**
        * This Function Save Instance From The DataBase To Registry Class And Create The Config Opertion To Do It ..
        */
        protected static function operation(){
             /**
             * Create Instance Of Specific Connector Driver
             * Save It In Registry Class
             * @return true in success false if not
             */
             $driver = (isset(self::$setting['setting']['driver'])) ? strtolower(self::$setting['setting']['driver']) : null  ;
             if(isset($driver) && !empty($driver)){
                    switch ($driver) {
                        case 'mysql':
                            $mysqlObject =   new Connector\Mysql(self::$setting['setting']);
                            return $mysqlObject->init(); // Return Pdo Object
                            break;
                        
                        default:
                            return false; 
                            break;
                    }
            }


             return false;   
        }

    }