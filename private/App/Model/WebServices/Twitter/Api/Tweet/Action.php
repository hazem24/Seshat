<?php
            namespace App\Model\WebServices\Twitter\Api\Tweet;
            use App\Model\WebServices\Twitter\Api\TwitterApi;



            /**
             *Class Action Responsable For Do Actions in Twitter User Account. 
             */
            Class Action extends TwitterApi
            {
            
                /**
                 * @method postTextTweet post Text Tweet To Twitter || replay to specific tweet in twitter.
                 * @return object|array.
                 */
                public function postTextTweet(array $parameters){
                       $tweetContent = $parameters['status']; 
                       $tweet_id     = $parameters['tweet_id'];//incase of user want to replay to specifc tweet.
                       $newTweet     = $this->connection->post('statuses/update',['status'=>$tweetContent,'in_reply_to_status_id'=>$tweet_id]);
                       return $this->getResponse($newTweet); 
                }

                /**
                 * @method postMediaTweet post Media Tweet To Twitter.
                 * @return object|array.
                 */

                 public function postMediaTweet(array $parameters){
                        $tweetContent = $parameters['status'];
                        $mediaPath    = $parameters['media'];
                        $tweet_id = $parameters['tweet_id'];//incase of user want to replay to specifc tweet.
                        $media = $this->connection->upload('media/upload', ['media' => $mediaPath]); 
                        $newTweet     = $this->connection->post('statuses/update',['status'=>$tweetContent,'in_reply_to_status_id'=>$tweet_id,'media_ids'=>implode(',', [$media->media_id_string])]);
                        return $this->getResponse($newTweet);
                 }

                 /**
                  *@method writeToTwitter create An Action To Twitter Tweet (unlike-like-retweet-unretweet-relay-follow-unfollow). 
                  *@return object|array.
                  */
                  //POST https://api.twitter.com/1.1/favorites/create.json?id=243138128959913986
                  //POST https://api.twitter.com/1.1/favorites/destroy.json?id=243138128959913986
                  //POST https://api.twitter.com/1.1/statuses/update.json?status=Maybe%20he%27ll%20finally%20find%20his%20keys.%20%23peterfalk

                  public function writeToTwitter(array $parameters){
                        $parameter = $parameters['parameters']; $method = $parameters['type'];
                        return $this->$method($parameter);
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

                  /**
                  * @method follow follow specfic user in twitter provided by {{ user_name }}. POST  https://api.twitter.com/1.1/friendships/create.json.
                  * @return object | array.
                  */
                  private function follow (string $user_id){
                        $follow = $this->connection->post("friendships/create",['user_id'=>$user_id]);
                        return $this->getResponse($follow);
                  }
                  
                  /**
                  * @method unfollow unfollow specific user in twitter provided by {{ user_name }}. POST https://api.twitter.com/1.1/friendships/destroy.json.
                  * @return object | array. 
                  */
                  private function unfollow (string $user_id){
                        $unfollow = $this->connection->post("friendships/destroy",['user_id'=>$user_id]);
                        return $this->getResponse($unfollow);
                  }

            }