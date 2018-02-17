<?php 


            namespace Framework\Lib\Security\Forms;
            use Framework\Registry;


            Class CsrfProtection 
            {
                protected static $formToken;
                protected static $session;


                private function __construct(){
                    //Empty!
                }
                private function __clone(){
                    //Empty!
                }

                public static function protectForm(){
                    /**
                    *This Function Responsable For 
                        1-create Token
                        2-save Token In Session 
                        3-create Token Field (return This Point)
                    */
                    self::$session = Registry::getInstance('session');
                    self::createToken();
                    self::saveTokenInSession();
                    return self::createTokenField();
                }


                public static function sessionTokenValidation(string $token = null):bool{
                            if(!is_null($token)){
                                    // Process The Validation 
                                    self::$session = Registry::getInstance('session');
                                    $sessionToken = self::$session->getSession('formToken');
                                    $tokenTime    = self::$session->getSession('formTokenTime');
                                    $timer = (((int)$tokenTime + 1800) >= time()) ? true : false;
                                    if(($token === $sessionToken) && $timer ){
                                                return true;
                                    }else if(!$timer){
                                                self::$session->unsetSession('formTokenTime');
                                    }                      
                            }

                            return false;

                }

                protected static function createToken(){
                        /**
                        *Function Resbosiable For Create Token For Forms 
                        **/
                            self::$formToken = md5(uniqid(rand() , true));
                }

                protected static function createTokenField():string{
                            return '<input type="hidden" name="formToken" value="'.self::$formToken.'">';
                }


                protected static function saveTokenInSession(){
                          self::$session->setSession('formToken',self::$formToken);
                          self::$session->setSession('formTokenTime',time());  
                }

               
            }