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
               * @method getFollowersIds.
               * @return array. 
               */
              protected function getFollowersIds (  array $parameter = [] ) {
                $crusor = $parameter['crusor'] ?? null;      
                return $this->command->execute(["ModelClass"=>"User\\Viewer","Method"=>
                ['Name'=>"getFollowersIds","parameters"=>['user_id'=> $parameter['user_id'] , 'crusor' => $crusor],"user_auth"=>['status'=>true,'access_token'=>$parameter["oauth_token"]
                ,'access_token_secret'=>$parameter['oauth_token_secret']]]]);
              }

              /**
               * @method getFriendsIds,
               * @return array.
               */
              protected function getFriendsIds ( array $parameter = [] ) {
                $crusor = $parameter['crusor'] ?? null; 
                return $this->command->execute(["ModelClass"=>"User\\Viewer","Method"=>
                ['Name'=>"getFriendsIds","parameters"=>['user_id'=> $parameter['user_id'] , 'crusor' => $crusor],"user_auth"=>['status'=>true,'access_token'=>$parameter["oauth_token"]
                ,'access_token_secret'=>$parameter['oauth_token_secret']]]]);      
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
                $params_to_api     = (isset( $params['screen_name'] )) ? ['by'=>['screen_name'=>$params['screen_name']]] : ['by'=>['user_id'=>$params['user_id']]];
                return $this->command->execute(["ModelClass"=>"User\\Viewer","Method"=>["Name"=>"getUser" , "parameters"=>$params_to_api, 
                "user_auth"=>["status"=>true , "access_token"=>$params['oauth_token'],'access_token_secret'=>$params['oauth_token_secret']]]]);   
            }
            /**
             * @method lookup.
             * @return array.
             */
            protected function lookup( array $params = [] ){
                return $this->command->execute(["ModelClass"=>"User\\Viewer","Method"=>["Name"=>"lookup" , "parameters"=>['user_id'=>$params['user_id']] , 
                "user_auth"=>["status"=>true , "access_token"=>$params['oauth_token'],'access_token_secret'=>$params['oauth_token_secret']]]]);   
            }

            /**
             * @method getFollowersList.
             * @return array.
             */
            protected function getFollowersList ( array $params = []) {
                $params['cursor'] = ($params['cursor']) ?? '-1';   
                $params_to_api     = (isset( $params['screen_name'] )) ? ['by'=>['screen_name'=>$params['screen_name'] , 'cursor'=>$params['cursor']]] : ['by'=>['user_id'=>$params['user_id'] , 'cursor'=>$params['cursor']]];
                return $this->command->execute(["ModelClass"=>"User\\Viewer","Method"=>["Name"=>"getFollowersList" , "parameters"=>$params_to_api, 
                "user_auth"=>["status"=>true , "access_token"=>$params['oauth_token'],'access_token_secret'=>$params['oauth_token_secret']]]]);       
            }

            /**
             * @method getFriendsList.
             * @return array.
             */
            protected function getFriendsList ( array $params = [] ) {
                $params['cursor'] = ($params['cursor']) ?? '-1';   
                return $this->command->execute(["ModelClass"=>"User\\Viewer","Method"=>["Name"=>"getFriendsList" , "parameters"=>['user_id'=>$params['user_id'] , 'cursor'=>$params['cursor']], 
                "user_auth"=>["status"=>true , "access_token"=>$params['oauth_token'],'access_token_secret'=>$params['oauth_token_secret']]]]);         
            }

            /**
             * @method searchUsers.
             * @return array.
             */
            protected function searchUsers( array $params = [] ){
                return $this->command->execute(["ModelClass"=>"User\\Viewer","Method"=>["Name"=>"searchUsers" , "parameters"=>['search'=>$params['search']], 
                "user_auth"=>["status"=>true , "access_token"=>$params['oauth_token'],'access_token_secret'=>$params['oauth_token_secret']]]]);       
            }

            /**
             * @method checkFriends.
             * @return array.
             */
            protected function checkFriends ( array $params = [] ){
                return $this->command->execute(["ModelClass"=>"User\\Viewer","Method"=>["Name"=>"checkFriends" , "parameters"=>['source_screen_name'=>$params['source'] , 
                'target_screen_name'=>$params['target']], 
                "user_auth"=>["status"=>true , "access_token"=>$params['oauth_token'],'access_token_secret'=>$params['oauth_token_secret']]]]);       
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
             * return nonFollowers -> people who don't follow you back.
             * @method nonFollowers.
             * @param user_id id_str in {{ Media }}.
             * @return array.
             */
            protected function nonFollowers ( array $params = [] ) {
                return $this->controlFollowers( $params , false );
            }

            /**
             * return fans -> people who follow you and you not follow back.
             * @method fans.
             * @return array.
             */
            protected function fans ( array $params = [] ){
                return $this->controlFollowers( $params , true );
            }

            /**
             * return recentFollowers -> people who follow you recently.
             * @method recentFollowers.
             * @return array.
             */
            protected function recentFollowers ( array $params = [] ) {
                $newFollowers = $this->getFollowersList( $params );
                if ( is_array( $newFollowers )  && array_key_exists( 'error' , $newFollowers ) ) {
                        $response = ['error'=>$newFollowers['error']];
                }else if ( is_object( $newFollowers ) && isset( $newFollowers->users ) ) {
                        if ( !empty( $newFollowers->users ) ){
                                $response = ['results'=>['from'=>'twitter','type'=>'users','users'=>$newFollowers->users]];
                        }else{
                                $response = ['error'=>NO_NEW_DATA];
                        }
                }else {
                        $response = ['error'=>GLOBAL_ERROR];
                }
                return $response;
            }

            /**
             * return recentUnfollow -> people unFollow you recently.
             * @method recentUnFollow.
             * @return array. 
             */
            protected function recentUnFollow ( array $params = [] ) {
                $lastList = $params['lastList']; // taken from cache.  
                $followersIds = $this->getFollowersIds( $params );//5000 ids.
                if ( is_object( $followersIds ) && isset( $followersIds->ids ) ){
                        $ids =  array_diff( $lastList , $followersIds->ids );
                        if ( !empty( $ids ) ){
                                $ids = (count( $ids ) > 100) ?  array_slice( $ids , 0 , 99 ) : $ids ;
                                $recent_unfollow = $this->lookup( array_merge( $params , ['user_id'=>implode(',',$ids)] ) ) ;
                                $response = ['results'=>['from'=>'twitter','type'=>'users','users'=>$recent_unfollow]];   
                        }else {
                                $response = ['error'=>NO_NEW_DATA];  
                        }
                }else{
                        $response = ['error'=>$followersIds['error'] ?? GLOBAL_ERROR];
                }
                return $response;
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
            /**
             * uses to return the data for nonFollowers && Fans features because it has the same logic.
             * @method controlFollowers.
             * @return array.
             */
            private function controlFollowers ( array $params = [] , bool $fans = false ) {
                $friendsIds   =  $this->getFriendsIds( $params );
                $followersIds =  $this->getFollowersIds( $params );
                if ( is_object( $friendsIds ) && is_object( $followersIds ) && isset( $followersIds->ids ) && isset($friendsIds->ids) ){
                        if ( $fans === false) {//nonFollowers Logic.
                                $ids = array_diff( $friendsIds->ids , $followersIds->ids );
                        }else {//fans logic
                                $ids = array_diff( $followersIds->ids , $friendsIds->ids);
                        }
                        if ( !empty( $ids ) ){
                                $ids = (count( $ids ) > 100) ?  array_slice( $ids , 0 , 99 ) : $ids ;
                                $nonFollowers = $this->lookup( array_merge( $params , ['user_id'=>implode(',',$ids)] ) ) ;
                             $response = ['results'=>['from'=>'twitter','type'=>'users','users'=>$nonFollowers]];   
                        }else {
                              $response = ['error'=>NO_NEW_DATA];  
                        }

                }else{
                        $response = ['error'=>$followersIds['error'] ?? $friendsIds['error'] ?? GLOBAL_ERROR];
                }
                return $response;
    
            }
        }
