<?php

        namespace App\Controller;
        use Framework\Error\WebViewError;
        use App\DomainHelper\FrontEndHelper;
        use App\DomainHelper\Helper;
        use App\DomainHelper\DateTrait;
        use Framework\Request\RequestHandler;
        use Framework\Lib\Security\Data\FilterDataFactory;
        use App\DomainHelper\Twitter;


        /**
        *This Class Just Provide The Logic Of All Action User Send It To Do Some Action In Twitter Like Tweet,Follow,...
        */

        Class TwitterAction extends AppShared
        {
           use DateTrait;
           
        
            /**
             * @method composeTweetAction This Method Resonsable For All Logic Of Compose Tweet In Twitter.
             * @return json. 
             */
            final public function composeTweetAction(){
                   $this->detectLang();
                   $this->rOut('tw_id','index/signin');
                   $this->redirectToWizard();
                   /**
                    * If Comming Request Is PublishNow  =>User Want To Send Tweet To Twitter.
                    * If Comming Request Is Sechdule => User Want To Scedule.
                    */
                    if(RequestHandler::postRequest()){
                        $publishNow = (bool)RequestHandler::post('publish');
                        $schedule   = (bool)RequestHandler::post('schedule');  
                        $category = (int)RequestHandler::post("category");
                        $tweetContent = (string)RequestHandler::post("tweetContent");
                        $seshatPublicAccess = (bool)RequestHandler::post("seshatPublicAccess");

                        if($publishNow === true){
                                //Publish Now Logic=>Table seshat_publish.
                                $publishNow = $this->newTweet($category,$tweetContent,$seshatPublicAccess);
                        }elseif($schedule === true){
                                //Schedule Logic=>Table seshat_schedule.
                                $scehduleTime = (string)RequestHandler::post("scehduleTime");
                               if($this->validDate($scehduleTime) === false){
                                        $this->error[] = INVAILD_DATE;
                               }
                               $schedule = $this->newTweet($category,$tweetContent,$seshatPublicAccess,true);
                        }
                        //Return Reponse To User.
                        if($this->anyAppError() === false){
                                $response = (isset($publishNow) && is_array($publishNow))?$publishNow:$schedule;           
                        }else{
                                $user_need_reauth = $this->reauthUser($this->error);
                                $response = ['error'=>($user_need_reauth === false) ? $this->error : ['reauth'=>$user_need_reauth[0]]];//reauth Index To Can Be Supplied By Javascript Can Know the type of error notify.
                        }
                        echo json_encode($response);
                        exit;           
                    }
                    echo json_encode(["code"=>403,'error'=>"Protected Area You Cannot Access."]);
                    exit;
            }



            private function newTweet($category,$tweetContent,$seshatPublicAccess,bool $schedule = false){
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
                        $this->error[] = WebViewError::userErrorMsg($anyError);
                    }else{
                        if(isset($_FILES['tweetMedia']) && !empty($_FILES['tweetMedia']['name']) && !empty($_FILES['tweetMedia']['tmp_name'])){
                                //Image + Text.
                                if($schedule === false){
                                        $tweet = $this->newTweetLogic($filter[TWEET_CONTENT],$categoryType,$seshatPublicAccess,false,true);         
                                }else{
                                        $tweet = $this->newTweetLogic($filter[TWEET_CONTENT],$categoryType,$seshatPublicAccess,true,true);
                                }
                                
                        }else{
                                //Text Only.
                                if($schedule === false){
                                        $tweet = $this->newTweetLogic($filter[TWEET_CONTENT],$categoryType,$seshatPublicAccess,false);
                                }else{
                                        $tweet = $this->newTweetLogic($filter[TWEET_CONTENT],$categoryType,$seshatPublicAccess,true);
                                }
                        }
                        
                        //Response Logic Same For Two Types.
                        if(array_key_exists('error',$tweet)){
                                if($tweet['error'] === true){
                                        $this->error[] = TWEET_NOT_SAVED;
                                }else{
                                        $this->error[] = $tweet['error'];
                                }
                        }else if (array_key_exists('uploadError',$tweet)){
                                $uploadError   =  WebViewError::anyError('upload',$tweet);
                                $this->error[] =  WebViewError::userErrorMsg($uploadError);
                        }else if(array_key_exists('success',$tweet)){
                                        return ['success'=>TWEET_UPLOAD_SUCCESS];
                        }else if(array_key_exists('task_save',$tweet)){
                                        return ['success'=>TASK_SCHEDULE_SAVED];//scheduled.
                        }else if (array_key_exists('task_not_save',$tweet)){
                                $this->error[] = TASK_NOT_SCHEDULE_SAVED;
                        }else if (array_key_exists("scheduleExist",$tweet)){
                                $this->error[] = HAVE_SCHEDULE_AT_SAME_TIME;
                        }else{
                                $this->error[] = GLOBAL_ERROR;
                        }      

                    }
                    

            }

            /**
             * @method newTweetLogic Handle Logic Of Send Direct Tweet To Twitter.
             * @return array.
             */
            private function newTweetLogic(string $tweetContent,int $categoryType,bool $seshatPublicAccess,bool $schedule=false,bool $media = false){
                $data = ['oauth_token'=>$this->session->getSession('oauth_token'),
                'oauth_token_secret'=>$this->session->getSession('oauth_token_secret'),'tweetContent'=>$tweetContent,'user_id'=>(int)$this->session->getSession('id'),
                'category'=>$categoryType,'media'=>$media,'seshatPublicAccess'=>$seshatPublicAccess];
                if($schedule === false){
                        $class = new Twitter\Send;
                        $method = "publishNewTweet";
                }else{
                        $class = new Twitter\Task;
                        $method = "newTask";
                        $data['task_id'] = 1;
                        $data['expected_finish'] = $this->date;//Coming From Date Trait.
                }
                return $class->do($method,$data);  
            }

           
        }