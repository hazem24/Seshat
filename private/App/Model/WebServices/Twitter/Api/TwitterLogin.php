<?php

    namespace App\Model\WebServices\Twitter\Api;


    /**
     * Login Class Provide Tools To Interact With Login With Twitter Api.
     */
    Class TwitterLogin extends TwitterApi
    {
        CONST CALL_BACK = BASE_URL.LINK_SIGN."index/seshatConnector";

        /**
         *@method generateUrl.
         *@return array of the generated Url AND Token's From Twitter. 
         */

         public function generateUrl():array{ 
                $requestToken = $this->connection->oauth('oauth/request_token', ['oauth_callback' => self::CALL_BACK]);
                $url = $this->connection->url('oauth/authenticate', ['oauth_token' => $requestToken['oauth_token']]);
                
                $anyError = $this->anyApiError($url);
                if($anyError === false){
                           $return = ['url'=>$url,'oauth_token'=>$requestToken['oauth_token'],'oauth_token_secret'=>$requestToken['oauth_token_secret']];

                }else {
                            $return = ['error'=>$anyError];
                }  
                return $return;

         }

         /**
          * @method confirmUser Provide the second step to get from twitter Api.
                *Final Access Token , Secret.
                *tw_id.
                *ScreenName.  
          */
         public function confirmUser(array $parameters):array{
                $oauth_verifier = $parameters['oauth_verifier'];
                $accessToken = $this->connection->oauth("oauth/access_token", ["oauth_verifier" => $oauth_verifier]);

                $httpResponse = $this->apiHttpResponse();
                if($httpResponse === true){
                        $return = $accessToken;
                }else{
                        $return = ['error'=>$httpResponse];
                }

                return $return;
         }
    }
