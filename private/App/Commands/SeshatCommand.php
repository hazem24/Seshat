<?php
    namespace App\Commands;
    use App\DomainHelper\Twitter;
    use Framework\Helper\ArrayHelper;
    use App\DomainHelper;
    use App\System\Notification\Notification;


    /**
     * this class responsble for all commands that seshat can do like analytic.
     */

    Class SeshatCommand extends BaseCommand
    {
        /**
         * instance of notification instance.
         */
        private static $notification = null;
        /**
         * @note any method that want to call it from outside the class must be protected and can be called via "execute" method in the base.
         * incase of parameters must send to method send it as an array of $param.
         */

        CONST HASH_REPORTS_FOLDER = REPORTS_HASH_FOLDER; 
        /**
         * @method getAnalytic get analytic of specific tweet.
         */
        protected function getAnalytic(array $params){
                  /**
                   * analtyic consists of :
                        *Statics about user it self (from tweet itself i can do this).
                        *Tweet Itself.
                        *Some user replies.
                        *some statics from user Reaction to this tweet (GeoLocation).
                   * */
                $data = ['screenName'=>$params['screenName'],
                'tweet_id'=>$params['tweet_id'],'oauth_token'=>$params['oauth_token'],'oauth_token_secret'=>$params['oauth_token_secret']];  
                $read = new Twitter\Read;
                $show_tweet   = $read->do('showTweet',$data);

                $retweeters   = $read->do('getRetweets',$data);//Last 100 Person reacted to this tweet.
                $replies      = $read->do("getReplies",$data);   
                return ['tweet'=>$show_tweet,'replies'=>$replies,'reacted_user'=>$retweeters];
        }

        /**
         * get seshat unRead notifications.
         */
        protected function unReadNotifications(array $params){
            $owner = $params['user_id'];
            $notifications = $this->notificationInstance( $owner );
            $notifications = $notifications->unReadNotifications();
            if ($notifications === false){
                $notifications = ['AppError'=>true];
            }
            return $notifications;
        }

        /**
         * get last 100 of user notifications which mean last (activity) in the account .. account timeLine.
         */
        protected function getUserNotifications(array $params){
            $owner = $params['user_id'];
            $notifications = $this->notificationInstance( $owner );
            $notifications = $notifications->getUserNotifications();
            if ($notifications === false){
                $notifications = ['AppError'=>true];
            }
            return $notifications;
        }

        /**
         * get last 50 of tasks.
         */
       protected function tasks(array $params){
            $tasks   = new DomainHelper\Twitter\Task;
            return $tasks->do("showTasks" , $params );
       }



        /**
         * @method createHashTagReport create Report For Specfic hashtag
         * @return array.
         */

         protected function createHashTagReport(array $params){
                $feature = $this->controlLicenses( $params['license_type'] , 4);
                if ( $feature !== false ){
                    $read = new Twitter\Read;
                    $hashtag_tweets_data = $read->do("getHashtagData",$params);
                    if(is_array($hashtag_tweets_data)){
                        if(array_key_exists("error",$hashtag_tweets_data)){
                            $return = $hashtag_tweets_data;
                        }else if (empty($hashtag_tweets_data)) {
                            $return = ['hash_not_active'=>true];
                        }else{
                            $return = $this->hashReport(ArrayHelper::convertMultiArray($hashtag_tweets_data) , $params['hashtag'],$params['screenName']);
                        }
                    }else{
                        $return = ['AppError'=>true];
                    }
                }else{
                    $return = ['error'=>UPGRADE];
                }
                return $return;    
         }
         /**
          * @method hashReport do the following.
                * Extract Overview data of Report as cheat sheet data in {{statistics}} property.
                * Save the Whole response (all tweets) data in  {{reportData}} property.
                * Create unique Name for report Name.
                * Save The Report File In Report Folder Inside Folder HashReport {{FileName}}.json.
          * @return array report_name || array in failure.      
          */
         private function hashReport(array $dataOfReport , string $hashTag,string $screen_name){
                $reportName  = DomainHelper\Helper::createHashReportName();
                $file_path   = self::HASH_REPORTS_FOLDER . $reportName . '.json';
                $report_data = DomainHelper\Helper::orgHashReportData($dataOfReport , str_ireplace("#","",$hashTag) , $screen_name);
                if(file_exists($file_path) === false){
                    file_put_contents($file_path,json_encode($report_data));
                }else{
                    return ['AppError'=>true]; //createHashReport must be changed.
                }
                return ['report_name'=>$reportName];
         }

         private function notificationInstance(int $owner){
            if (is_null(self::$notification) === true){
                self::$notification = new Notification( $owner );
            }
            return self::$notification;
         }
    } 