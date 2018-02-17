<?php
            namespace App\Model\WebServices\Twitter\Api\Tweet;
            use App\Model\WebServices\Twitter\Api\TwitterApi;



            /**
             *Class Manage Responsable For Read,Update a user's Account And Profile Settings. 
             */
            Class Action extends TwitterApi
            {
            
                /**
                 * @method verfiyCredentials Check if supplied user credentials are valid.
                 * @return object|array
                 */
                public function postTextTweet(array $parameters){
                       $tweetContent = $parameters['status']; 
                       $newTweet     = $this->connection->post('statuses/update',['status'=>$tweetContent]);
                       $anyApiError  = $this->anyApiError($newTweet);
                       if($anyApiError === false){
                                return $newTweet;
                       }
                                return ['error'=>$anyApiError];
 
                }

            }