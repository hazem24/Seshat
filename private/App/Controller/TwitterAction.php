<?php

        namespace App\Controller;
        use Framework\Shared;
        use Framework\Error\WebViewError;
        use App\DomainHelper\FrontEndHelper;
        use App\DomainHelper\Helper;
        use Framework\Request\RequestHandler;
        use Framework\Lib\Upload\UploadImage;
        use Framework\Lib\Security\Data\FilterDataFactory;


        /**
        *This Class Just Provide The Logic Of All Action User Send It To Do Some Action In Twitter Like Tweet,Follow,...
        */

        Class TwitterAction extends AppShared
        {
                
           private $twitter_action_command;

           public function __construct(){
                    parent::__construct();
                    $this->twitter_action_command = Shared\CommandFactory::getCommand('twitterAction');
            }
        
            /**
             * @method composeTweetAction This Method Resonsable For All Logic Of Compose Tweet In Twitter.
             * 
             */
            public function composeTweetAction(){
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
                                        
                                $publishNow = $this->publishNewTweet($category,$tweetContent,$seshatPublicAccess);
                                if($this->anyAppError() === false){
                                     $response = $publishNow;           
                                }else{
                                     $user_need_reauth = $this->reauthUser($this->error);
                                     $response = ['error'=>($user_need_reauth === false) ? $this->error : ['reauth'=>$user_need_reauth[0]]];//reauth Index To Can Be Supplied By Javascript Can Know the type of error notify.
                                }
                        }elseif($schedule === true){
                                //Schedule Logic=>Table seshat_schedule.
                        }
                         echo json_encode($response);
                         exit;           
                    }
                    echo json_encode(["code"=>403,'error'=>"Protected Area You Cannot Access."]);
                    exit;
            }



            private function publishNewTweet($category,$tweetContent,$seshatPublicAccess){
                /**
                * 1 - If Tweet Have Media (Image) Check If Image Is Good And Size Is <= 5MB.
                * 2 - If Tweet Is Text Only Check The Text Chars <= 280.
                * 3 - The Two Scenrio (Image + Text).
                */ 
                $data = [TWEET_CONTENT=>['value'=>$tweetContent , 'type'=>'string','min'=>1, 'max'=>280],
                        ];
                    $filter = new FilterDataFactory($data);
                    $filter = $filter->getSecurityData();
                    $categoryType = Helper::categoryType($category);
                    $anyError = WebViewError::anyError('form' , $data , $filter);
                    if(is_array($anyError)){
                        $this->error[] = WebViewError::userErrorMsg($anyError);
                    }else{
                        $oauth_token = $this->session->getSession('oauth_token');
                        $oauth_token_secret = $this->session->getSession('oauth_token_secret');

                        if(isset($_FILES['tweetMedia']) && !empty($_FILES['tweetMedia']['name']) && !empty($_FILES['tweetMedia']['tmp_name'])){
                                //Image + Text.
                                $tweet = $this->twitter_action_command->execute(['Method'=>['name'=>"publishTweetWithMedia",'parameters'=>['status'=>$filter[TWEET_CONTENT],
                                'media'=>,
                                'oauth_token'=>$oauth_token,'oauth_token_secret'=>$oauth_token_secret,
                                'user_id'=>(int)$this->session->getSession('id'),'category'=>$categoryType,'publicAccess'=>$seshatPublicAccess]]]);

                        }else{
                                //Text Only.
                                 $tweet = $this->twitter_action_command->execute(['Method'=>['name'=>"publishTweet",'parameters'=>['status'=>$filter[TWEET_CONTENT],
                                'oauth_token'=>$oauth_token,'oauth_token_secret'=>$oauth_token_secret,
                                'user_id'=>(int)$this->session->getSession('id'),'category'=>$categoryType,'publicAccess'=>$seshatPublicAccess]]]);
                                if(array_key_exists('error',$tweet)){
                                        if($tweet['error'] === true){
                                                $this->error[] = TWEET_NOT_SAVED;
                                        }else{
                                                $this->error[] = $tweet['error'];
                                        }
                                }else if(array_key_exists('success',$tweet)){
                                                return ['success'=>TWEET_UPLOAD_SUCCESS];
                                }
                        }
                    }
                    

            }


           
        }