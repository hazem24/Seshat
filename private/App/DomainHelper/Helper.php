<?php
        namespace App\DomainHelper;
        use Framework\Registry;
        use Framework\Request\RequestHandler;

        
        /**
        *Provide Some Helper Function Can Be Used In The Domain Logic App
        */

        Class Helper
        {
            public static function redirectOutSide(string $sessionName = 'id' , string $direction = ""){
                   $sessionInstance = Registry::getInstance('session');
                   if((bool)$sessionInstance->getSession($sessionName) === false){
                        // Redirect User 
                        $sessionInstance->saveAndCloseSession();
                        header("Location:$direction");
                        exit;
                   }     
            }

            public static function redirectInside(string $sessionName = 'id' , string $direction = ""){
                        $sessionInstance = Registry::getInstance('session');
                        if((bool)$sessionInstance->getSession($sessionName) === true){
                        // Redirect User 
                        $sessionInstance->saveAndCloseSession();
                        header("Location:$direction");
                        exit;
                   }

            }
            /**
            *@method ajaxRequest This Method Handle The Logic To Check If The Coming Request Is Ajax Or Normal 
            *@return bool 
            *If @return true This Mean The InComing Request Is Ajax Request 
            *If @return false This Mean The Incoming Request Without Ajax 
            */
            public static function ajaxRequest():bool{
                   if(!empty(RequestHandler::post('ajax'))){
                        return true;
                   }
                   if(!empty(RequestHandler::get('ajax'))){
                        return true;
                   }

                   return false; 

            }
            /**
            *@method noJs This Function Stop Execute The Script In Case Of User Stop JavaScript In Website 
            */
            public static function noJs(){
                      require(VIEWS_PATH . "NoJs/noJs.html");
                      exit;
            }

            /**
            *@method getMailerService This Method Just Take The Library Name And Return Full Path 
            *To Reach This Library  Service
            *@used At Registery Command
            */
            public static function getMailerService($lib){

                $serviceName =    "Framework\\Lib\\Mailer\\Service\\". get_class($lib) . "Service";
                return $serviceName;
            }
            /**
             * @method accountType Convert And Return The Account Type.
             * @return array.
             */

             public static function  accountType(int $typeNumber = 1){
                    $accountsType = [1=>PERSONAL,2=>BRAND_PRODUCT,3=>STAR,4=>WRITTER,5=>READER,6=>STUDENT,7=>CONTENT_MAKER,8=>OTHER,9=>PROGRAMMER];
                    if(array_key_exists($typeNumber,$accountsType)){
                                return [$typeNumber=>$accountsType[$typeNumber]];
                    }
                                return [1=>$accountsType[1]];

            } 

            /**
             * @method categoryType Responsable For Convert category number to type.
             * @return array.
             */
            public static function categoryType(int $typeNumber = 0){
                   if(array_key_exists($typeNumber,TWEET_CATEGORY)){
                                return [$typeNumber=>TWEET_CATEGORY[$typeNumber]];
                   }
                                return [0=>TWEET_CATEGORY[0]]; 
            }

        }