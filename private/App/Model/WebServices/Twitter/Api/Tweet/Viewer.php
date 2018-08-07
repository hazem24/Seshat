<?php
            namespace App\Model\WebServices\Twitter\Api\Tweet;
            use App\Model\WebServices\Twitter\Api\TwitterApi;

            /**
             *Class Viewer Responsable For Read Data From Twitter Api And Return Response To User In Seshat.
             */
            Class Viewer extends TwitterApi
            {
             /**
              * @method readtTimeLine Read TimeLine From User Account (User Which Auth To My App).
              * @return array.
              */
              public function readTimeLine(){
                  $timeLineTweet = $this->connection->get("statuses/home_timeline",["count"=>"100","exclude_replies"=>"true","include_entities"=>"true","tweet_mode"=>"extended"]);
                  return $this->getResponse($timeLineTweet);
              }

              /**
               * @method searchTweets. GET https://api.twitter.com/1.1/search/tweets.json.
               * @return array.
               */
              public function searchTweets(array $parameters = []){
                    $search_tweets = $this->connection->get("search/tweets",["q"=>$parameters['q'],'result_type'=>$parameters['result_type'],"max_id"=>$parameters['max_id'],"until"=>$parameters['until'],"since_id"=>$parameters['since_id'],"count"=>"100","tweet_mode"=>"extended"]);
                    return $this->getResponse($search_tweets);
              }

              /**
               * @method getRetweets. GET https://api.twitter.com/1.1/statuses/retweets/509457288717819904.json.
               * @return array.
               */
              public function getRetweets(array $parameters = []){
                     $retweeters = $this->connection->get("statuses/retweets",['id'=>$parameters['tweet_id'],'count'=>'100','trim_user'=>"false","tweet_mode"=>"extended"]); 
                     return $this->getResponse($retweeters);
              }

              /**
               * @method favList. GET https://api.twitter.com/1.1/favorites/list.json?count=2&screen_name=episod.
               * @return array.
               */
               public function favList(array $parameters = []){
                    $favList = $this->connection->get("favorites/list",['screen_name'=>$parameters['screenName'],'count'=>'50']);
                    return $this->getResponse($favList);
               }

               /**
                * @method showTweet. GET https://api.twitter.com/1.1/statuses/show.json?id=210462857140252672.
                * @return array.
                */
                public function showTweet(array $parameters = []){
                    $getTweet = $this->connection->get("statuses/show",['id'=>$parameters['tweet_id'],'include_my_retweet'=>'false',"tweet_mode"=>"extended"]);
                    return $this->getResponse($getTweet);
                }

               

                /**
                 * @method userTimeLine. GET https://api.twitter.com/1.1/statuses/user_timeline.json.
                 * @return array.
                 */
                public function userTimeLine (array $parameters = []) {
                    $getUserTimeLine = $this->connection->get("statuses/user_timeline" , ['screen_name'=>$parameters['screen_name'] , 'count'=>'50' , "tweet_mode"=>"extended" , 'exclude_replies'=>"true"]);
                    return $this->getResponse($getUserTimeLine);
                }
            }