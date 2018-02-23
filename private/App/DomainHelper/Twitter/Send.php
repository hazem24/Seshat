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

            protected function publishNewTweet(array $parameter){
                 return $this->command->execute(['Method'=>['name'=>"publishTweet",'parameters'=>['status'=>$parameter['tweetContent'],
                    'media'=>$parameter['media'],
                    'oauth_token'=>$parameter['oauth_token'],'oauth_token_secret'=>$parameter['oauth_token_secret'],
                    'user_id'=>$parameter["user_id"],'category'=>$parameter['category'],'publicAccess'=>$parameter['seshatPublicAccess']]]]); 
            }
         }
