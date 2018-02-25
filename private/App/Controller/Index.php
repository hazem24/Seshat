<?php
        namespace App\Controller;
        use Framework\Request\RequestHandler;
        use Framework\Lib\Security\Data\FilterDataFactory;
        use Framework\Error\WebViewError;
        use Framework\Shared;
        
        
        Class Index extends AppShared
        {
            /**
            *@method indexAction
            */    
             public function defaultAction(){
                     $this->redirectToWizard();
                     $this->rIn("tw_id","seshatTimeline");
                     $this->render();
             }   





            public function signInAction(){
                    $this->detectLang();
                    $this->redirectToWizard();
                    $this->rIn("tw_id","seshatTimeline");
                    $this->actionView->setDataInView(["login_url"=>(object)['generatedUrl'=>$this->generateTwitterLoginUrl()]]);
                    $this->render();
            }

            

            /**
             * @method seshatConnector Provide THe Call back Action from Twitter. 
             */
            public function seshatConnectorAction(){
                   $this->detectLang();
                   $oauth_token = $this->session->getSession('oauth_token');
                 
                   if(RequestHandler::getRequest() && $oauth_token !== false){
                           
                        $oauth_token_get = (string)RequestHandler::get('oauth_token'); 
                        $oauth_token_secret = $this->session->getSession('oauth_token_secret');      
                        $oauth_verifier = (string)RequestHandler::get('oauth_verifier');
                        
                        $this->checkAuthnication($oauth_token_get,$oauth_token,$oauth_verifier,$oauth_token_secret);
                        
                   }
                   echo json_encode(["code"=>403,'error'=>"Protected Area You Cannot Access."]);
                   exit;
            }

            /**
             * @method checkAuthnication Check The Proccess Of auth After User returned From Twitter.
             * @method Have Two Scenrio.
                        *First If User Authicate To The App From Signin Button.--Done.
                        *Second If The User Inside The App And Revoke Access The App. 
             */
            private function checkAuthnication(string $oauth_token_get,string $oauth_token,string $oauth_verifier,string $oauth_token_secret){
                if(!empty($oauth_token_get) && $oauth_token == $oauth_token_get){
                        $confirmUser = $this->confirmUser($oauth_token_get,$oauth_token_secret,$oauth_verifier);
                                if($this->anyAppError() === false){
                                                /**
                                                 * 'oauth_token' => string '747262122229436416-v4U8oBrfTQjAzLzHMvkmi94PgNncwC9' (length=50)
                                                 * 'oauth_token_secret' => string 'nvRWnu7hxPozSaHCDmQmUJcfMiE0OpRq2b16q6UUsyou0' (length=45)
                                                 * 'user_id' => string '747262122229436416' (length=18)
                                                 * 'screen_name' => string 'Hazem13596846' (length=13)
                                                 * 'x_auth_expires' => string '0' (length=1)
                                                 */      
                                        $this->userStatus($confirmUser['user_id'],$confirmUser['screen_name'],$confirmUser['oauth_token'],$confirmUser['oauth_token_secret']); 
                                        if($this->anyAppError() === true){        
                                                $this->session->setSession('error',$this->error);
                                                $this->rOut('tw_id','index/signin');
                                        } 
                                }else{        
                                        $this->session->setSession('error',$this->error);
                                        $this->rOut('tw_id','index/signin');
                                }
                }else{
                        echo json_encode(["code"=>403,'error'=>"Protected Area You Cannot Access."]);
                        exit;
                }    
            }


            private function confirmUser(string $oauth_token , string $oauth_token_secret, string $oauth_verifier){
                $cmd = Shared\CommandFactory::getCommand('twitterApi');
                $confirmUser = $cmd->execute(['ModelClass'=>"TwitterLogin",'Method'=>['Name'=>"confirmUser",'parameters'=>['oauth_verifier'=>$oauth_verifier],'user_auth'=>['status'=>true,'access_token'=>$oauth_token
                ,'access_token_secret'=>$oauth_token_secret]]]);

                if(array_key_exists('error',$confirmUser)){
                                $this->error[] = $confirmUser['error'];
                }else if(array_key_exists('oauth_token',$confirmUser)){
                                return $confirmUser;
                }
            }

            /**
             * @method userStatus Check Status Of User Where he/she is in (wizard ? new ? Exists).
             * 
             */
            private function userStatus(string $tw_id,string $screen_name,string $oauth_token,string $oauth_token_secret){
                    $cmd = Shared\CommandFactory::getCommand('user');
                    $userStatus = $cmd->execute(['Method'=>['name'=>'checkUserStatus','parameters'=>['tw_id'=>$tw_id
                    ,'screen_name'=>$screen_name]]]);
                    if(is_array($userStatus)){
                                if(array_key_exists('error',$userStatus)){
                                               $this->error[] = $userStatus['error']; 
                                }else if(array_key_exists('wizard',$userStatus)){
                                        //User Not Submit The Wizard Redirect It To Wizard (Create Profile).
                                        $this->openSessionsToUser($userStatus['id'],$tw_id,$oauth_token,$oauth_token_secret,$screen_name,true);
                                        $this->redirectToWizard();              
                                }
                    }else if(is_object($userStatus)){
                                        //User Exists And Submit Wizard.
                                        $this->logUserIn($userStatus,$oauth_token,$oauth_token_secret);
                    }
            }


            private function logUserIn($userModel,string $oauth_token,string $oauth_token_secret){
                    /**
                     * 1- Open Sessions To User. --Done.
                     * 2- Redirect User To seshatReader. --Done.
                     * 
                     * */  
                    $this->openSessionsToUser($userModel->getProperty('id'),$userModel->getProperty('tw_id'),
                    $oauth_token,$oauth_token_secret,$userModel->getProperty('screen_name')); 
                    $this->rIn("tw_id","seshatTimeline");
            }

           
            /**
             * @method openSessionToUser Open The Important Session That App Need.
             */
            private function openSessionsToUser(int $id,string $tw_id,string $oauth_token,string $oauth_token_secret,string $screen_name,bool $wizard = false){
                    if($wizard === true){
                                $this->session->setSession('wizard',$wizard);
                    }
                    $this->session->setSession('id',$id);
                    $this->session->setSession('tw_id',$tw_id);
                    $this->session->setSession('userAgent',$_SERVER['HTTP_USER_AGENT']);
                    $this->saveToken($oauth_token,$oauth_token_secret); 
                    $this->session->setSession('username',$screen_name);
                    $this->session->newId();
                    $this->session->saveAndCloseSession();     
            }


        }