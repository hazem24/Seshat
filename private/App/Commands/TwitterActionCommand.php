<?php
        namespace App\Commands;
        use Framework\Shared;
        use Framework\Lib\Upload\UploadImage;
        use App\Model\App\Seshat;



        /**
        *This Class Have All Commands That Do Action In Twitter.
        */

        Class TwitterActionCommand extends Shared\AbstractCommand
        {
            CONST MAX_IMAGE_SIZE = 5242880;//5MB.
            private $twitter_api_command;

            public function __construct(){
                parent::__construct();
                $this->twitter_api_command = Shared\CommandFactory::getCommand('twitterApi');
            }

            public function execute(array $data = []){
                $method = $data['Method']['name']; 
                if(method_exists($this,$method)){
                         return call_user_func_array(array($this,"$method") , array($data['Method']['parameters']));
                }  
            }
            /**
             * @method publishTweet Responsable For Publish Tweet Without Media.
             * @return array
             */
            private function publishTweet(array $parameters=[]){
                    $tweetContent = $parameters['status'];
                    $oauth_token = $parameters['oauth_token'];
                    $oauth_token_secret = $parameters['oauth_token_secret'];
                    $category =  array_keys($parameters['category'])[0];
                    $user_id = $parameters['user_id'];
                    $publicAccess = $parameters['publicAccess'];
                    $publish_text_tweet = $this->twitter_api_command->execute(['ModelClass'=>"Tweet\\Action",'Method'=>['Name'=>'postTextTweet','parameters'=>['status'=>$tweetContent],'user_auth'=>['status'=>true,'access_token'=>$oauth_token
                    ,'access_token_secret'=>$oauth_token_secret]]]);
                    if(is_object($publish_text_tweet)){
                            $tweet_id = $publish_text_tweet->id_str;
                            return ($this->saveTweet($user_id,$category,$tweet_id,$publicAccess) === true) ? ['success'=>true] : ['error'=>true];
                    }else{
                            return $publish_text_tweet;
                    }
                    
            }

            private function saveTweet(int $user_id,int $category,string $tweet_id,bool $publicAccess){
                    $publishModel = new Seshat\PublishModel;
                    $publishModel->setProperty('user_id',$user_id);
                    $publishModel->setProperty('category_id',$category);
                    $publishModel->setProperty('tweet_id',$tweet_id);
                    $publishModel->setProperty('public_access',$publicAccess);
                    return $publishModel->saveNewPublish();

            }



        }
