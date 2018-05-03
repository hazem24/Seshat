<?php
    namespace App\Commands;
    use App\DomainHelper\Twitter;


    /**
     * this class responsble for all commands that seshat can do like analytic.
     */

    Class SeshatCommand extends BaseCommand
    {
        /**
         * @note any method that want to call it from outside the class must be protected and can be called via "execute" method in the base.
         * incase of parameters must send to method send it as an array of $param.
         */
        /**
         * @method getAnalytic get analytic of specific tweet.
         */
        protected function getAnalytic(array $params){
                  /**
                   * analtyic consists of :
                        *Statics about user it self (from tweet itself i can do this).
                        *Tweet Itself.
                        *Some user replies.
                        *some statics from user Reaction to this tweet (GeoLocation).
                   * */
                $data = ['screenName'=>$params['screenName'],
                'tweet_id'=>$params['tweet_id'],'oauth_token'=>$params['oauth_token'],'oauth_token_secret'=>$params['oauth_token_secret']];  
                $read = new Twitter\Read;
                $show_tweet = $read->do('showTweet',$data);
            
                $retweeters   = $read->do('getRetweets',$data);//Last 100 Person reacted to this tweet.
                $replies = $read->do("getReplies",$data);   
                return ['tweet'=>$show_tweet,'replies'=>$replies,'reacted_user'=>$retweeters];
        }
    } 