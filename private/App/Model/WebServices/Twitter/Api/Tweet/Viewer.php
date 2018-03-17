<?php
            namespace App\Model\WebServices\Twitter\Api\Tweet;
            use App\Model\WebServices\Twitter\Api\TwitterApi;



            /**
             *Class Viewer Responsable For Read Data From Twitter Api And Return Response To User In Seshat.
             */
            Class Viewer extends TwitterApi
            {
             /**
              * @method readtTimeLine Read TimeLine From User Account.
              * @return array.
              */
              public function readTimeLine(){
                  $timeLineTweet = $this->connection->get("statuses/home_timeline",["count"=>"50","exclude_replies"=>"false","include_entities"=>"true","tweet_mode"=>"extended"]);
                  return $this->getResponse($timeLineTweet);
              }

            }