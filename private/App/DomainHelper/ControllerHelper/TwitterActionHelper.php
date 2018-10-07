<?php
    namespace App\DomainHelper\ControllerHelper;
    use Framework\Error\WebViewError;
    use App\DomainHelper\FrontEndHelper;
    use App\DomainHelper\Helper;
    use App\DomainHelper\DateTrait;
    use Framework\Request\RequestHandler;
    use Framework\Lib\Security\Data\FilterDataFactory;
    use App\DomainHelper\Twitter;
    use App\DomainHelper\BaseHelper;
    use App\Controller\TwitterAction;
    


    /**
    * this class a helper class for {{ TwitterActionController }}
    */

    class TwitterActionHelper extends BaseHelper
    {
        use DateTrait;
     
        public static function composeTweet ( TwitterAction $twitterAction ) {
            /**
            * If Comming Request Is PublishNow  =>User Want To Send Tweet To Twitter.
            * If Comming Request Is Schedule => User Want To Scedule.
            */
            if(RequestHandler::postRequest()){
                $publishNow = (bool)RequestHandler::post('publish');
                $schedule   = (bool)RequestHandler::post('schedule');  
                $category = (int)RequestHandler::post("category");
                $tweetContent = (string)RequestHandler::post("tweetContent");
                $seshatPublicAccess = (bool)RequestHandler::post("seshatPublicAccess");

                if($publishNow === true){
                    //Publish Now Logic=>Table seshat_publish.
                    $tweet_id = (string)RequestHandler::post("tweet_id");//Incase of user want to replay to specific tweet.
                    $publishNow = self::newTweet( $twitterAction ,  $category,$tweetContent,$seshatPublicAccess,false,$tweet_id );
                }elseif($schedule === true){
                    //Schedule Logic=>Table seshat_schedule.
                    $scehduleTime = (string)RequestHandler::post("scehduleTime");// include PM AM.
                    if(self::validDate($scehduleTime , 'Africa/Cairo') === false){
                        $twitterAction->setError(INVAILD_DATE);
                    }else{
                        $schedule = self::newTweet( $twitterAction ,$category,$tweetContent,$seshatPublicAccess,true );
                    }
                }
                //Return Reponse To User.
                $response  = $twitterAction->returnResponseToUser((isset($publishNow) && is_array($publishNow))?$publishNow:$schedule);
                $twitterAction->encodeResponse($response);           
            }
            $twitterAction->encodeResponse(["code"=>403,'error'=>"Protected Area You Cannot Access."]);
        }

        public static function doHelper ( TwitterAction $twitterAction ) {
            if(RequestHandler::postRequest()){
                $action_type = strtolower((string)RequestHandler::post('type'));
                $tweet_id = (string)RequestHandler::post('tweet_id');
                $key = (int)RequestHandler::post("key");//To Update The Cache Key of tweet items in the cache.
                $replay_context = (bool)RequestHandler::post("replay_context");//The (like-unlike-retweet-unretweet) not coming from replay context.
                $cached         = (bool)RequestHandler::post("cached");//To Save Tweeta in cached (Important for timeLine Tweets).
                $twitter_response = self::doTypeLogic( $twitterAction , $action_type,$tweet_id,$key,$replay_context,$cached);
                //Return Reponse To User.
                $response = $twitterAction->returnResponseToUser($twitter_response);
                $twitterAction->encodeResponse($response);
            }
            $twitterAction->encodeResponse(["code"=>403,'error'=>"Protected Area You Cannot Access."]);
        }

        public static function createRelation ( TwitterAction $twitterAction){
            if(RequestHandler::postRequest()){
                $relation_with  = (string) RequestHandler::post('with');
                $relationType   = (string) RequestHandler::post('type');
                $createRelation = self::doRelation(  $twitterAction , $relation_with , $relationType );
            }
            $twitterAction->encodeResponse(['code'=>404,'error'=>['Not Found.']]);
        }

        private static function newTweet( TwitterAction $twitterAction , $category,$tweetContent,$seshatPublicAccess,bool $schedule = false,string $tweet_id = ''){
            /**
            * 1 - If Tweet Have Media (Image) Check If Image Is Good And Size Is <= 5MB.
            * 2 - If Tweet Is Text Only Check The Text Chars <= 280.
            * 3 - The Two Scenrio (Image + Text).
            */ 
            $data = [TWEET_CONTENT=>['value'=>$tweetContent , 'type'=>'string','min'=>1, 'max'=>280],
                    ];
                $filter = new FilterDataFactory($data);
                $filter = $filter->getSecurityData();
                $categoryType = array_keys(Helper::categoryType($category))[0];
                $anyError = WebViewError::anyError('form' , $data , $filter);
                if(is_array($anyError)){
                    $twitterAction->setError(WebViewError::userErrorMsg($anyError));
                }else{
                    if(isset($_FILES['tweetMedia']) && !empty((string)$_FILES['tweetMedia']['name']) && !empty((string)$_FILES['tweetMedia']['tmp_name'])){
                        //Image + Text.
                        if($schedule === false){
                                $tweet = self::newTweetLogic($twitterAction , $filter[TWEET_CONTENT],$categoryType,$seshatPublicAccess,false,true,$tweet_id);         
                        }else{
                                $tweet = self::newTweetLogic($twitterAction , $filter[TWEET_CONTENT],$categoryType,$seshatPublicAccess,true,true);
                        }
                            
                    }else{
                        //Text Only.
                        if($schedule === false){
                            $tweet = self::newTweetLogic($twitterAction , $filter[TWEET_CONTENT],$categoryType,$seshatPublicAccess,false,false,$tweet_id);
                        }else{
                            $tweet = self::newTweetLogic($twitterAction , $filter[TWEET_CONTENT],$categoryType,$seshatPublicAccess,true);
                        }
                    }
                    
                    //Response Logic Same For Two Types.
                    if(array_key_exists('error',$tweet)){
                        if($tweet['error'] === true){
                            $twitterAction->setError(TWEET_NOT_SAVED);
                        }else{
                            $twitterAction->setError($tweet['error']);
                        }
                    }else if (array_key_exists('uploadError',$tweet)){
                        $uploadError   =  WebViewError::anyError('upload',$tweet);
                        $twitterAction->setError(WebViewError::userErrorMsg($uploadError));
                    }else if(array_key_exists('success',$tweet)){
                        return ['success'=>TWEET_UPLOAD_SUCCESS];
                    }else if(array_key_exists('task_save',$tweet)){
                        return ['success'=>TASK_SCHEDULE_SAVED];//scheduled.
                    }else if (array_key_exists('task_not_save',$tweet)){
                        $twitterAction->setError(TASK_NOT_SCHEDULE_SAVED);
                    }else if (array_key_exists("scheduleExist",$tweet)){
                        $twitterAction->setError(HAVE_SCHEDULE_AT_SAME_TIME);
                    }else{
                        $twitterAction->setError(GLOBAL_ERROR);
                    }      

                }
        }


        /**
        * @method newTweetLogic Handle Logic Of Send Direct Tweet To Twitter.
        * @return array.
        */
        private static function newTweetLogic(TwitterAction $twitterAction , string $tweetContent,int $categoryType,bool $seshatPublicAccess,bool $schedule=false,bool $media = false,string $tweet_id = ''){
            $data = ['access_token'=>$twitterAction->session->getSession('oauth_token'),
            'access_token_secret'=>$twitterAction->session->getSession('oauth_token_secret'),'tweetContent'=>$tweetContent,
            'user_id'=>(int)$twitterAction->session->getSession('id'),
            'license_type'=> $twitterAction->session->getSession('license_type') ,
            'category'=>$categoryType,'media'=>$media,'publicAccess'=>$seshatPublicAccess,'tweet_id'=>$tweet_id];
            if($schedule === false){
                $class = new Twitter\Send;
                $method = "publishNewTweet";
            }else{
                $class = new Twitter\Task;
                $method = "addNewTask";
                $data['task_id'] = 1;
                $data['expected_finish'] = self::$date . ':00';//Coming From Date Trait.
            }
            return $class->do($method,$data);  
        }

        /**
        * @method doTypeLogic do right logic based on user chooses.
        * @return ||void.
        */
        private static function doTypeLogic( TwitterAction $twitterAction , string $type,string $tweet_id,int $key,bool $replay_context = false , bool $cached = false){
            switch ($type) {
                case 'retweet':
                    $response = self::newAction( $twitterAction ,'retweet',$tweet_id);
                    $successMsg = RETWEETED;
                break;
                case 'unretweet':
                    $response = self::newAction( $twitterAction  , 'unretweet',$tweet_id);
                    $successMsg = UNRETWEETED;
                break; 
                case 'like':
                    $response = self::newAction( $twitterAction , "like",$tweet_id);
                    $successMsg = LIKED;
                break;
                case 'unlike':
                    $response = self::newAction( $twitterAction , 'unlike',$tweet_id);
                    $successMsg = UNLIKED;
                break;    
                case 'deletetweet':
                    $response = self::newAction( $twitterAction , 'deleteTweet' , $tweet_id );
                    $successMsg = TWEET_DELETED;     
                break;
                default:
                $twitterAction->setError(CANNOT_CREATE_YOUR_ACTION);
                break;
            }
          
            if(isset($response)){
                if(is_object($response)){
                    $userId = $twitterAction->session->getSession('id');
                    $cache  = $twitterAction->fastCache();//set cache instances at cache property in AppShared Controller.
                    //Update The Tweet In Cache.
                    if((bool)$replay_context == false && $cached === true){
                        if(($type === 'retweet') || ($type === 'like')){
                            //increment tweet in cache system.
                            $cache->incrementTweet($type,$key,'userTimeLine'.$userId,470);
                        }else if (($type === 'unretweet') || ($type === 'unlike')){
                            //decrement tweet in cache system.
                            $cache->decrementTweet($type,$key,'userTimeLine'.$userId,470);
                        }       
                    }     
                    return ['success'=>$successMsg];
                }else if (is_array($response) && array_key_exists('error',$response)){
                    $twitterAction->setError($response['error']);
                } 
            }
        }

        /**
        *@method doRelation.
        */
        private static function doRelation (TwitterAction $twitterAction , string  $userId , string $relationType = '') {
            
            switch (strtolower($relationType)) {
                case 'follow':
                    //Follow Here.
                    $response   = self::newAction( $twitterAction , 'follow' , $userId , 'User'); 
                    $response   = (is_object($response)) ? (isset($response->screen_name))  ? (isset($response->status)) ? ['follow'=>true] : ['follow_request_sent'=>true]  : $twitterAction->setError($response) : ['error'=> [CANNOT_CREATE_YOUR_ACTION]];
                    break;
                case 'unfollow':
                    //UnFollow Here.
                    $response   = self::newAction( $twitterAction , 'unfollow' , $userId , 'User' );
                    $response   = (is_object($response)) ? (isset($response->screen_name))  ? ['unfollow'=>true] : $twitterAction->setError($response) : ['error'=> [CANNOT_CREATE_YOUR_ACTION]];
                    break;
                case 'undo_follow_request':
                    $response   = ['error'=>[THIS_FEATURE_NOT_FOUND]];    
                    break;
                default:
                    $twitterAction->encodeResponse(['code'=>404,'error'=>[INVALIAD_REQUEST]]);
                    break;
            }
            $twitterAction->commonError($response);
            $response = $twitterAction->returnResponseToUser($response);
            $twitterAction->encodeResponse($response);
        }

        /**
         *@method newAction send new action for specific tweet to twitter.
         *@param type =>type of action (retweet,like,unretweet,unlike) , params => id of the tweet || user_id.
         *@return
         */
        private static function newAction( TwitterAction $twitterAction , string $type, $params , string $scope = 'Tweet'){
           $data = ['parameters'=>$params,
                   'type'=>$type,'scope'=>$scope];
           $send_to_twitter = new Twitter\Send;
           return $send_to_twitter->do('writeToTwitter',array_merge($data , $twitterAction->getTokens()));
        }
    }
