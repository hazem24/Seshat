<?php
    namespace App\Model\WebServices\Twitter\Api;
    use App\Model\WebServices\Twitter\Api\TwitterApi;



     /**
        *Class AbstractAction an abstract class for all type of action can be done in twitter. 
    */
    Abstract Class AbstractAction extends TwitterApi
    {
            
    /**
    *@method writeToTwitter create An Action To Twitter Tweet (unlike-like-retweet-unretweet-relay-follow-unfollow). 
    *@return object|array.
    */

    public function writeToTwitter(array $parameters){
        $parameter = $parameters['parameters']; $method = $parameters['type'];
        return $this->$method($parameter);
    }
}