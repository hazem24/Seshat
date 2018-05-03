<?php
        namespace App\DomainHelper\Twitter;
        use Framework\Shared\CommandFactory;

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
                return $this->command->execute(["ModelClass"=>"Tweet\\Viewer","Method"=>
                ['Name'=>"searchTweets","parameters"=>['q'=>$parameter['search'],'since_id'=>$since_id],"user_auth"=>['status'=>true,'access_token'=>$parameter["oauth_token"]
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
                $replies = [];

                if( is_object($mentions) && isset($mentions->statuses)  && is_array($mentions->statuses) && !empty($mentions->statuses)){
                        foreach ($mentions->statuses as $key => $value) {
                                if($value->in_reply_to_status_id_str == $params['tweet_id']){
                                        $replies[] = $value;
                                }
                        }
                }
                
                if(!empty($replies)){
                        return $replies;
                }else{
                        return ['noReplies'=>true];
                }  
            }
         }
