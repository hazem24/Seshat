<?php
        namespace App\DomainHelper\Twitter;
        use Framework\Shared\CommandFactory;
        use Framework\Helper\Html;



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
                 return $this->command->execute(['Method'=>['name'=>"publishTweet",'parameters'=>['status'=>Html::decodeDataToHtml($parameter['tweetContent']),
                    'media'=>$parameter['media'],'mediaPath'=>$parameter['mediaPath'] ?? '','tweet_id'=>$parameter['tweet_id'] ?? '',
                    'access_token'=>$parameter['access_token'],'access_token_secret'=>$parameter['access_token_secret'],
                    'user_id'=>$parameter["user_id"],'category'=>$parameter['category'] ?? 0,'publicAccess'=>$parameter['publicAccess'] ?? 0]]]); 
            }
            /**
             * write some action like (follow-unfollow-retweet-unretweet-like-unlike) to twitter (create an action to twitter).
             * @method writeToTwitter.
             */
            protected function writeToTwitter(array $parameter){
                return $this->command->execute(['Method'=>['name'=>"writeToTwitter",'parameters'=>['type'=>$parameter['type'],
                'parameters'=>$parameter['parameters'],'scope'=>$parameter['scope'],
                'access_token'=>$parameter['access_token'],
                'access_token_secret'=>$parameter['access_token_secret']]]]);  
            }
         }
