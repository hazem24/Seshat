<?php
        namespace App\DomainHelper\Twitter;
        use Framework\Shared\CommandFactory;



        /**
         * Class Send Responsable For All Logic Of Send Something To Twitter.
         */

         class Send extends Action
         {
            
            public function __construct(){
                   $this->command = $this->initCommand();
            }
            protected function initCommand(){
                    return CommandFactory::getCommand('twitterAction');
            }
            /**
             * @method publishNewTweet responsable for create new tweet && replay to specific tweet.
             */
            protected function publishNewTweet(array $parameter){
                 return $this->command->execute(['Method'=>['name'=>"publishTweet",'parameters'=>['status'=>$parameter['tweetContent'],
                    'media'=>$parameter['media'],'tweet_id'=>$parameter['tweet_id'],
                    'oauth_token'=>$parameter['oauth_token'],'oauth_token_secret'=>$parameter['oauth_token_secret'],
                    'user_id'=>$parameter["user_id"],'category'=>$parameter['category'],'publicAccess'=>$parameter['seshatPublicAccess']]]]); 
            }
            protected function writeToTweet(array $parameter){
                      return $this->command->execute(['Method'=>['name'=>"writeToTweet",'parameters'=>['type'=>$parameter['type'],
                      'tweet_id'=>$parameter['tweet_id'],
                      'oauth_token'=>$parameter['oauth_token'],'oauth_token_secret'=>$parameter['oauth_token_secret']]]]);  
            }
         }
