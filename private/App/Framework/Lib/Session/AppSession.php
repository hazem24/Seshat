<?php

        namespace Framework\Lib\Session;
        use Framework\Exception\SessionException as SessionException;
        use App\DomainMapper\SessionMapper as SessionMapper;


        Class AppSession implements \SessionHandlerInterface
        {
                private $sessionName;
                private $sessionId;
                private $saveType = ['DB'];
                private $sessionMapper;


                public function __construct(string $type = 'DB' , string $sessionName = null){
                            $type = strtoupper($type);
                            if(!in_array($type,$this->saveType)){
                                    throw new SessionException("Error You Provide Non Vailed Way To Save Session On It You Can Use The Following Only ( " . implode(',' , $this->saveType) . ' )');
                            }
                                //Convert Array To String To Use Below 
                                $this->saveType = $type;
                            if(!is_null($sessionName) && is_string($sessionName)){
                                    $this->sessionName = $sessionName;
                                    session_name($this->sessionName);
                            }

                            session_set_save_handler($this,true);	 
                            session_start();


                            
                }


                public function open($save_path , $session_name){
                       switch ($this->saveType) {
                           case 'DB':
                               $this->sessionMapper = new SessionMapper;
                               return true;
                               break;
                           
                           default:
                                    // Empty !  
                           break;
                       }

                }


                public function read($session_id){
                        $data = $this->sessionMapper->find(['id'=>"$session_id"]);
                        if(is_string($data)){
                                
                                return (string) $data;
                        }

                                return '';
                }

                public function write($session_id , $data){
                       if($this->sessionMapper->replace($session_id , $data) === true){
                            return true;
                       }
                        throw new SessionException("Cannot Write Session Please Fix This"); 
                }

                public function gc($maxLifeTime){
                        return $this->sessionMapper->gc($maxLifeTime);                        
                }

                public function destroy($session_id){
                        if($this->sessionMapper->destroy($session_id)){
                            session_unset();
                            $_SESSION = [];
                            return true;
                        }
                        throw new SessionException("Cannot Destroy The Session");
                }

                public function close(){

                        return true;
                }

                public function getSession($sessionName){
                        if(isset($_SESSION[$sessionName])){

                                return $_SESSION[$sessionName];

                        }
                                return false;
                }

                public function setSession($sessionName , $sessionValue){
                        if(!empty($sessionName)){

                                $_SESSION[$sessionName] = $sessionValue;
                        }else{
                            throw new SessionException("You Can Not Create Session With Empty Data Or Empty Name");
                        }

                }
                public function newId(bool $type = false){
                             session_regenerate_id($type);
                }

                public function saveAndCloseSession(){
                             session_write_close();
                }

                public function sId(){
                        $this->sessionId = session_id();
                        return $this->sessionId;
                }
                public function unsetSession(string $sessionName){
                        if($this->getSession($sessionName)){
                                   unset($_SESSION[$sessionName]);             
                        }
                }

                public function clear(){
                        $_SESSION = [];
                }
        }