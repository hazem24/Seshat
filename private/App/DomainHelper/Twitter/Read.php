<?php
        namespace App\DomainHelper\Twitter;
        use Framework\Shared\CommandFactory;

        /**
         * Class Read Responsable For All Logic Of Read Something From Twitter.
         */

         class Read extends Action
         {
            
            public function __construct(){
                   $this->command = $this->initCommand();
            }
            protected function initCommand(){
                    return CommandFactory::getCommand('twitterApi');
            }
            /**
             * @method readTimeLine Read time line of oAuth User.
             */
            public function readTimeLine(array $parameter = []){
                      return $this->command->execute(["ModelClass"=>"Tweet\\Viewer","Method"=>
                      ['Name'=>"readTimeLine","parameters"=>[],"user_auth"=>['status'=>true,'access_token'=>$parameter["oauth_token"]
                      ,'access_token_secret'=>$parameter['oauth_token_secret']]]]);
            }
         }
