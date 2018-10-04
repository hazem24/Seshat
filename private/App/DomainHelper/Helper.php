<?php
        namespace App\DomainHelper;
        use Framework\Registry;
        use Framework\Request\RequestHandler;
        use Framework\Helper\ArrayHelper;

        
        /**
        *Provide Some Helper Function Can Be Used In The Domain Logic App
        */

        Class Helper extends BaseHelper
        {
            
            /**
             * @property media.
             * */    
            private static $media = [1=>'twitter'];
                
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

            /**
             * @method checkLang Responsable for check the incoming lang for translate (From-To)
             * @return true in success || string of error other wise
             */

             public static function checkLang(string $to){
                    $whiteList = ['ar','ru','tu','de','tr','fr','es','en'];
                    if(in_array(strtolower($to),$whiteList)){
                        return true;
                    }
                        return LANG_NOT_SUPPORTED;
             }
             /**
              * this method return last post user created as text.
              * @method getLastPostText.
              * @return array.
              */
             public static function getLastPostTextByUser( $post , string $media ){
                switch ($media) {
                        case 'twitter':
                                if ( is_object( $post ) && isset($post->status) ){
                                       $text    =  ( isset($post->status->retweeted_status ) ) ? $post->status->retweeted_status->full_text :  $post->status->full_text;
                                       $return  = ['post_id'=>$post->status->id_str,'text'=>$text];
                                }
                                break;
                        
                        default:
                                //Nothing here.
                                break;
                }
                return $return ?? [];
             }

                /**
                 * @method percentage.
                 * @return percentage of any value provide to it.
                 */
                public static function percentage (int $value , int $total , bool $provide_percentage_symbol = true){
                        //Simple check to avoid div. by zero.
                        $total = ($total <= 0) ? 1 : $total; 
                        $percentage_value = ceil((($value / $total) * 100));
                        if($provide_percentage_symbol === true){
                                $percentage_value .= '%';
                        }
                        return $percentage_value;         
                }

                /**
                 * @method peopleParticipated .. people that participated in something.
                 * @return array.
                 */
                public static function peopleParticipated (array $data):array {
                        $people = [];
                        foreach ($data as $key => $tweet) {
                                //To prevent the uses of array_unique.
                                $people['users'][$tweet->user->screen_name] = $tweet->user->followers_count;
                                $people['profile_img'][$tweet->user->screen_name] = $tweet->user->profile_image_url;
                        }
                        return $people;
                }

                /**
                 * @method totalImpressions .. calc. the total impressions.
                 * @param users must be in this form ['{{screen_name}}'=>followers_count] which exactly can be get by self::peopleParticipated method.
                 * @return int.
                 */
                public static function totalImpressions (array $users):int {                        
                        return (int)array_sum($users);
                }

              /**
               * @method tweetsClassifier classifiy tweets ['media','retweets','text']. 
               * @return array.
               */
              public static function tweetsClassifier (array $tweets):array{
                      $classifier = []; 
                      $classifier['media'] = []; $classifier['text'] = []; $classifier['retweet'] = [];
                     if(!empty($tweets)){
                                foreach ($tweets as $key => $tweet) {
                                        //Media.
                                        if(isset($tweet->extended_entities->media[0]) || isset($tweet->retweeted_status->extended_entities->media[0]) || (isset($tweet->entities->urls) && !empty($tweet->entities->urls))){
                                                $classifier['media'][] = $tweet;
                                        }else { //text.
                                                $classifier['text'][]  = $tweet;
                                        }
                                        //Retweet.
                                        if(isset($tweet->retweeted_status)){
                                                $classifier['retweet'][] = $tweet;
                                        } 
                                }
                     } 
                     return $classifier;
              }

              /**
               * @method extractReplies.
               * @return array.
               */
              public static  function extractReplies(array $tweets,string $tweet_id = ''):array{
                        $replies = [];
                        if(  is_array($tweets) && !empty($tweets) ){
                               $replies = self::repliesLoop($tweets,$tweet_id);
                        }
                return $replies;        
              }

              /**
               * @method extractRetweets.
               * @return array.
               */
              public static function extractRetweets(array $data):array{
                     return array_filter(array_map(function($elem){
                           if(isset($elem->retweetd_status)){
                                return $elem;
                           }     
                     },$data));   
              }

              /**
               * @method extractMedia media here is (links-images-videos).
               * @return array.
               */
              public static function extractMedia(array $data):array{
                     return array_filter(array_map(function($elem){
                          if(isset($elem->extended_entities->media[0]) || isset($elem->retweeted_status->extended_entities->media[0])){
                                return $elem;
                          }
                     },$data));   
              } 
              /**
               * @method extractText.
               * @return array.
               */
              public static function extractText(array $data):array{
                        return array_filter(array_map(function($elem){
                                if(!isset($elem->extended_entities->media[0]) && !isset($elem->retweeted_status->extended_entities->media[0])){
                                        return $elem;
                                }
                        },$data));    
              }

              /**
               * @method createHashReportName.
               */
              public static function createHashReportName():string{
                     return uniqid(); // length = 13.
              }

              /**
               * @method orgHashReportData org. report data.
               * @return array.
               */
              
               public static function orgHashReportData(array $reportData , string $hashTag , string $created_by = 'seshatApp'){
                      $number_of_tweets        = count($reportData);  
                      $people_participated     = self::peopleParticipated($reportData);
                      $tweetClassifier         = self::tweetsClassifier($reportData);
                      $replies_tweets          = self::extractReplies($reportData);
                      $most_users_by_followers = ArrayHelper::arsortArray($people_participated['users']);
                      return [
                        'hashtag'=>$hashTag,
                        'number_of_tweets'=> $number_of_tweets,
                        'created_at'=> date("Y-m-d"),'created_by'=> $created_by,
                        'statistics'=> self::hashTagStatistics($tweetClassifier['text'],$tweetClassifier['retweet'],$tweetClassifier['media'],$replies_tweets,$number_of_tweets),
                        'people_participated'=> count($people_participated['users']),
                        'impressions'=> self::totalImpressions($people_participated['users']),
                        'most_users_by_number_of_followers' => ['user'=>$most_users_by_followers,'profile_img'=>$people_participated['profile_img']],
                        'tweets'=>['text'=>$tweetClassifier['text'],'retweets'=>$tweetClassifier['retweet'],'media'=>$tweetClassifier['media'],'replies'=>$replies_tweets]   
                      ];
               }

               /**
                * this method has all media that seshat provided && check if the provided media in {{ $media }} param. is provided.
                * @method issetMedia.
                * @return bool.
                */

                public static function issetMedia ( string $media ) {
                       return (bool) in_array( strtolower($media) , self::$media );
                }

                /**
                 * this method return true if user {{ source }} follow user {{ target }} in specific media.
                 * @method getRelation.
                 * @return bool.
                 */
                public static function follow( $mediaChecker , string $media ){
                     switch (strtolower($media)) {
                             case 'twitter':
                                $return = ( is_object( $mediaChecker ) && $mediaChecker->relationship ) ?? null;
                                if ( is_null( $return ) === false){
                                    $return =  $mediaChecker->relationship->source->following;  
                                }
                                break;
                             default:
                              $return =  null;  
                                break;
                     }
                     return $return;
                }

                /**
                 * this media return the number of {{ Media }} .. must be used after issetMedia check method.
                 * @property mediaToNumber.
                 * @return int.
                */
                public static function mediaToNumber( string $media ){
                        return array_search( strtolower($media) , self::$media );      
                }
               /**
                * @method hashTagStatistics.
                * @return assoc.array with statistics data for specific hashtag. 
                */
               private static function hashTagStatistics (array $text_tweets , array $retweets_tweets , array $media_tweets , array $replies_tweets , int $number_of_tweets):array{  
                       return ['text_precentage'  =>   self::percentage(count($text_tweets),$number_of_tweets,false),
                              'retweet_precentage'=>   self::percentage(count($retweets_tweets),$number_of_tweets,false),
                              'replies_precentage'=>   self::percentage(count($replies_tweets),$number_of_tweets,false),
                              'media_precentage'  =>   self::percentage(count($media_tweets),$number_of_tweets,false)
                        ];
               }

               /**
                * @method repliesLoop.
                * @return array.
                */
                private static function repliesLoop (array $tweets , string $tweet_id = ''){
                        $replies = [];
                        foreach ($tweets as $key => $value) {
                                if(!empty($tweet_id)){
                                        if($value->in_reply_to_status_id_str == $tweet_id){
                                                $replies[] = $value;
                                        }           
                                }else{
                                        if(is_null($value->in_reply_to_status_id_str) === false){
                                                $replies[] = $value;
                                        }
                                }
                                
                        }
                        return $replies;
                } 
        }