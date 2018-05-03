<?php
        namespace App\Model\WebServices\Twitter\Api;
        use App\Model\WebServices\__Services;
        use Abraham\TwitterOAuth\TwitterOAuth;
        
        /**
         * This Class Provide Base Class For All Services Which Will Use Twitter
         */
        Class TwitterApi
        {
            CONST CONSUMER_KEY ='aunVGBbtjyWAFiZhp9lZJ2pSD';
            CONST CONSUMER_SECRET ='UcuWgqSaruS8o1NY43gKaBZkMDLrJIfOEfLie0nKaPE7Eteey5';

            protected $access_token = null;
            protected $access_token_secret = null;


            protected $connection;

            /**
             * @param $user_auth is a flag to determine if process will use (access_token,_secret) for specific user or global app Scope.
             * true => user token & secret Will Used.
             * false => App Key & Secret Will Used.
             */
            protected $user_auth = false;

            /**
            *@method startProcess. 
            */
            final public function startProcess(array $data = []){
                    //Check The Scope.
                    $this->user_auth = (isset($data['user_auth']['status']))?(bool)$data['user_auth']['status']:$this->user_auth;
                    if($this->user_auth === true){
                            $this->access_token = (string) $data['user_auth']['access_token'];
                            $this->access_token_secret = (string) $data['user_auth']['access_token_secret'];
                    }
                    $this->initilization();
                    if(method_exists($this,$data['method'])){
                        $method_name = $data['method'];
                        $parameter = (isset($data['parameter'])) ? $data['parameter']:null;
                        return $this->$method_name($parameter);
                    }
                    
            }


            final protected function initilization(){
                if(is_null($this->access_token) === false && is_null($this->access_token_secret) === false){
                        $this->connection = new TwitterOAuth(self::CONSUMER_KEY, self::CONSUMER_SECRET,$this->access_token,$this->access_token_secret);
                }else{
                        $this->connection = new TwitterOAuth(self::CONSUMER_KEY, self::CONSUMER_SECRET);
                }
                    $this->connection->setTimeouts(100, 150);  
            }
            /**
             * @method apiResponse Check The response From the Twitter Api according to "https://developer.twitter.com/en/docs/basics/response-codes".
             * 
             * @return mixed 
                    *@return true if the api response is successfully.
                    *@return string if the api response is Error and return Error Message According to this response.
             */
            final protected function apiHttpResponse(){
                  switch ((int)$this->connection->getLastHttpCode()) {
                      case 200:
                          $response = true;
                          break;
                      case 304: // No data to modified.
                           $response = NO_NEW_DATA; 
                          break; 
                      case 404:
                           $response = NOT_FOUND_404_TWITTER;   
                           break;
                      case 429:
                            $response = SESHAT_NEED_TIME;
                            break; 
                      case 500 || 504:
                            $response = TWITTER_DOWN;
                            break;
                      case 401:
                            $response = REVOKED_SESHAT;     
                      default:
                          $response   = DEFAULT_TWITTER_HTTP_ERROR;
                          break;
                  }

                  return $response;
            }

            /**
             * @method anyApiError This Method Check If The Api Response Has Any Error.
             * @param response the last repsponse return with twitter Api.
             * @return Mixed
             *      *@return false if there is No Error.
                    *@return string if there is any Error.
             */
            final protected function anyApiError($response){
                        if(isset($response->errors) === true){//There's An Error.
                                switch ((int)$response->errors[0]->code) {
                                    case 88:
                                        $error = SESHAT_NEED_TIME;
                                        break;
                                    case 89:
                                        $error = ["reauth"=>REVOKED_SESHAT];
                                        break;
                                    case 135:
                                        $error = SYSTEM_CLOCK_INVAILED;
                                        break;
                                    case 144:
                                        $error = WRONG_TWEET_ID;
                                        break;
                                    case 186:
                                        $error = MIN_TWEET_LENGTH;
                                        break;
                                    case 231:
                                        $error = VERFIY_LOGIN_WITH_TWITTER;
                                        break;
                                    case 327:
                                        $error = DUPLICATE_RETWEET;
                                        break;
                                    case 34:
                                        $error = NOT_FOUND;
                                        break;
                                    case 17:
                                        $error = NOT_FOUND;
                                        break; 
                                    case 150:
                                        $error = FOLLOW_TO_DIRECT;
                                        break;
                                    case 161:
                                        $error = LIMIT_FOLLOW;
                                        break;
                                    case 179:
                                        $error = INVAILED_ACTION;
                                        break;
                                    case 187:
                                        $error = DUPLICATE_TWEET;
                                        break; 
                                    case 220:
                                        $error = NOT_ACCESS_RESOURCE;
                                        break;                                              
                                    default:
                                        $error = DEFAULT_TWITTER_HTTP_ERROR;
                                        break;
                                }  
                        }else{
                                    $error = false;
                        }
                     return $error;
            }
            
            /**
             * @method getResponse repsonsable for return the response to user.
             * @return array|object.
             */
            protected function getResponse($response){
                $anyApiError  = $this->anyApiError($response);
                if($anyApiError === false){
                        return $response;
                }
                        return ['error'=>$anyApiError];

            }

        } 
