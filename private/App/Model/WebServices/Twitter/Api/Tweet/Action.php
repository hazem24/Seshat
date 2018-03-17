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

                 /**
                  *@method writeToTweet create An Action To Twitter Tweet (unlike-like-retweet-unretweet-relay). 
                  *@return object|array.
                  */
                  //POST https://api.twitter.com/1.1/favorites/create.json?id=243138128959913986
                  //POST https://api.twitter.com/1.1/favorites/destroy.json?id=243138128959913986
                  //POST https://api.twitter.com/1.1/statuses/update.json?status=Maybe%20he%27ll%20finally%20find%20his%20keys.%20%23peterfalk

                  public function writeToTweet(array $parameters){
                        $tweet_id = $parameters['tweet_id']; $method = $parameters['type'];
                        return $this->$method($tweet_id);
                  }
                  /**
                   * @method retweet do retweet for specific tweet. POST https://api.twitter.com/1.1/statuses/retweet/243149503589400576.json
                   * @return object|array.
                   */
                  private function retweet(string $tweet_id){
                        $retweet = $this->connection->post('statuses/retweet',['id'=>$tweet_id,"tweet_mode"=>"extended"]);
                        return $this->getResponse($retweet);
                  }
                  /**
                   * @method unretweet do unretweet for specific tweet. POST https://api.twitter.com/1.1/statuses/unretweet/243149503589400576.json
                   * @return object|array.
                   */
                  private function unretweet(string $tweet_id){
                        $unretweet = $this->connection->post('statuses/unretweet',['id'=>$tweet_id,"tweet_mode"=>"extended"]);
                        return $this->getResponse($unretweet);
                  }

                  /**
                   * @method like do like for specific tweet. POST https://api.twitter.com/1.1/favorites/create.json?id=243138128959913986
                   * @return object|array.
                   */

                   private function like(string $tweet_id){
                        $like = $this->connection->post("favorites/create",['id'=>$tweet_id]);
                        return $this->getResponse($like);

                   }

                   /**
                    * @method unlike do unlike for specific tweet. POST https://api.twitter.com/1.1/favorites/destroy.json?id=243138128959913986
                    * @return object|array.    
                    */

                    private function unlike(string $tweet_id){
                        $unlike = $this->connection->post("favorites/destroy",['id'=>$tweet_id]);
                        return $this->getResponse($unlike);
                    }



            }