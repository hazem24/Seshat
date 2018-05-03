<?php
    namespace App\Model\WebServices\Twitter\Scarper;


    /**
     * This Class responsable for scarper twitter to get specific information.
     */

     Class Read extends Base
     {
        use Helper; 
        /**
         * @method getReplies get replies of specific tweet.
         * @return array.
         */
        public function getReplies(string $screenName , string $tweet_id){
               $status = $this->twitterUrl."$screenName"."/status/$tweet_id"; 
               $this->curl->setOpts([CURLOPT_RETURNTRANSFER =>true,CURLOPT_HEADER=>false,CURLOPT_AUTOREFERER=>true,CURLOPT_SSL_VERIFYPEER=>true,CURLOPT_MAXREDIRS =>10]);
               $this->curl->get($status);
               $tweet_page = $this->curl->response(); 
               $this->curl->close();
               self::repliesFilter($tweet_page);
        }
     }