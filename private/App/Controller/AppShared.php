<?php
        namespace App\Controller;
        use Framework\Shared;
        use App\DomainHelper\FrontEndHelper; 
        use App\DomainHelper\FastCache;
        

    /**
     * AppShared Controller Have All Logic That Can Be Shared Via All App.
     */
    Abstract Class AppShared extends Shared\Controller
    {
        /**
        * @property cache Save And Instance Of Fast Cache Class.
        */
        protected $cache = null;
        protected $error = [];
           
        public function setError (string $error) {
                $this->error[] = $error; 
        }
        /**
        * @method generateTwitterLoginUrl.
        */
        public function generateTwitterLoginUrl():string{
                $cmd = Shared\CommandFactory::getCommand('twitterApi');
                $generateUrl = $cmd->execute(['ModelClass'=>"TwitterLogin",'Method'=>['Name'=>"generateUrl",'parameter'=>[],'user_auth'=>['status'=>false]]]);
                if(isset($generateUrl['error'])){
                           $return = $generateUrl['error'];// I Must Think For A method to notify User About Any Error In Th App.
                }else if(isset($generateUrl['url'])){
                           /**
                            * 1-save Token In session To Use It In Call Back.
                            * 2-Return the generated Url.
                            */
                           //Clear All Session Rstore It to empty Data.
                           $this->session->clear();
                           $this->saveToken($generateUrl['oauth_token'],$generateUrl['oauth_token_secret']);
                           $return = $generateUrl['url'];
                }
                return $return;
        }

        /**
          * @method redirectToWizard redirect User To Wizard If Needed.
        */
        public function redirectToWizard(){
                $redirectToWizard = (bool)$this->session->getSession('wizard');
                $tw_id = (bool)$this->session->getSession("tw_id");
                if($redirectToWizard === true && $tw_id === true && strtolower($this->actionToCall) !== 'createprofileaction'){
                            self::rIn('tw_id','seshat/createProfile');
                }
        }
        


        public function saveToken(string $oauth_token ='no_token',string $oauth_token_secret='no_secret_token'){

                //Set New Pair Of oauth_token.
                $this->session->setSession('oauth_token',$oauth_token);
                $this->session->setSession('oauth_token_secret',$oauth_token_secret);
                
        }

        public function anyAppError(){
                if(!empty($this->error)){
                        return true;
                }
                return false;
        }

        /**
         * @method reauthUser This Method Check If User Need Reauthinaction Again Beacause He/She Revoked App.
         * @param error&&ajax = false =>Check If Response Must Me Json Encoded Beacuse It Come From Ajax Request.
         * @return json|bool|array
         */
        public function reauthUser($error,bool $ajax = false){
                  if(is_array($error[0])&&array_key_exists('reauth',$error[0])){
                                if($ajax === true){
                                        $this->encodeResponse($error);//not used it may be deleted this option written @03:18 Am 08/04/2018.     
                                }else{
                                        $url = $this->generateTwitterLoginUrl();
                                        return [FrontEndHelper::reauthUserModal($url,$error[0]['reauth'])]; 
                                }
                  } 
                return false;       
        }
        /**
         * @method fastCache get An Instance of FastCache Class And Prevent dupicate of FastCache Method.
         * @return FastCache.
         */
        public function fastCache():FastCache{
              if(is_null($this->cache) === true){
                        $this->cache = new FastCache();
              }
              return $this->cache;
        }
        
           /**
            *@method verfiyCredentials Get User Email-Name-Image For Wizard Proccess && seshatTimeLine.
            *@return array. 
            */
        protected function verfiyCredentials(){
                $oauth_token = $this->session->getSession('oauth_token');
                $oauth_token_secret = $this->session->getSession('oauth_token_secret');
                $cmd = Shared\CommandFactory::getCommand('twitterapi');
                $userCredients = $cmd->execute(['ModelClass'=>"Account\\Manage",'Method'=>['Name'=>'verfiyCredentials','parameters'=>[],'user_auth'=>['status'=>true,'access_token'=>$oauth_token
                ,'access_token_secret'=>$oauth_token_secret]]]);
                //Handle Error Here !
                if(array_key_exists('error',$userCredients)){
                        $this->error[] = $userCredients['error'];//['reauth'=>"msg"]
                }else{
                         return $userCredients;
                }
                
        }
        /**
         * @method rule create rules if user can access specific area of app.
         */
        public function rule(string $rule = 'access'){
                  $this->detectLang();
                  if(strtolower($rule) == 'access'){
                        self::rOut('tw_id','index/signin');
                        $this->redirectToWizard();
                  }else if(strtolower($rule) == 'notaccess'){
                        self::rIn("tw_id","seshatTimeline");           
                  }
        }

        /**
         * @method getTokens return tokens for specific seshat user.
         * @return array.
         */
        public function getTokens(){
                //oauth value.
                $oauth_token = $this->session->getSession('oauth_token');
                $oauth_token = ($oauth_token !== false) ? $oauth_token : 'no_token';
                //secret value.
                $oauth_token_secret = $this->session->getSession('oauth_token_secret');
                $oauth_token_secret = ($oauth_token_secret !== false) ? $oauth_token_secret : 'no_token_secret';
                return ['oauth_token'=>$oauth_token,'oauth_token_secret'=>$oauth_token_secret];
        }

        /**
         * @method commonError.
         * @return void.
         */
        public function commonError(array $data_has_error){
                if(array_key_exists('AppError',$data_has_error) === true){
                        $this->error[] = GLOBAL_ERROR;
                }else if (array_key_exists('error',$data_has_error) === true){
                        $this->error[] = $data_has_error['error'];
                }
        }

        /**
         * @method returnErrorToUser.
         */
        public function returnResponseToUser($dataToReturnToUser){
                if($this->anyAppError() === false){
                        $response = $dataToReturnToUser;           
                }else{
                        $user_need_reauth = $this->reauthUser($this->error);
                        $response = ['error'=>($user_need_reauth === false) ? $this->error : ['reauth'=>$user_need_reauth[0]]];//reauth Index To Can Be Supplied By Javascript Can Know the type of error notify.
                }
                return $response;
        }

        /**
         * @method encodeResponse.
         * @return json.
         */
        public function encodeResponse($data_to_encode , bool $stop_script = true){
                echo json_encode($data_to_encode);
                if($stop_script === true){
                        self::stop();
                }
        }
        /**
         * @method stop.
         * @return null.
         */

        public static function stop (){
                exit;
        }

    }