<?php
        namespace App\DomainHelper\Twitter;
        use Framework\Shared\CommandFactory;
        use App\DomainHelper\Helper;

        /**
         * Class Read Responsable For All Logic Of Read Something From Twitter.
         */

        class Read extends Action
        {
            
            public function __construct(){
                   $this->command = $this->initCommand();
            }
            protected function initCommand(){
                    return CommandFactory::getCommand('twitterApi');
            }
            /**
             * @method readTimeLine Read time line of oAuth User.
             */
            protected function readTimeLine(array $parameter = []){
                      return $this->command->execute(["ModelClass"=>"Tweet\\Viewer","Method"=>
                      ['Name'=>"readTimeLine","parameters"=>[],"user_auth"=>['status'=>true,'access_token'=>$parameter["oauth_token"]
                      ,'access_token_secret'=>$parameter['oauth_token_secret']]]]);
            }
            /**
             * @method searchTweets search tweets for specific words.
             */
            protected function searchTweets(array $parameter = []){
                $since_id = (isset($parameter['since_id']) && !empty($parameter['since_id']))?$parameter['since_id']:null;
                $until = (isset($parameter['until']) && !empty($parameter['until']))?$parameter['until']:null; 
                $result_type = (isset($parameter['result_type']) && !empty($parameter['result_type']))?$parameter['result_type']:'mixed';
                $max_id = (isset($parameter['max_id']) && !empty($parameter['max_id']))?$parameter['max_id']:null;   
                return $this->command->execute(["ModelClass"=>"Tweet\\Viewer","Method"=>
                ['Name'=>"searchTweets","parameters"=>['q'=>$parameter['search'],'result_type'=>$result_type,'max_id'=>$max_id,'until'=>$until,'since_id'=>$since_id],"user_auth"=>['status'=>true,'access_token'=>$parameter["oauth_token"]
                ,'access_token_secret'=>$parameter['oauth_token_secret']]]]);

            }

            /**
             * @method getRetweets responsable for return Max 100 Users retweets specific tweet.
             */

             protected function getRetweets(array $parameter = []){
                $tweet_id = $parameter['tweet_id'] ;
                return $this->command->execute(["ModelClass"=>"Tweet\\Viewer","Method"=>
                ['Name'=>"getRetweets","parameters"=>['tweet_id'=>$tweet_id],"user_auth"=>['status'=>true,'access_token'=>$parameter["oauth_token"]
                ,'access_token_secret'=>$parameter['oauth_token_secret']]]]);
       
             }

             /**
              * @method favList return Max 200 tweets liked by specific users.
              */
              protected function favList(array $parameter = []){
                        return $this->command->execute(["ModelClass"=>"Tweet\\Viewer","Method"=>
                        ['Name'=>"favList","parameters"=>['screenName'=>$parameter['screenName']],"user_auth"=>['status'=>true,'access_token'=>$parameter["oauth_token"]
                        ,'access_token_secret'=>$parameter['oauth_token_secret']]]]);
              }
              /**
               * @method showTweet return specific tweet based on id. 
               */

              protected function showTweet(array $parameter = []){
                        return $this->command->execute(["ModelClass"=>"Tweet\\Viewer","Method"=>
                        ['Name'=>"showTweet","parameters"=>['tweet_id'=>$parameter['tweet_id']],"user_auth"=>['status'=>true,'access_token'=>$parameter["oauth_token"]
                        ,'access_token_secret'=>$parameter['oauth_token_secret']]]]);
              } 
            /**
             * Twitter Components read section.
             * have all methods that have mixed of one or more than method from read method.
             */

            /**
             * @method getReplies get Replies for specific tweet.
             */
            protected function getReplies(array $params = []){
                $screenName = $params['screenName'];
                $search = "to:$screenName";
                $data = ['search'=>"$search",'since_id'=>$params['tweet_id'],'oauth_token'=>$params['oauth_token'],'oauth_token_secret'=>$params['oauth_token_secret']];  
                $mentions =  $this->searchTweets($data);
                $replies  =  Helper::extractReplies($mentions->statuses,$params['tweet_id']);
                if(!empty($replies)){
                        return $replies;
                }else{
                        return ['noReplies'=>true];
                }  
            }
            /**
             * @method userTimeLine.
             * @return array.
             */
            protected function userTimeLine (array $params = []) { 
                return $this->command->execute(["ModelClass"=>"Tweet\\Viewer","Method"=>["Name"=>"userTimeLine" , "parameters"=>['screen_name'=>$params['screen_name']] , 
                "user_auth"=>["status"=>true , "access_token"=>$params['oauth_token'],'access_token_secret'=>$params['oauth_token_secret']]]]);   
            }
            /**
             * @method getUser.
             * @return array.
             */
            protected function getUser (array $params = []) { 
                return $this->command->execute(["ModelClass"=>"Tweet\\Viewer","Method"=>["Name"=>"getUser" , "parameters"=>['screen_name'=>$params['screen_name']] , 
                "user_auth"=>["status"=>true , "access_token"=>$params['oauth_token'],'access_token_secret'=>$params['oauth_token_secret']]]]);   
            }

            /**
             * @method getFollowersList.
             * @return array.
             */
            protected function getFollowersList ( array $params = []) {
                $params['cursor'] = ($params['cursor']) ?? '-1';   
                return $this->command->execute(["ModelClass"=>"Tweet\\Viewer","Method"=>["Name"=>"getFollowersList" , "parameters"=>['screen_name'=>$params['screen_name'] , 'cursor'=>$params['cursor']], 
                "user_auth"=>["status"=>true , "access_token"=>$params['oauth_token'],'access_token_secret'=>$params['oauth_token_secret']]]]);       
            }

            /**
             * @method getHashtagData.
             */
            protected function getHashtagData(array $params){
                $tweets = $this->searchTweetsSevenDays($params['hashtag'],$params['oauth_token'],$params['oauth_token_secret']);
                return $tweets;
            }

            /** 
             * @note max tweets can be returned by this method = 2000 which is equ. to count($tweet) = 20.
             * @method searchTweetSevenDays.
             * @return array.
                * empty no data found.
                * !empty with data.
                * key error .. error returned from twitter Api.
             */
            private function searchTweetsSevenDays(string $search, string $oauth_token , string $oauth_token_secret){
                $tweets = [];
                $max_id  = null;
                $repeat  = 20;
                while ( true ) { 
                        
                        $search_tweets = $this->searchTweets(['search'=>$search ,'result_type'=>'recent','max_id'=>$max_id,'oauth_token'=>$oauth_token,'oauth_token_secret'=>$oauth_token_secret]);
                        if(isset($search_tweets->statuses) && !empty($search_tweets->statuses) && is_array($search_tweets->statuses)){
                                $tweets[]       = $search_tweets->statuses;
                                $count_statues  = count($search_tweets->statuses); // Count destroyed  the performance!.
                                $count_tweets   = count($tweets);
                                if(  ( $repeat == 0 ) || ( $count_tweets == 20 ) ){ 
                                        break;                      
                                }else if ( $count_statues == 100 ){
                                        //Search  about new interval of tweets.
                                        $last_tweet = array_pop( $search_tweets->statuses );
                                        if( isset( $last_tweet->id_str ) ){
                                                $max_id   = $last_tweet->id_str;
                                        }  
                                }
                        }else if ( is_array($search_tweets) && array_key_exists('error',$search_tweets) && empty($tweets) ){
                                //Handle the error that can be came from twitter Api as error response.
                                $tweets  = $search_tweets;
                                break;
                        }else {
                                break;//No data found || empty array response found !.
                        } 
                        --$repeat;
                }
                return $tweets;
            }
        }
