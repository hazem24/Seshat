<?php
            namespace App\Model\WebServices\Twitter\Api\Account;
            use App\Model\WebServices\Twitter\Api\TwitterApi;



            /**
             *Class Manage Responsable For Read,Update a user's Account And Profile Settings. 
             */
            Class Manage extends TwitterApi
            {
            
                /**
                 * @method verfiyCredentials Check if supplied user credentials are valid.
                 * @return object|array
                 */
                public function verfiyCredentials(){
                       $verfiy_credentials = $this->connection->get("account/verify_credentials",["include_email"=>"true","skip_status"=>"true"]);
                       $anyApiError = $this->anyApiError($verfiy_credentials);
                       if($anyApiError === false){
                                    $return = $verfiy_credentials;
                       }else{
                                    $return = ['error'=>$anyApiError];
                       } 
                       return $return;
                }

            }