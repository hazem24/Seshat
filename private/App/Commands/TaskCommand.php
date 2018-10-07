<?php
        namespace App\Commands;
        use Framework\Shared;
        use App\Model\App\Seshat\TaskModel;

        /**
        *This Class Have All Commands That Do Action In Twitter.
        */

        Class TaskCommand extends BaseCommand
        {
            protected $model;

            public function __construct(){
                parent::__construct();
                $this->model = new TaskModel;
            }
            /**
             *@method  saveTask. 
             *@return true in success , false in failed to save task , array failed to upload image.
             */

             protected function saveTask(array $task_data){ 
                
                $checkTask = $this->checkTask(  $task_data['task_id'] , $task_data ); //Check Task For Any Error. 
                if(!is_null( $checkTask )) {
                    return $checkTask;
                }  
                $task_data['details'] = $this->taskDetails($task_data['task_id'],$task_data);
                $this->model->setProperty("task_id",$task_data['task_id']);
                $this->model->setProperty("details",$task_data['details']);
                $this->model->setProperty("user_id",$task_data['user_id']);
                $this->model->setProperty("expected_finish",$task_data['expected_finish']);
                $this->model->setProperty("task_name",$task_data['task_name']);
                return ($this->model->save()===true)?['task_save'=>true]:['task_not_save'=>true]; 
             }

             protected function showTasks( array $params ){
                $user_id = $params['user_id'];
                return $this->model::showTasks( $user_id );
             }

             protected function deleteTask( array $params ){
                $user_id = $params['user_id']; $task_id = $params['task_id'];
                return $this->model::deleteTask( $user_id , $task_id );
             }


            private function checkTask ( int $task_id , array &$task_data ) {
                switch ($task_id) {
                    case 1:
                        $logic = $this->scheduleTaskLogic( $task_data , $task_data['license_type']);
                        break;
                    case 2:
                        $logic = $this->tweetAsLogic( $task_data , $task_data['license_type']);
                        break;
                    case 31:
                        $logic = $this->controlFollowersLogic( $task_data['user_id'] , $task_id , $task_data['license_type']);    
                        break;
                    case 32:
                        $logic = $this->controlFollowersLogic( $task_data['user_id'] , $task_id , $task_data['license_type']);    
                        break;
                    case 33:
                        $logic = $this->controlFollowersLogic( $task_data['user_id'] , $task_id , $task_data['license_type']);    
                        break;
                    default:
                        //Nothing Here!.
                        break;
                }
                return   $logic;              
            } 
            /**
            * @method scheduleTaskLogic
            */
            private function scheduleTaskLogic ( array &$task_data , int $license_type ) {
                /**
                 * i must count all schedule users have and compare it with license max limit if less than user can put schedule
                 * else Upgrade message must appear.
                 * else if Max reached reach limit must appear.
                 */
                $feature = $this->controlLicenses( $license_type , 1);
                if ( $feature !== false ){
                    $task_data['mediaPath']  = ($task_data['media'] === true)?($this->handleMedia($task_data['user_id'])):'';//for schedule part.
                    if(is_array($task_data['mediaPath'])){
                        $return =  $task_data['mediaPath'];//Error At Upload Media.
                    }
                    if($this->scheduleExists($task_data['user_id'],$task_data['expected_finish']) === true){
                        $return = ['scheduleExist'=>true];
                    }
                    if ($this->model->countScheduled( $task_data['user_id'] ) >= $feature['max']){
                        $return = ['error'=>UPGRADE_TO_INCREASE_SCHEDULED_LIMIT]; 
                    }    
                }else{
                    $return     = ['error'=>UPGRADE]; 
                }
                return $return ?? null;
            }
            /**
            * tweetAs logic. 
            * @method tweetAsLogic. 
            * @return array || null.
            */
            private function tweetAsLogic ( array $task_data , int $license_type ) {
                /**
                * 1- check if user has 3 tweet as feature.--Done.
                    * if true return to user error msg => MAX_TWEET_AS_THREE.--Done.
                * 2- check if user choose this {{ screen_name }} before.--Done.
                    * if true return error msg => YOU_TWEET_AS_THIS_USER_ALREADY.--Done.
                */
                $feature = $this->controlLicenses( $license_type , 2 );
                if ($feature !== false){
                    $tweetAsInfo = $this->tweetAsInfo( $task_data['user_id'] );
                    if ( is_array($tweetAsInfo)  && !empty( $tweetAsInfo ) ){
                        $counter = count( $tweetAsInfo );
                        if ( $counter >= 3 ) {
                            $return = ['error'=>[MAX_TWEET_AS_THREE]]; 
                        }else if ( $counter >= $feature['max'] ){
                            $return = ['error'=>POST_AS_MAX_UPGRADE . $feature['max'] . UPGRADE_TO_INCREASE_LIMIT];
                        }else {//less than three uses.
                            //Check if user tweet as this user ?.
                            foreach ($tweetAsInfo as $key => $task) {
                                $details = json_decode( $task->getProperty('details') , true);
                                if ( isset( $details['screen_name'] ) && strtolower($details['screen_name']) == strtolower($task_data['screen_name']) ){
                                    $return = ['error'=>[YOU_TWEET_AS_THIS_USER_ALREADY . ' ' . $details['screen_name'] .'.']];
                                }
                            }
                        }
                    }else if ( $tweetAsInfo === false ) {
                        $return = ['AppError'=>true];
                    }    
                }else{
                    $return = ['error'=>UPGRADE];
                }
                return $return ?? null;
            }

            /**
             * @method controlFollowersLogic.
             * @return array || null.
             */
            private function controlFollowersLogic ( int $user_id , int $task_id , int $license_type ) {
                $feature = $this->controlLicenses( $license_type , $task_id );
                if ( $feature !== false ){
                    $taskExists = $this->controlFollowersTaskExists( $user_id , $task_id );
                    if ( $taskExists === true ){
                        $return = ['error'=>CNTROL_FLLOWR_TASK_EXISTS];
                    }else{
                        $return = null;
                    }    
                }else{
                    $return = ['error'=>UPGRADE];
                }
                return $return;
            }
            /**
             * @method controlFollowersChecker Check if user created this task before.
             * @return bool.
             */
            private function controlFollowersTaskExists( int $user_id , int $task_id ){
                return $this->model::controlFollowersTaskExists( $user_id , $task_id );
            }
            /**
            * @method scheduleExists Check If User Has  An Exists schedule At Specific Time.
            * @return bool.
            */
            private function scheduleExists(int $user_id,string $date){
                return $this->model::scheduleExists($user_id,$date);
            }

            /**
            * Get important data for tweetAsInfo for specific {{ user_id }}.
            * @method tweetAsInfo.
            * @return array of object {{ TaskModel }} || empty array || false in failure. 
            */
            private function tweetAsInfo ( int $user_id ){
                return $this->model->getTweetAsInfo( $user_id );
            }


            /**
             * @method taskDetails Responsable For Organize taskDetails.
             * @return json.
             */

            private function taskDetails(int $task_id,array $data){
                switch ($task_id) {
                    case 1://progress has two values 0 -> 100 only.
                        $details = json_encode(['access_token'=>$data['access_token'],
                        'access_token_secret'=>$data['access_token_secret'],
                        'media'=>$data['media'],'mediaPath'=>$data['mediaPath'],'tweetContent'=>$data['tweetContent']
                        ,'publicAccess'=>$data['publicAccess'],
                        'category'=>$data['category']]);
                        break;
                    case 2:// no progress here unlimited until cancel by user or error happen or not re-pay.
                        $details = json_encode(['access_token'=>$data['access_token'],
                        'access_token_secret'=>$data['access_token_secret'],'lang'=>$data['lang'],
                        'screen_name'=>$data['screen_name'],'media'=>$data['media']]);
                        break; 
                    case 31://progress = (done/order) * 100.
                        $details = json_encode(['access_token'=>$data['access_token'],
                        'access_token_secret'=>$data['access_token_secret'],'socialMedia'=>'twitter',
                        'order'=>$data['order'],'done'=> 0]);
                        break;
                    case 32://progress = (done/order) * 100.
                        $details = json_encode(['access_token'=>$data['access_token'],
                        'access_token_secret'=>$data['access_token_secret'],'socialMedia'=>'twitter',
                        'order'=>$data['order'],'done'=> 0]);
                        break;       
                    case 33://progress = (done/order) * 100.
                        $details = json_encode(['access_token'=>$data['access_token'],
                        'access_token_secret'=>$data['access_token_secret'],'socialMedia'=>'twitter',
                        'order'=>$data['order'],'done'=> 0]);
                        break;       
                    default:
                        # code...
                        break;
                }
            return $details;    
        }

        private function handleMedia(int $user_id){
            $uploadMedia = $this->uploadMedia($user_id);
            if(is_array($uploadMedia)){
                $return = ['uploadError'=>$uploadMedia];//Error At Upload Image To Seshat System.
            }else if(is_string($uploadMedia)){
                $return = $uploadMedia;
            }
            return $return;
        }
    }
