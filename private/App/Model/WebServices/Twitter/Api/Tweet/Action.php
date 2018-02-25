<?php
            namespace App\Model\WebServices\Twitter\Api\Tweet;
            use App\Model\WebServices\Twitter\Api\TwitterApi;



            /**
             *Class Action Responsable For Do Actions in Twitter User Account. 
             */
            Class Action extends TwitterApi
            {
            
                /**
                 * @method postTextTweet post Text Tweet To Twitter.
                 * @return object|array.
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

                /**
                 * @method postMediaTweet post Media Tweet To Twitter.
                 * @return object|array.
                 */

                 public function postMediaTweet(array $parameters){
                        $tweetContent = $parameters['status'];
                        $mediaPath    = $parameters['media'];
                        $media = $this->connection->upload('media/upload', ['media' => $mediaPath]); 
                        $newTweet     = $this->connection->post('statuses/update',['status'=>$tweetContent,'media_ids'=>implode(',', [$media->media_id_string])]);
                        $anyApiError  = $this->anyApiError($newTweet);
                        if($anyApiError === false){
                                return $newTweet;
                        }
                                return ['error'=>$anyApiError];
                 }

            }