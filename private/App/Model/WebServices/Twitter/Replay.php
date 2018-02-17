<?php
        namespace App\Model\WebServices\Twitter;
        use App\Model\WebServices\Twitter\TwModel;
        use App\DomainMapper\LogMapper;
        use \Curl\Curl;
        
        
         Class Replay extends TwModel
        {
            CONST TWEET_SENDER = TWEET_SENDER;
            protected $userAgent;
            protected $userId;
            protected $logMapper;

            /**
            *@method startProcess This method Have Any Logic Of 
            */
            public function startProcess(array $data = []){
                    //Constant For Any Process
                    $this->userId = $data['userId'];
                    $this->logMapper = new LogMapper;
                    $this->userAgent = $this->userAgent();
                    $screenName = $data['screenName'];
                    $email = $data['email'];
                    $password = $data['password'];
                    $replay_to = $data['follow_screen'];
                    $replay_text = (isset($data['replay_text'])&&$data['replay_text'] !== null)?$data['replay_text']:$this->getRandomResponse();
                    $sendProcess =  $this->replay($screenName,$email,$password,$replay_to,$replay_text);
                    if(is_array($sendProcess) &&  array_key_exists('replaySend',$sendProcess)){
                        $this->log(true,"تم الرد علي التغريده  => $replay_to",$screenName,$this->userId,3);
                    }else if (is_array($sendProcess) &&  array_key_exists('replayNotSend',$sendProcess)){
                              
                        $this->log(false,'لم يستطيع عمل رد.',$screenName,$this->userId,0);
                    }                    
                        sleep(1);                       
                        return true;
            }

            private function replay(string $screenName , string $email , string $password , string $replay_to , string $replay_text = 'gotrend.today'){
                    /**
                    *1-Login To Twitter 
                        *If Login Done Send Post With Hashtag Needed --Done
                        *If Else Skip --Done
                    */
                    $loginPage = $this->login($screenName,$password,$email,0);
                    if(is_array($loginPage) && array_key_exists('loggedIn',$loginPage)){
                            //Account Login Search For Auth_key
                            $auth_code = $this->searchAuth($email);
                            $send_follow = $this->send_replay($auth_code,$replay_to ,$replay_text,$email);
                    }else if(is_array($loginPage) && array_key_exists('twitterRefusedConnection',$loginPage)){
                             $response =  ['replayNotSend'=>true];
                    }else if(is_array($loginPage) && array_key_exists('loginPage',$loginPage)){
                                $send_follow = $this->send_replay($loginPage['auth_code'],$replay_to , $replay_text ,$email);
                    }

                    if(isset($send_follow) && is_int(stripos($send_follow,'302 Found',0))){
                             $response = ['replaySend'=>true];
                    }
                    if(isset($response) && !empty($response)){
                            return $response;
                    }else {
                            return ['replayNotSend'=>true];
                    }
            }

            private function send_replay(string $auth_code,string $replay_to , string $replay_text ,string $email){
                    $ch= curl_init();
                    curl_setopt_array($ch, array(
                    CURLOPT_URL => TWEET_SENDER,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_POST => true,
                    CURLOPT_POSTFIELDS => "authenticity_token=$auth_code&tweet[in_reply_to_status_id]=$replay_to&tweet[text]=$replay_text&wfa=1&commit=Reply",
                    CURLOPT_COOKIEJAR =>  realpath(self::COOKIES_FOLDER."/" . $email . ".txt"),
                    CURLOPT_COOKIEFILE => realpath(self::COOKIES_FOLDER."/" . $email . ".txt"),
                    CURLOPT_COOKIE     => realpath(self::COOKIES_FOLDER."/" . $email . ".txt"),                       
                    CURLOPT_FOLLOWLOCATION => false,
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

            private function searchAuth(string $email){
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
        
                $ch      = curl_init( self::TWEET_SENDER );
                curl_setopt_array( $ch, $options );
                $composeTweet = curl_exec( $ch );
                curl_close( $ch ); 
                return $this->getAuthication($composeTweet);  

            }
        } 
