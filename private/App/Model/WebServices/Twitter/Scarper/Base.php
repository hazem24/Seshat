<?php
    namespace App\Model\WebServices\Twitter\Scarper;
    use Framework\Lib\Curl\CurlClass;

    /**
     * This Class Provide an abstract class for all scarper twitter classes.
     */

    Abstract class Base
    {
          /**
           * @property curl instance of class CurlClass Which will uses to scarper twitter.
           */
          protected $curl;
          /**
           * @property twitterUrl url of mobile twitter site to scarp it.
           */
          protected $twitterUrl = "https://mobile.twitter.com/";
  
          public function __construct(){
                $this->curl = new CurlClass;
          }  
    } 