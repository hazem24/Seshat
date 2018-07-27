<?php
    namespace App\DomainHelper\ControllerHelper;
    use App\DomainHelper\BaseHelper;
    use App\Controller\Profile;
    use App\DomainHelper\Twitter;
    use App\DomainHelper\FrontEndHelper;
    use App\DomainHelper\Helper;
    use Framework\Request\RequestHandler;

    /**
     * This Class provide helper methods for profile Controller.
     */

     Class ProfileHelper extends BaseHelper 
     {
        /**
         * max sample can be taken for fakeAccounts Calc.
         * @property maxSample. 
         */ 
        private static $maxSample = 1000;

        /**
         * max sample to be taken per request.
         * @property samplePerRequest.
         */
        private static $samplePerRequest = 200;

        public static function renderProfileView ( Profile $profile , array $params = [] ){
            $user_name = $params[0] ?? null;
            $profile->renderLayout("HeaderApp");
            if(is_null($user_name) === false){
                //Render View of profile.
                $profile->render();
            }else {
                $profile->renderLayout("NotFound");
            }  
                $profile->renderLayout("FooterApp");
        }
        
        public static function getProfileDataForTwitter ( Profile $profile , array $params = [] ){
            $user_name = $params[0] ?? null;
            if(is_null($user_name) === false) {
                $tokens = $profile->getTokens();
                $profileReader = self::checkUser(  $profile , new Twitter\Read , $tokens , str_ireplace("@",'',$user_name), true);
                $profile_data  = $profileReader['profileReader'];
                $tweetsReader  = $profileReader['timeLine'];
                //search For error.
                $profile->commonError( (is_array($profile_data) ? $profile_data : $tweetsReader ) );
                $response = $profile->returnResponseToUser(['profile'=> $profile_data,'tweets'=> (is_array($tweetsReader)) ? FrontEndHelper::tweetsStyle($tweetsReader) : $tweetsReader ,'auth_id'=>$profile->session->getSession('tw_id')]);
                $profile->encodeResponse($response);
            }
            //Nothing Here For Now V 1.0. 
        }

        /**
         * cal. fake accounts for specific user.
         */
        public static function fakeAccountsCalcualtion ( Profile $profile , array $params = [] ) {
            $media = $params[0] ?? null;
            $screen_name = $params[1] ?? null;
            if ( is_null($media) === false && Helper::issetMedia($media) && is_null($screen_name) === false ) {
                //Calc.
                self::doFakeCalculation($profile , $media , $screen_name );
            }else {
                $profile->setError(INVALIAD_REQUEST);
            }
            //return response.
            $response = $profile->returnResponseToUser($response ?? null);
            $profile->encodeResponse($response);
        }

        /**
         * @method tweetAsTask.
         * @return json.
         */
        public static function tweetAsTask ( Profile $profile ) {
            if (RequestHandler::postRequest()) {
                /**
                * 1 - check if user exists in twitter or no error in twitter api.
                    * if true start the logic of tweetAs task.
                    * else error return.
                */
                $tweet_as = ( string ) RequestHandler::post('tweetAs'); //Username to tweet as him/her.
                $options  = [
                    'translate_tweets_to'=> (( string ) RequestHandler::post('translate_tweets_to'))
                ];
                $checkLang = Helper::checkLang( $options['translate_tweets_to'] );
                $checkLang = ($checkLang === true) ? null : $profile->setError( $checkLang );
                
                $tokens = $profile->getTokens();
                $profile_reader = self::checkUser( $profile ,  new Twitter\Read  , $tokens , $tweet_as );
                $profile->commonError( $profile_reader );
                
                if( is_object( $profile_reader ) && isset( $profile_reader->screen_name ) && is_null( $checkLang ) === true){
                    //Exists.
                    if( isset( $profile_reader->status ) ) {
                        //Save Task.
                        $task     = new Twitter\Task;
                        $response = $task->do('addNewTask' , array_merge($tokens,['task_id'=>2,'screen_name'=>$tweet_as,'lang'=>$options['translate_tweets_to'],'user_id'=>$profile->session->getSession('id')]) );
                        $profile->commonError( $response );
                        if( is_array( $response )  && array_key_exists('task_not_save',$response)){
                            $profile->setError(TASK_NOT_SAVE);
                        }else if ( is_array( $response ) && array_key_exists('task_save',$response)){
                            $response = ['tweet_as_success'=>TWEET_AS_SUCCESS .  "$tweet_as " . WITH_LANG . ' ' . $options['translate_tweets_to']];
                        }
                    }else{
                        $profile->setError( PRIVATE_ACCOUNT );
                    }
                }
                //InCase of there's no response.
                if( !isset( $response ) ) {
                    $profile->setError( INVALIAD_REQUEST ); 
                }
                $response = $profile->returnResponseToUser(  $response ?? null );
                $profile->encodeResponse( $response );
            }
            $profile->encodeResponse(['error'=>['Request Not Found.']]);
        }

        private static function doFakeCalculation (  Profile $profile , string $media ,  string $screen_name ){
            $method = "$media"."FakeAccounts";
            if(method_exists(get_called_class(),$method)){
                self::$method( $profile , $screen_name );
            }else{
                $profile->setError( INVALIAD_REQUEST );
            }
            
        }
        /**
         * this method uses for get % and numbers of fake accounts followers for specific users && in future i think it will return list of unactive followers || may be self::classifiyFollowers.
         * @method twitterFakeAccounts.
         */
        public static function twitterFakeAccounts ( Profile $profile , string $screen_name ) {
            $tokens = $profile->getTokens();
            $reader = new Twitter\Read;
            $profileReader = self::checkUser( $profile , $reader  , $tokens , $screen_name );
            $profile->commonError($profileReader);
            if (is_object($profileReader) && isset($profileReader->screen_name)) {
                if(isset($profileReader->status)){
                    //do calc. here
                    $loops    = self::followersLoopCalculation($profileReader->followers_count);
                    $response = self::classifiyFollowers($reader,$loops,$profileReader->followers_count,$tokens,$screen_name);
                    if ( is_array($response) && array_key_exists('fakePercentage' , $response)) {
                        $response['profile_img'] = $profileReader->profile_image_url_https;
                        $response['screen_name'] = $profileReader->screen_name;
                        $response                = ['fakeFollowersReport'=>FrontEndHelper::fakeFollowersReport($response)];
                    }
                    $profile->commonError($response);
                }else { 
                    $profile->setError(PRIVATE_ACCOUNT);
                }
            }else {
                $profile->commonError(['AppError'=>true]);
            }
            //Incase of request 
            if( !isset( $response ) ){
                $profile->setError( INVALIAD_REQUEST );
            }
            //response Here.
            $response = $profile->returnResponseToUser( $response ?? null);
            $profile->encodeResponse( $response );
        }
        /**
         * this method calc. how many times will loop through api to get the sample users {{ Followers }}.
         * @method followersLoopCalculation.
         */
        private static function followersLoopCalculation ( int $followers ){
            $followers_loops = ( $followers > self::$maxSample ) ? ['loopTimes'=>5,'sample'=>self::$maxSample] : ['loopTimes'=>(int)ceil($followers/self::$samplePerRequest),'sample'=>$followers];
            return $followers_loops;
        }

        /**
        * this method classified {{ fakeAccount || normalAccount }} the followers of specific users {{ screen_name }} || in future this method will return the actual fakeaccounts as unactive users future (i think that).
        * @method classifiyFollowers.
        * @return  
        */
        private static function classifiyFollowers ( Twitter\Read $twitterRead ,  array $loops_sample , int $totalFollowers , array $tokens , string $screen_name) {
            /**
            * get sample of followers list.
            * classifiy followers.
            * return ['fake','fake%','screen_sample']
            */
            $sample = $loops_sample['sample'];
            $loops  = $loops_sample['loopTimes'];
            $fakeAccounts = 0;
            $cursor = -1;
            for ($i=1; $i <= $loops ; $i++) { 
                $followersList = $twitterRead->do('getFollowersList',['screen_name'=>$screen_name,'cursor'=>$cursor,'oauth_token'=>$tokens['oauth_token'] , 
                'oauth_token_secret'=>$tokens['oauth_token_secret']]);
                if(isset( $followersList->users ) && !empty( $followersList->users )){
                    //Sample loops.
                    foreach ($followersList->users as $key => $user) {
                        if ( $user->friends_count > $user->followers_count || is_null($user->description) ) {
                            $fakeAccounts++;
                        }else if ( isset( $user->status ) ) {
                            $last_tweet_date  = date_create($user->status->created_at);
                            $now              = date_create(date("Y-m-d"));
                            $diff             = date_diff($now,$last_tweet_date);
                            if( $diff->d >= 20){//Last tweet by this user from 20 days so consider it as
                                $fakeAccounts++;
                            }
                        }
                    }
                    
                }else if ( is_array($followersList) && array_key_exists('error',$followersList) ) {
                    $response = $followersList;
                    break;
                }else {
                    $response = ['error'=>[FOLLOWERS_LIST_ERROR . " $screen_name"]];
                    break;  
                }
                //Change crusor value to next value.
                if (isset( $followersList->next_cursor ) && $followersList->next_cursor > 0) {
                    $cursor = $followersList->next_cursor;
                }
                if($i == $loops){//Loops Ends create the calculation.
                    $fakePercentage = Helper::percentage($fakeAccounts , 2 * $sample , false);
                    $fakeNumbers    = (int)floor(($fakePercentage/100) * $totalFollowers);
                    $response = ['fakePercentage'=>$fakePercentage,'fakeNumbers'=>$fakeNumbers,'totalFollowers'=>$totalFollowers,'sample'=>(int)((25/100) * $totalFollowers)];
                    break;
                }

            }
            return $response ?? ['error'=>[GLOBAL_ERROR]];
        }
        /**
         * Check if user exists.
         */
        private static function checkUser (Profile $profile , Twitter\Read $reader , array $tokens  , string $screen_name , bool $return_time_line = false) {
            $profileReader = $reader->do("getUser",['screen_name'=>$screen_name,'oauth_token'=>$tokens['oauth_token'] , 
            'oauth_token_secret'=>$tokens['oauth_token_secret']]);
            if($return_time_line === true) {
                $tweetsReader = $reader->do("userTimeLine",['screen_name'=>$screen_name,'oauth_token'=>$tokens['oauth_token'] , 
                'oauth_token_secret'=>$tokens['oauth_token_secret']]);
                return ['profileReader'=>$profileReader,'timeLine'=>$tweetsReader];
            }
            return $profileReader;
        }
     }