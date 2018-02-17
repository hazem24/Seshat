<?php
        namespace App\Model\WebServices\Twitter;
        use App\Model\WebServices\__Services;
        use App\DomainMapper\UserMapper;
        use \Curl\Curl;
        
        /**
        *This Class Responsable For Register Accounts In Twitter
        */
        Abstract Class TwModel extends __Services
        {
            CONST TW_LOGIN_LINK = TW_LOGIN;
            CONST COOKIES_FOLDER = COOKIES_FOLDER;
            CONST TW_POST_LOGIN = TWITTER_NEW_SESSION;
            /**
            *@method startProcess This method Have Any Logic Of 
            */
            Abstract function startProcess(array $data = []);
            

            protected function extractName(string $emailAdress){
                $pos = stripos($emailAdress, '@');
                return  substr($emailAdress, 0,$pos);
            }


            
            protected function login(string $screenName = '' , string $password = '' , string $email = '',int $phone = 0){
                    
                    /**
                    * 1- Get Login Page Of Twitter --Done
                    * 2- Get Authication Code --Done
                    * 3- Send Post To Twitter --Done
                    * 4- Save Cookies In File --Done
                    * 5- Login To Twitter     --Done
                    * 6- Solve Challenge If Found --Done
                    */
                    $loginPage = $this->loginPage($email);
                    if(stripos($loginPage,'Log in',0) !== false){
                            $auth_code = $this->getAuthication($loginPage);
                            $send_post_login =  $this->sendLoginPost($auth_code,$screenName,$password,$email);
                            
                            if(is_int(stripos($send_post_login,'Verify your identity',0))){ 
                                    $solveChallenge = $this->solveChallenge($send_post_login , $screenName , $email,$phone);
                            }
                    }else if (is_int(stripos($loginPage,'tweet',0))){

                                return ['loggedIn'=>true];
                    }else{
                                return ['twitterRefusedConnection'=>true];
                    }
                    return ['loginPage'=>$loginPage,'auth_code'=>$auth_code];
            }

            protected function loginPage(string $email){
                $options = array(
                    CURLOPT_CUSTOMREQUEST  => "GET",        //set request type post or get
                    CURLOPT_POST           => false,        //set to GET
                    CURLOPT_RETURNTRANSFER => true,        // return web page
                    CURLOPT_HEADER         => false,       // don't return headers
                    CURLOPT_FOLLOWLOCATION => true,        // follow redirects
                    CURLOPT_AUTOREFERER    => true,        // set referer on redirect
                    CURLOPT_SSL_VERIFYPEER => true,        // Make Curl Use Ssl
                    CURLOPT_USERAGENT=>$this->userAgent,
                    CURLOPT_COOKIEJAR =>  realpath(self::COOKIES_FOLDER."/" . $email . ".txt"),
                    CURLOPT_COOKIEFILE => realpath(self::COOKIES_FOLDER."/" . $email . ".txt"),
                    CURLOPT_CONNECTTIMEOUT => 120,      // timeout on connect
                    CURLOPT_TIMEOUT        => 120,      // timeout on response
                    CURLOPT_MAXREDIRS      => 10,       // stop after 10 redirects
                );
        
                $ch      = curl_init( self::TW_LOGIN_LINK );
                curl_setopt_array( $ch, $options );
                $loginPage = curl_exec( $ch );
                curl_close( $ch ); 
                return $loginPage;  

            }

            protected function getAuthication(string $loginPage){
                preg_match('/<input name="authenticity_token" type="hidden" value=".+"/', $loginPage, $matches);
                $auth_code = str_ireplace('<input name="authenticity_token" type="hidden" value="','', $matches[0]);                     
                $auth_code = str_ireplace('"','',$auth_code); 
                return $auth_code; 
            }
            protected function sendLoginPost($auth_code , string $screenName = '' , string $password = '' , string $email = ''){
                      $this->accountCookie($email);                      
                      $ch= curl_init();
                      curl_setopt_array($ch, array(
                      CURLOPT_URL => self::TW_POST_LOGIN,
                      CURLOPT_RETURNTRANSFER => true,
                      CURLOPT_POST => true,
                      CURLOPT_POSTFIELDS => "authenticity_token=$auth_code&session%5Busername_or_email%5D=$screenName&session%5Bpassword%5D=$password&remember_me=1&wfa=1&commit=+Log+in+&ui_metrics=",
                      CURLOPT_COOKIEJAR =>  realpath(self::COOKIES_FOLDER."/" . $email . ".txt"),
                      CURLOPT_COOKIEFILE => realpath(self::COOKIES_FOLDER."/" . $email . ".txt"),
                      CURLOPT_COOKIE     => realpath(self::COOKIES_FOLDER."/" . $email . ".txt"),                       
                      CURLOPT_FOLLOWLOCATION => true,
                      CURLOPT_COOKIESESSION=>true,
                      CURLOPT_CONNECTTIMEOUT=>30,
                      CURLOPT_USERAGENT=>$this->userAgent,
                      CURLOPT_HEADER         => true,       // don't return headers                       
                      CURLOPT_AUTOREFERER    => true,     // set referer on redirect                       
                      CURLOPT_SSL_VERIFYPEER=>true,
                      CURLOPT_MAXREDIRS      => 10,       // stop after 10 redirects                       
                       ));
                       $output = curl_exec($ch);
                       curl_close($ch);
                       return $output;
            }

            protected function solveChallenge(string $output , string $screenName , string $email,int $phone = 0){
                    //Send Another Request To Solve Challange
                    if(stripos($output,"RetypePhoneNumber") !== false){
                        $challenge_type = "RetypePhoneNumber";
                        $value = "$phone";
                    }else{
                        $challenge_type = "RetypeEmail";
                        $value = "$email";
                    }


                    preg_match('/<input type="hidden" name="challenge_id" value=".+/',$output,$matches);
                    preg_match('/<input type="hidden" name="user_id" value=".+/',$output,$userIds);
                    preg_match('/<input type="hidden" name="authenticity_token" value=".+/',$output,$authencity);
                    $challangeId = str_ireplace('<input type="hidden" name="challenge_id" value="','',$matches[0]);
                    $challangeId = trim(str_ireplace('"/>','',$challangeId));
                    $userId      = str_ireplace('<input type="hidden" name="user_id" value="','',$userIds[0]);
                    $userId      = trim(str_ireplace('"/>','',$userId));
                    $auth_token  = str_ireplace('<input type="hidden" name="authenticity_token" value="','',$authencity[0]);
                    $auth_token  = trim(str_ireplace('"/>','',$auth_token));

                    $solve = curl_init();
                    curl_setopt_array($solve, array(
                    CURLOPT_URL => SCREEN_NAME_CHALLANGE,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_POST => true,
                    CURLOPT_POSTFIELDS => "authenticity_token=$auth_token&challenge_id=$challangeId&user_id=$userId&challenge_type=$challenge_type&platform=web&redirect_after_login=%2F&remember_me=true&challenge_response=$value",
                    CURLOPT_COOKIEJAR =>  realpath(self::COOKIES_FOLDER."/" . $email . ".txt"),
                    CURLOPT_COOKIEFILE => realpath(self::COOKIES_FOLDER."/" . $email . ".txt"),
                    CURLOPT_COOKIE     => realpath(self::COOKIES_FOLDER."/" . $email . ".txt"),
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_CONNECTTIMEOUT=>30,
                    CURLOPT_HEADER         => true,       // don't return headers                       
                    CURLOPT_AUTOREFERER    => true,     // set referer on redirect                       
                    CURLOPT_SSL_VERIFYPEER=>true,
                    CURLOPT_MAXREDIRS      => 10,       // stop after 10 redirects                       
                     ));
                     $output = curl_exec($solve);
                     curl_close($solve);
                     return $output;
            }
            
            protected function userAgent(){
return "Mozilla/5.0 (Linux; Android 4.0.1; Nexus 10 Build/LMY48T; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Safari/537.36 TwitterAndroid";
                   /* return "Mozilla/5.0 (Linux; Android 4.4.2; SM-T230NU Build/KOT49H) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/30.0.0.0 Safari/537.36 TwitterAndroid";*/
            }

            protected function accountCookie(string $email){
                if (!file_exists(self::COOKIES_FOLDER."/".$email . ".txt")) {
                    $fh = fopen(self::COOKIES_FOLDER."/".$email . ".txt", 'w');  
                     fclose($fh);
                }

            }
            /**
            *log type.
            *0 => fail
            *1 => warning
            *2 => info
            *3 => success
            */
            protected function log(bool $success,$message,$screenName,$userId,$type){
                if($success === true){
                        $this->logMapper->log('تنبيه من العضويه : ( '. $screenName . ' ) : ' . $message, $userId  , $type);
                }else {
                        $this->logMapper->log('تنبيه من العضويه : ( '. $screenName . ' ) : ' . $message . "لا يمكننا الاتصال بتويتر في الواقت الحالي سيتم المحاوله",$userId , 0);
                }
        }
            /**
            *@method getRandomResponse This Function Must Handle Two Logic First Find If User Have Tweets In DataBase
            *Use it Else 
            *get from tweets.txt File
            *Updated @19/01/2018 To Use preg_split for all cases.
            */
            protected function getRandomResponse(string $hashtag = ''){
                //$tweets  = explode(PHP_EOL,file_get_contents('tweets.txt'));
                $tweets  = preg_split("/\\r\\n|\\r|\\n/",file_get_contents('tweets.txt'));
                $counter = count($tweets)-1;
                return $tweets[rand(0,$counter)] . "             
$hashtag";
            }


        } 
