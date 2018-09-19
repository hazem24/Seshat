<?php
        namespace App\DomainHelper\ControllerHelper;
        use App\DomainHelper\BaseHelper;
        use Framework\Request\RequestHandler;
        use Framework\Lib\Security\Data\FilterDataFactory;
        use Framework\Error\WebViewError;
        use Framework\Lib\Security\Forms\CsrfProtection;
        use App\DomainHelper;
        use App\Controller\Seshat;
        use Framework\Shared;

        /**
         * This Class Provide All Helper Methods That used inside seshat controller.
         */

        Class SeshatHelper extends BaseHelper
        {
            /**
            * have all search type for specific media. 
            * @property searchType. 
            */
            private static $searchType = [
                'media'=>['twitter'=>['tweets','users']]
            ];

            /**
            * have all features in specific media.
            * @property features. 
            * inActiveFollowing => not implemented in version 0.1 && recentUnFollow.
            */
            private static $features = [
                'media'=>['twitter'=>['nonFollowers','recentUnfollow','fans',
                'recentFollowers','checkFriends']]
            ];

            public static function createProfileAction ( Seshat $seshat ) {
                $isWizard = (bool)$seshat->session->getSession('wizard');
                if($isWizard === true){
                             /**
                              *   'firstname' => string 'hazem' (length=5)
                              *   'email' => string 'gotrendtoday@gmail.com' (length=22)
                              *   'account_type' => string '6' (length=1)
                              *   'account_describe' => string 'account descr. !' (length=22)
                              *   'finish' => string 'Finish' (length=6)
                              *   'formToken' => string 'db67c26dcc53014e4dadc7316d731aab' (length=32)
                              */
                             if(RequestHandler::postRequest()){
                                     //Check If It Comes From The Form.
                                     $formToken = (string)RequestHandler::post('formToken');
                                     $user_submit_form = (bool)CsrfProtection::sessionTokenValidation($formToken);
                                     if($user_submit_form === true){
                                             $name = RequestHandler::post('firstname');
                                             $email = RequestHandler::post('email');
                                             $account_type = RequestHandler::post('account_type');
                                             $account_describe = RequestHandler::post('account_describe');
                                             $filter = self::filterWizardForm( $seshat , $name,$email,$account_type,$account_describe);
                                             if($seshat->anyAppError() === true){
                                                        $return = ['error'=>$seshat->getErrors()];
                                             }else{
                                                    self::createProfile($seshat , $filter); 
                                                    if($seshat->anyAppError() === true){
                                                        $return = ['error'=>$seshat->getErrors()];       
                                                    }else{
                                                        $return = ['location'=>BASE_URL.LINK_SIGN.'seshatTimeline'];
                                                    }
                                             }
                                             
                                     }else{
                                                //User Not Submit The Form.
                                                $return = ['error'=>[BOT_ACCESS]];
                                     }

                                    $seshat->encodeResponse($return);
                             }
                             //Email , And Name , And Image Must Be Taken From Twitter And Send To View Here. --Done.
                             $userCredients = $seshat->verfiyCredentials();
                             if($seshat->anyAppError() === true){
                                     $user_need_reauth = $seshat->reauthUser($seshat->getErrors());
                                     $send_to_view = ['error'=>($user_need_reauth === false) ? $seshat->getErrors() : $user_need_reauth];
                             }else{
                                     $send_to_view = (object)['data'=>$userCredients];
                             }
                            $seshat->actionView->setDataInView(["user"=>$send_to_view]);
                            $seshat->render();
                }else{    
                     self::rIn("tw_id","seshatTimeline");                        
                } 
            }

            /**
             * this method has all logic of user seshat account.
             */
            public static function account(Seshat $seshat){
                if (RequestHandler::getRequest()){
                    $cmd = Shared\CommandFactory::getCommand('seshat');
                    $response =  $cmd->execute(['Method'=>['name'=>
                    (string)RequestHandler::get('get'),'parameters'=>['user_id'=>$seshat->session->getSession('id')]]]);
                    $seshat->commonError( $response );
                    $response = $seshat->returnResponseToUser( ( $response ) ?? null );
                    $seshat->encodeResponse( $response );
                }
                $seshat->encodeResponse( ['code'=>404,'msg'=>'Request not found.'] );
            }

            /**
             * this just render the view of tasks action.
             */
            public static function tasks( Seshat $seshat ){
                $seshat->renderLayout("HeaderApp");
                $seshat->render();
                $seshat->renderLayout("FooterApp");
            }

            /**
             * delete specific task.
             */
            public static function deleteTask( Seshat $seshat ){
                if (RequestHandler::postRequest() && RequestHandler::post('task_id')){
                    $task_id = (int) RequestHandler::post("task_id");
                    if ($task_id > 0){
                        $user_id  = $seshat->session->getSession("id");
                        $tasks    = new DomainHelper\Twitter\Task;
                        $response = (bool) $tasks->do("deleteTask" , ['user_id'=>$user_id,'task_id'=>$task_id] );
                        if ($response === true){
                            $response = ['task_deleted'=>TASK_DELETED];
                        }else{
                            $response = ['task_not_deleted'=>TASK_CANNOT_DELETED];
                        } 
                        $seshat->encodeResponse( $response );
                    }
                }
                $seshat->encodeResponse( ['code'=>404,'msg'=>'Request not found.'] );
            }

            public static function createReportHelper ( Seshat $seshatController , array $params = [] ){
                    /**
                      * 1- check params[0] if isset or not && check the type of report if allowable.
                        *if true => start logic proccess for this type of report. 
                        *else not found page.
                      */
                      $report_type = (isset($params[0]) && !empty($params[0]))? self::reportType($params[0]) : false;
                      if($report_type !== false){
                              /**
                               * check if reports value if found at least one params is found $params[1].
                               * start to create a report based on type of report.
                              */
                              if (RequestHandler::postRequest()) {
                                    $hashtag_name = (string) RequestHandler::post('hashtag');
                                    $response = self::createReport( $seshatController , $report_type,['hash_tag'=>$hashtag_name]);
                                    $response = $seshatController->returnResponseToUser($response);
                                    $seshatController->encodeResponse($response);    
                              }       
                      }
                      //Web View.
                      $seshatController->renderLayout("headerApp");
                      if($report_type === false){
                            //render not found layout.   
                            $seshatController->renderLayout("NotFound");  
                      }else {
                            //Hashtag report form.
                            $seshatController->renderLayout("createReport_hashTag");
                      }
                      $seshatController->renderLayout("footerApp");      
            }


            public static function getReportHelper( Seshat $seshatController , array $params = [] ){
                $folder_path = (isset($params[0]) && !empty($params[0])) ? (string) REPORTS_FOLDER.$params[0].DS : '';
                $file_name = (isset($params[1]) && !empty($params[1])) ? (string) $folder_path.$params[1] . '.json' : null;
                if (is_null($file_name) === false && file_exists($file_name) === true) {
                        echo file_get_contents($file_name);
                        $seshatController::stop();
                }
                $seshatController->encodeResponse(['notFound'=>true]);
            }

            public static function controlFollowers ( Seshat $seshat , array $params = [] ) {
                $media   = $params[0] ?? null;
                $feature = $params[1] ?? null;
                $json    = $params[2] ?? null;
                if ( is_null( $media ) === false && DomainHelper\Helper::issetMedia( $media ) === true && (bool)in_array($feature,self::$features['media'][$media]) === true ) {
                    if ( strtolower($json) == 'json' ) {//uses in angularjs request to return the data as json.
                        $response = self::controlFollowersContainer( $seshat , $feature );
                        $seshat->commonError( $response  );
                        $response = $seshat->returnResponseToUser( $response ?? null );
                        $seshat->encodeResponse( $response );
                    }else { //Render Views.
                        $seshat->renderLayout( "HeaderApp" );
                        //renderView of control Followers.                    
                        $seshat->renderLayout("controlFollowers"); 
                        $seshat->renderLayout( "FooterApp" );    
                    }
                }else {
                    $seshat->renderLayout( "HeaderApp" );
                    $seshat->renderLayout('notFound');
                    $seshat->renderLayout( "FooterApp" );
                }
            }

            public static function analyticActionHelper ( Seshat $seshat , array $params = []  ) {
                //Error Handler Api Or App Logic.
                $screenName = (isset($params[0]) && !empty($params[0]))?(string) $params[0] : false;
                $tweet_id   = (isset($params[1]) && !empty($params[1]))? (string) $params[1] : false;                    
                if($screenName !== false && $tweet_id !== false){
                    $getAnalytic = self::seshatAnalyticData( $seshat , $screenName , $tweet_id);
                                                                    
                }else{
                    $seshat->setError(CANNOT_UNDERSTAND);
                }
                                    
                //Check for any error in this request.
                if($seshat->anyAppError() === true){
                        $user_need_reauth = $seshat->reauthUser($seshat->getErrors());
                        $send_to_view = ['error'=>($user_need_reauth === false) ? DomainHelper\FrontEndHelper::notify($seshat->getErrors(),'top','center'): $user_need_reauth];
                }else{
                        $send_to_view = $getAnalytic;
                }
                                                        
                $haveError = (bool)(array_key_exists('error',$send_to_view));
                $seshat->renderLayout("HeaderApp");
                                                        
                if($haveError === true){
                    $seshat->renderLayout('Notfound');
                }else{
                    $seshat->actionView->setDataInView(["analyticData"=>$send_to_view,'FrontHelperClass'=>new DomainHelper\FrontEndHelper]);        
                    $seshat->render();
                }
                                    
                $seshat->renderLayout("FooterApp",($haveError === true)?['error'=>$send_to_view]:[]);
            }

            public static function renderReportView ( Seshat $seshatController , string $report_type ){
                $seshatController->renderLayout("HeaderApp");
                   switch (strtolower($report_type)) {
                       case 'hashtag':
                           $seshatController->renderLayout("get_hashtag_Report");
                           break;
                       default:
                           $seshatController->renderLayout("NotFound");
                           break;
                   }
                   $seshatController->renderLayout("footerApp"); 
            }

            public static function searchHelper( Seshat $seshat , array $params = []){
                if ( !empty($params) ) {
                    //get Search results as json. 
                    $media = (isset($params[0]) && !empty($params[0]) && DomainHelper\Helper::issetMedia($params[0])) ? strtolower($params[0]) : 'notFound' ;
                    $type  = (isset($params[1]) && !empty($params[1])) ? self::searchType( $media , $params[1] ) : null ;
                    $query = (isset($params[2]) && !empty($params[2])) ? $params[2] : null;
                    if (is_null($media) || is_null($type) || is_null($query)) { 
                        $seshat->setError( INVALIAD_REQUEST );
                    }else {
                        //Factory pattern must implement here  and in the whole App if i add more than one social media.
                        //create search.
                        $mediaReader = "App\\DomainHelper\\" . ucfirst($media) . "\\Read";
                        $read = new $mediaReader;
                        $search = $read->do( "search" . ucfirst($type) , array_merge(['search'=>$query,'result_type'=>'recent'] , $seshat->getTokens()));
                        $seshat->commonError( $search );
                       
                        if ( is_object( $search ) && isset( $search->statuses ) ) {
                            //statuses.
                            $response   = ['results' => ['from' => $media , 'type'=>'posts' , 'tweets'=>DomainHelper\FrontEndHelper::tweetsStyle($search->statuses)]];
                        } else if (is_array( $search ) && isset( $search[0] ) && is_object( $search[0] ) && isset( $search[0]->screen_name ) ){
                            //users.
                            $response   = [ 'results' => ['from'=>$media, 'type'=>$type , 'users' => $search]];
                        }else {
                            $seshat->setError( CAN_NOT_RETREIVE_DATA );
                        }
                    }
                    $response = $seshat->returnResponseToUser( ( $response ) ?? null );
                    $seshat->encodeResponse( $response );
                }else{
                    //render views.
                    $seshat->renderLayout("HeaderApp");
                    $seshat->render();
                    $seshat->renderLayout("FooterApp");
                }
            }
            /**
             * @method checkFriends .. check the relation between two accounts.
             * @return json.
             */
            public static function checkFriends ( Seshat $seshat , array $params = [] ) {
                $media          = ( $params[0] ) ?? 'notFound';
                $getJson            = ( $params[1] ) ?? null;
                $source_name      = ( $params[2] ) ?? '';
                $target           = ( $params[3] ) ?? ''; //searchType
                if (is_null( $getJson ) === false && $getJson == 'json' && DomainHelper\Helper::issetMedia($media) === true && !empty( $source_name ) && !empty( $target )){
                    //logic here.
                    $response = self::checkRelationLogic( $source_name , $target , $seshat->getTokens());
                    $seshat->commonError( $response );
                    $response = $seshat->returnResponseToUser( $response ?? null );
                    $seshat->encodeResponse( $response );
                }else {
                    //render view.
                    $seshat->renderLayout("HeaderApp");
                    $seshat->render();
                    $seshat->renderLayout("FooterApp");
                }
            }

            public static function statistics (Seshat $seshat){
                if (RequestHandler::getRequest() && RequestHandler::get('getStatistics')){
                    $user_id  = $seshat->session->getSession('id'); 
                    $response = ['now'=>self::getRealTimeData($seshat , $user_id) , 'past'=>self::getPastStatistics($seshat , $user_id)];
                    $response = $seshat->returnResponseToUser( $response ?? null );
                    $seshat->encodeResponse( $response );                    
                }   
                //render view.
                $seshat->renderLayout("HeaderApp");
                $seshat->render();
                $seshat->renderLayout("FooterApp");
            }
            public static function controlFollowersTask ( Seshat $seshat ){
                if ( RequestHandler::postRequest() ) {
                    $task_type    = ( string ) RequestHandler::post('taskType');
                    $order = ( int )    RequestHandler::post('order');//how many followers/unfollowers seshat will do it automatically.
                    $response  = self::controlFollowersSaveTask( $task_type  , $order ,  $seshat->getTokens() , $seshat->session->getSession('id'));
                    $seshat->commonError( $response );
                    $response = $seshat->returnResponseToUser( $response ?? null );
                    $seshat->encodeResponse( $response );
                }
                $seshat->encodeResponse(['error'=>'Request not Found.']);
            }

            /**
             * this method just render the view of activityAction .. user notifications the same as account activity .. what seshat do in user accounts.
             */

             public static function accountActivity(Seshat $seshat){
                $seshat->renderLayout("HeaderApp");
                $seshat->render();
                $seshat->renderLayout("FooterApp");
             }

            /**
              * @method reportType Responsble for check type of report and if exists or not.
              * @return int of type report || false.
              */

            private static function reportType(string $report_name){
                $report = ['hashtag'=>1];
                if(array_key_exists(strtolower($report_name),$report)){
                    return $report[$report_name];
                }   
                    return false;
            }

            /**
            * responsable for create specific report for specific type.
            * @method createReport.
            */
            private static function createReport (Seshat $seshatController , int $report_type , array $data_to_create_report = []){
                switch ($report_type) {
                    case 1://Hashtag report data type.
                        //Hashtag report $data_to_create_report must have one and only one element which is hashtag name to track.
                        if(isset($data_to_create_report['hash_tag']) && !empty($data_to_create_report['hash_tag'])){
                                 return self::createHashTagReport( $seshatController , $data_to_create_report['hash_tag']);
                        } 
                    default:
                         return ['code'=>404,'error'=>["Request Not found."]];
                         break;
                } 
            }

           /**
            * @method createHashTagReport Responasble for create hashtag report.
            * @return string => indicate uniq_id_of_report || void otherwise.
            */
            private static function createHashTagReport( Seshat $seshatController , string $hashtag_name){
                $tokens = $seshatController->getTokens();  
                $screenName = $seshatController->session->getSession("username");
                $cmd = Shared\CommandFactory::getCommand('seshat');
                $getHashTagReport =  $cmd->execute(['Method'=>['name'=>"createHashTagReport",'parameters'=>['screenName'=>$screenName,
                'hashtag'=> urlencode((stripos($hashtag_name,'#') !== false) ? '' : '#').$hashtag_name,
                'oauth_token'=>$tokens['oauth_token'],'oauth_token_secret'=>$tokens['oauth_token_secret']]]]);
                $seshatController->commonError($getHashTagReport); 
                return $getHashTagReport;
            }

            private static function filterWizardForm( Seshat $seshat ,  string $name,string $email,int $account_type,string $account_describe){
                $data = [YOUR_NAME=>['value'=>$name , 'type'=>'string','min'=>3 , 'max'=>'100'],
                         EMAIL=>['value'=>$email , 'type'=>'email','min'=>3 , 'max'=>400],
                         DESCRIBE_YOUR_ACCOUNT=>['value'=>$account_describe , 'type'=>'string','min'=>20 , 'max'=>400]
                        ];
                $filter = new FilterDataFactory($data);
                $filter = $filter->getSecurityData();
                $account_type = DomainHelper\Helper::accountType($account_type);
                $anyError = WebViewError::anyError('form' , $data , $filter);
                if(is_array($anyError)){
                    //There Is And Error.
                    $seshat->setError(WebViewError::userErrorMsg($anyError));
                }else{
                    $filter['account_type'] = array_keys($account_type)[0];
                    return $filter;//Return Back Filter Data.
                }
            }

            private static function createProfile(Seshat $seshat , array $data){
                $name = $data[YOUR_NAME];  $email = $data[EMAIL]; $account_describe = $data[DESCRIBE_YOUR_ACCOUNT]; $account_type = $data['account_type'];
                $cmd = Shared\CommandFactory::getCommand('user');
                $createProfile = $cmd->execute(['Method'=>['name'=>'createProfile','parameters'=>['id'=>(int)$seshat->session->getSession('id'),'name'=>$name
                ,'email'=>$email,'user_describe'=>$account_describe,'account_type'=>$account_type]]]);
                if($createProfile === true){
                             /**
                              * 1-Wizard Session To False. --Done.
                              * 2-Redirect User To SeshatTimeLine. --Done.
                              * 3- Welcome Msg To User For First Time Only.
                              */
                    $seshat->session->setSession('wizard',false);
                }elseif($createProfile === false){
                    //Error Happen At Inserting Data To DataBase Try Again Later.
                    $seshat->setError(GLOBAL_ERROR);
                }else if(array_key_exists('emailExists',$createProfile)){
                    //Email Exists.
                    $seshat->setError(EMAIL_EXISTS);
                } 
            }

             /**
            * @method seshatAnalyticData.
            */
           private static function seshatAnalyticData( Seshat $seshat  , string $screenName,string $tweet_id){
                $tokens = $seshat->getTokens();   
                $cmd = Shared\CommandFactory::getCommand('seshat');
                $analyticData =  $cmd->execute(['Method'=>['name'=>"getAnalytic",'parameters'=>['screenName'=>$screenName,
                  'tweet_id'=>$tweet_id,
                  'oauth_token'=>$tokens['oauth_token'],'oauth_token_secret'=>$tokens['oauth_token_secret']]]]);
                if(is_array($analyticData) && is_object($analyticData['tweet']) === false && array_key_exists('error',$analyticData['tweet'])){
                    $seshat->setError($analyticData['tweet']['error']);
                } 
                return $analyticData;
            }

            /**
            * indicate that the search type is provided for specific media. 
            * @method searchType.  
            * @return string of type || null in case no type founded.
            */
            private static function searchType ( string $media , string $searchType ) {
            
                if ( array_key_exists($media , self::$searchType['media']) ) {
                    $typeKey = (array_search( strtolower($searchType) , self::$searchType['media'][$media] ));
                    $return = ($typeKey !== false) ?  self::$searchType['media'][$media][$typeKey] : null;
                }else {
                    $return = null;
                }
                
                return $return;
            }


            /**
             * @method controlFollowersContainer.
             * @return array.
             */
            private static function controlFollowersContainer ( Seshat $seshat ,  string $feature) {
                $data = array_merge(['user_id'=>$seshat->session->getSession('tw_id')] , $seshat->getTokens());
                $read     = new DomainHelper\Twitter\Read;
                if ( $feature == 'recentUnfollow' ){
                    return self::unrecentFollowersLogic( $seshat , $read , $data );
                }
                $response = $read->do($feature,$data);
                return $response;
            }

            private static function unrecentFollowersLogic ( Seshat $seshat , DomainHelper\Twitter\Read $read  , array $data = []){
                /**
                 * 1 - get old list from cache.
                    * if found return the list if not found create one and save it in cache.
                 */
                $itemName = 'followersList' . $seshat->session->getSession('id');
                $cache = $seshat->fastCache();
                $followersList = $cache->getItem( $itemName );
                if ( is_null($cache->get( $followersList )) === true){
                    //get list and save it in cache.
                    $createFollowersList = $read->do('getFollowersIds',$data);
                    if ( is_object( $createFollowersList ) && isset( $createFollowersList->ids ) ){
                        $cache->set( $followersList , $createFollowersList->ids , 10800);//3 hr.
                    }
                    $response = ['error'=>NO_NEW_DATA];
                }else {
                    $data['lastList'] = $cache->get( $followersList );
                    $response = $read->do('recentUnfollow',$data);
                }
                return $response;
            }


            private static function checkRelationLogic ( string $source , string $target , array $tokens = [] ){
                $data = array_merge( ['source'=>$source,'target'=>$target] , $tokens );
                $read = new DomainHelper\Twitter\Read;
                $response =  $read->do('checkFriends',$data);
                if ( is_array( $response )  && array_key_exists( 'error' , $response )){
                    $response = ['error'=>$response['error']];
                } else{
                    $source_profile_data  = $read->do('getUser',array_merge( ['screen_name'=>$source] , $tokens));
                    $target_profile_data  = $read->do('getUser',array_merge( ['screen_name'=>$target] , $tokens));
                    $response             = ['source_data'=>['profile'=>$source_profile_data] , 'target'=>['profile'=>$target_profile_data],'relation'=>$response];
                }
                return $response;
            }

            private static function controlFollowersSaveTask ( string $task_type , int $order ,  array $tokens , int $user_id){
                $whiteListOfTasks = ['fans'=>32,'nonfollowers'=>31,'recentfollowers'=>33];
                $task_id          = ($whiteListOfTasks[strtolower($task_type)]) ?? null;
                if ( is_null( $task_id )  === false){
                    if ($order > 0 && $order <= 2000){
                        $task = new DomainHelper\Twitter\Task;
                        $task = $task->do("addNewTask",array_merge(['task_id'=>$task_id,'user_id'=>$user_id , 'order'=>$order],$tokens));
                        //response from here.
                        if ( is_array( $task ) && array_key_exists('task_save',$task) ){
                            $task = ['success'=>CNTROL_FLLOWR_SAVED];
                        }else if ( is_array( $task ) &&  array_key_exists('task_not_save',$task)){
                            $task = ['error'=>TASK_NOT_SAVE];
                        }
                        return $task;    
                    }else{
                        return ['error'=>MAX_2000];
                    }
                }
                return ['error'=>CANNOT_DO_TASK];
           }

           private static function getPastStatistics( Seshat $seshat , int $user_id ){
                $cache      = $seshat->fastCache(); 
                $statistics = $cache->getItem("mediaStatistics".$user_id);
                $data       = $cache->get($statistics);
                if (is_null($data) === true){
                    //get statistics of social media accounts and save it in cache.
                        //save the data into cache for 48 hr.
                        $data = self::getRealTimeData($seshat,$user_id);
                        $cache->set($statistics,$data,172800);//48 hr.              
                    }
                return $data;
           }

           /**
            * this method get real time data for statistics report .. delayed 3 hr for cache.
            */
           private static function getRealTimeData( Seshat $seshat , int $user_id){
                $cache      = $seshat->fastCache();
                $statistics = $cache->getItem("realTimeStatistic".$user_id);
                $data       = $cache->get($statistics);
                if (is_null($data) === true){
                    //get data and save it in the cache.
                    $twitter_id   = $seshat->session->getSession('tw_id'); $tokens = $seshat->getTokens();
                    $params       = array_merge(['user_id'=>$twitter_id] , $tokens);
                    $read         = new DomainHelper\Twitter\Read;
                    $user         = $read->do("getUser",$params);
                    $nonFollowers = $read->do("nonFollowers",$params);
                    $fans         = $read->do("fans",$params);
                    $data         = (['statistics'=>['twitter'=>['followers'=>$user->followers_count,
                        'following'=>$user->friends_count , 'tweet_count'=>$user->statuses_count,
                        'nonFollower'=>count( $nonFollowers['results']['users']) , 'fans'=>count( $nonFollowers['results']['users'])]]]);
                    $cache->set($statistics,$data,10800);// 3 hr.
                }
                return $data;
           }
        }