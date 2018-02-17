<?php
        namespace Framework\Lib\DataBase\Connector;
        use Framework\Exception\DbException as DbException;
        use Framework\Lib\DataBase\Connector\BaseConnector as BaseConnector;
        use Framework\Helper\ArrayHelper as ArrayHelper;
        



        Class Mysql extends BaseConnector
        {
            protected $host;
            protected $dbname;
            protected $username;
            protected $password;
            protected $predefined_options  = [];
            protected $pdo;
            protected $options = [];


            public function __construct(array $option){
                parent::__construct($option);
                //Remove Options Array  From The Incoming Array
                $this->predefined_options = array_pop($this->connect_param);
            }

            public function init(){
                
                $filterConnectedData = ArrayHelper::trimArray($this->connect_param);
                /** 
                *@note array search must be added to array helper Refactor  it Written at 23/05/2017 at 07:48
                */
                if($searchForError = array_search( 'empty' , $filterConnectedData)){
                       throw new DbException("empty data Found In **($searchForError)** Field Please Modifiy It In (Setting.php) File this Error Generated @class " . __CLASS__);
                }

                $this->host      =     $this->connect_param['host'];
                $this->dbname    =     $this->connect_param['dbname'];
                $this->username  =     $this->connect_param['username'];
                $this->options   =     $this->connect_param['options'];
                //This Must Be Equal $this->connect_param['password']
                $this->password = '';
                /// This If Statement Logic Need Refactor I Think I Will Use Utf-8 Only Created At 24/05/2017 at 05:16 Am
                if(isset($this->predefined_options) && !empty($this->predefined_options)){
                        $this->predefined_options = implode(',',$this->predefined_options);
                        $this->predefined_options = $this->predefinedConstant($this->predefined_options);
                }

                return $this->isConnected();
            }

            public function lastInsertId(string $name = ''){

                    return $this->pdo->lastInsertId();
            }
            /**
            *@method isConnected Check If The Mysql Connected Or Not 
            *@return bool
            */

            private function isConnected():\PDO{
                $this->pdo = $this->makeConnection();
                
                if(is_object($this->pdo) && $this->pdo instanceof \PDO){
                    return $this->pdo;
                }

                throw new DbException("<br>Error When Try To Connect To DataBase @Class " . __CLASS__);
                    

            }
            /**
            *
            *$dsn, $username, $password, $options
            */
            private function makeConnection(){
                try{
                    
                    $db = new \PDO('mysql:host='.$this->host . ";dbname=".$this->dbname,$this->username,$this->password , $this->options);
                    $db->setAttribute(\PDO::ATTR_ERRMODE ,\PDO::ERRMODE_EXCEPTION);
                    
                }catch(\PDOException $e){
                        echo $e->getMessage();
                        return;
                }
                
                return $db;
            }
            // This Function Logic Need Refactor I Think I Will Use Utf-8 Only Created At 24/05/2017 at 05:15 Am
            private function predefinedConstant(string $constantName = 'utf8'):array{
                    $return = [];
                    $constantName = strtolower($constantName);
                    switch ($constantName) {
                        case 'utf8':
                            $return[\PDO::MYSQL_ATTR_INIT_COMMAND] = 'SET NAMES utf8';
                            break;
                        default:
                            $return = [];
                            break;
                    }
                        return $return;
            }
        }