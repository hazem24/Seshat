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
            /**
             * write some action like (follow-unfollow-retweet-unretweet-like-unlike) to twitter (create an action to twitter).
             * @method writeToTwitter.
             */
            protected function writeToTwitter(array $parameter){
                return $this->command->execute(['Method'=>['name'=>"writeToTwitter",'parameters'=>['type'=>$parameter['type'],
                'parameters'=>$parameter['parameters'],'scope'=>$parameter['scope'],
                'oauth_token'=>$parameter['oauth_token'],'oauth_token_secret'=>$parameter['oauth_token_secret']]]]);  
            }
         }
