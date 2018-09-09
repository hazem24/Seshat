<?php
        namespace App\Commands;
        use Framework\Shared;
        use Framework\Lib\Upload\UploadImage;
        use App\Model\App\Seshat;



        /**
        *This Class Have All Commands That Do Action In Twitter.
        */

        Class TwitterActionCommand extends TwitterCommand
        {
            private $twitter_api_command;

            public function __construct(){
                parent::__construct();
                $this->twitter_api_command = Shared\CommandFactory::getCommand('twitterApi');
            }

            /**
             * @method publishTweet Responsable For Publish Tweet.
             * @return array.
             */
            protected function publishTweet(array $parameters=[]){
                    $tweetContent = $parameters['status'];
                    $oauth_token = $parameters['oauth_token'];
                    $oauth_token_secret = $parameters['oauth_token_secret'];
                    $category =  $parameters['category'];
                    $user_id = $parameters['user_id'];
                    $publicAccess = $parameters['publicAccess'];
                    $tweet_id = $parameters['tweet_id'];//incase of user want to replay to specific tweet.
                    if(isset($parameters['media']) && $parameters['media'] === true){
                        //Tweet With Media(image).
                        $uploadMedia = $this->uploadMedia($user_id);
                        if(is_array($uploadMedia)){
                                $publish_tweet = ['uploadError'=>$uploadMedia];//Error At Upload Image To Seshat System.
                        }else if(is_string($uploadMedia)){
                                $publish_tweet = $this->twitter_api_command->execute(['ModelClass'=>"Tweet\\Action",'Method'=>['Name'=>'postMediaTweet',
                                'parameters'=>['status'=>$tweetContent,'tweet_id'=>$tweet_id,'media'=>self::UPLOAD_IMAGE_PATH.$uploadMedia],
                                'user_auth'=>['status'=>true,'access_token'=>$oauth_token
                                ,'access_token_secret'=>$oauth_token_secret]]]); 
                        }
                        
                    }else{
                        //Tweet Text Only.
                        $publish_tweet = $this->twitter_api_command->execute(['ModelClass'=>"Tweet\\Action",'Method'=>['Name'=>'postTextTweet','parameters'=>['status'=>$tweetContent,'tweet_id'=>$tweet_id],'user_auth'=>['status'=>true,'access_token'=>$oauth_token
                        ,'access_token_secret'=>$oauth_token_secret]]]);         
                    }
                    if(is_object($publish_tweet)){
                            $tweet_id = $publish_tweet->id_str;
                            return ($this->saveTweet($user_id,$category,$tweet_id,$publicAccess) === true) ? ['success'=>true] : ['error'=>true];
                    }else{
                            return $publish_tweet;
                    }
                    
            }

            private function saveTweet(int $user_id,int $category,string $tweet_id,bool $publicAccess){
                    $publishModel = new Seshat\PublishModel;
                    $publishModel->setProperty('user_id',$user_id);
                    $publishModel->setProperty('category_id',$category);
                    $publishModel->setProperty('tweet_id',$tweet_id);
                    $publishModel->setProperty('public_access',$publicAccess);
                    return $publishModel->save();

            }

            protected function writeToTwitter(array $parameters){//$scope tweet or users.
                $scope = ucfirst(strtolower($parameters['scope']));
                $write_type = $parameters['type'];
                $oauth_token = $parameters['oauth_token'];
                $oauth_token_secret = $parameters['oauth_token_secret'];
                $parameters = $parameters['parameters'];
                return $this->twitter_api_command->execute(['ModelClass'=>"$scope\\Action",'Method'=>['Name'=>'writeToTwitter',
                        'parameters'=>['parameters'=>$parameters,'type'=>$write_type],
                        'user_auth'=>['status'=>true,'access_token'=>$oauth_token
                        ,'access_token_secret'=>$oauth_token_secret]]]);
            }



        }
