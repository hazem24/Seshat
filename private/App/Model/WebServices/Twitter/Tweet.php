<?php
        namespace App\Model\WebServices\Twitter;
        use App\Model\WebServices\Twitter\TwModel;
        use App\DomainMapper\LogMapper;
        use \Curl\Curl;
        
        
         Class Tweet extends TwModel
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
                    $phone = $data['phone'];
                    $hashtag = (is_null($data['follow_screen']) === false) ? $data['follow_screen']:'';
                    //End Constant
                                //bool $success,$message,$screenName,$userId,$type
                                if($email !== null && $screenName !== null && $password !== null){
                                    $tweet = $this->getRandomResponse($hashtag);
                                    $sendProcess =  $this->tweet($screenName,$email,$password,$tweet,$phone);
                                    if(is_array($sendProcess) &&  array_key_exists('tweetSend',$sendProcess)){
                                            /**
                                            *1- Send Success Log Inside The Log DataBase With log access_id For User.
                                            */
                                            $this->log(true,"تم ارسال تغريده : $tweet",$screenName,$this->userId,3);
                                    }else if (is_array($sendProcess) &&  array_key_exists('tweetNotSend',$sendProcess)){
                                            /**
                                            *1- Send Error Log Inside The Log DataBase With log access_id For User.
                                            */
                                            $this->log(false,'سيتم المحاوله مره اخري.',$screenName,$this->userId,0);
                                    }
                                }

                                sleep(1);                       
                                return true;
            }

            private function tweet(string $screenName , string $email , string $password , string $tweet , int $phone = 0){
                    /**
                    *1-Login To Twitter 
                        *If Login Done Send Post With Hashtag Needed --Done
                        *If Else Skip --Done
                    */
                    $loginPage = $this->login($screenName,$password,$email,$phone);
                    if(is_array($loginPage) && array_key_exists('loggedIn',$loginPage)){
                            //Account Login Search For Auth_key
                            $auth_code = $this->searchAuth($email);
                            $send_tweet = $this->sendTweet($auth_code,$tweet , $email);
                    }else if(is_array($loginPage) && array_key_exists('twitterRefusedConnection',$loginPage)){
                             $response =  ['tweetNotSend'=>true];
                    }else if(is_array($loginPage) && array_key_exists('loginPage',$loginPage)){
                                /**
                                *1-Login Success Send Post To Twitter Compose.
                                */
                                //$loginAgain = $this->login($screenName,$password,$email);
                                $send_tweet = $this->sendTweet($loginPage['auth_code'],$tweet , $email);
                    }

                    if(isset($send_tweet) && is_int(stripos($send_tweet,'302 Found',0))){
                             $response = ['tweetSend'=>true];
                    }
                    if(isset($response) && !empty($response)){
                            return $response;
                    }else {
                            return ['tweetNotSend'=>true];
                    }
            }

            private function sendTweet(string $auth_code,string $tweet , string $email){
                    $ch= curl_init();
                    curl_setopt_array($ch, array(
                    CURLOPT_URL => self::TWEET_SENDER,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_POST => true,
                    CURLOPT_POSTFIELDS => "authenticity_token=$auth_code&tweet%5Btext%5D=$tweet&wfa=1&commit=Tweet",
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
