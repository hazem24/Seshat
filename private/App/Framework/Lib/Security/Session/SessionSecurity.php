<?php

            namespace Framework\Lib\Security\Session;
            use Framework\Registry;

            /**
            *Static Class Provide Static  Functions To Protect System From Session Haijacking And Fixsation
            */
            Class SessionSecurity
            {
                    protected static $sessionInstance;


                    private function __construct(){
                        //Empty!
                    }
                    private function  __clone(){
                        //Empty!
                    }
                    /**
                    *@method hasUserAgent This Method Until Now I Not Used It @Written 05/07/2017 @04:55 Pm
                    */
                    public static  function hasUserAgent(){
                            self::$sessionInstance = Registry::getInstance('session');
                            /**
                            1-Check If User Has UserAgent Saved In Session 
                                *If Yes Return It 
                                *If No Create One
                                 
                            
                            */
                           if(self::$sessionInstance->getSession('userAgent') !== false){
                                    
                                    return true;
                           }

                                    return false; 
                    }
                    public static function isAttacker(string $userAgent , string $savedAgent = ''){
                        
                                  if($userAgent != $savedAgent){
                                            return true;
                                  }

                                  return false;
                                            
                    }

                    public static function createUserAgent(string $salt){
                              self::$sessionInstance = Registry::getInstance('session');
                              $userAgent = $_SERVER['HTTP_USER_AGENT'];
                              $id        = self::$sessionInstance->sId();
                              $protectedUserAgent = md5($userAgent . $id . $salt); 
                              return $protectedUserAgent;
                    }

            }