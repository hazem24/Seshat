<?php
        namespace App\Commands;
        use Framework\Shared;
        use App\Model\App\Seshat\TaskModel;




        /**
        *This Class Have All Commands That Do Action In Twitter.
        */

        Class TaskCommand extends TwitterCommand
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
                     $task_data['media'] = ($task_data['media'] === true)?($this->handleMedia($task_data['user_id'])):false;
                     if(is_array($task_data['media'])){
                                return $task_data['media'];//Error At Upload Media.
                     }
                     if($this->scheduleExists($task_data['user_id'],$task_data['expected_finish']) === true){
                                return ['scheduleExist'=>true];
                     }
                     $task_data['details'] = $this->taskDetails($task_data['task_id'],$task_data);
                     $this->model->setProperty("task_id",$task_data['task_id']);
                     $this->model->setProperty("details",$task_data['details']);
                     $this->model->setProperty("user_id",$task_data['user_id']);
                     $this->model->setProperty("expected_finish",$task_data['expected_finish']);
                     $this->model->setProperty("task_name",$task_data['task_name']);
                     return ($this->model->save()===true)?['task_save'=>true]:['task_not_save'=>true]; 
             }
             /**
              * @method scheduleExists Check If User Has  An Exists schedule At Specific Time.
              * @return bool.
              */
             private function scheduleExists(int $user_id,string $date){
                     return $this->model::scheduleExists($user_id,$date);
             }

            /**
             * @method taskDetails Responsable For Organize taskDetails.
             * @return json.
             */

            private function taskDetails(int $task_id,array $data){
                switch ($task_id) {
                    case 1:
                        $details = json_encode(['access_token'=>$data['oauth_token'],
                        'access_token_secret'=>$data['oauth_token_secret'],
                        'media'=>$data['media'],'tweetContent'=>$data['tweetContent']
                        ,'publicAccess'=>$data['seshatPublicAccess'],
                        'catgory'=>$data['category']]);
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
