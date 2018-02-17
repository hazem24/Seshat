<?php
        namespace App\Controller;
        use Framework\Shared;
        use Framework\Request\RequestHandler;
        use Framework\Lib\Security\Data\FilterDataFactory;
        use Framework\Error\WebViewError;
        use Framework\Lib\Security\Forms\CsrfProtection;
        use App\DomainHelper\Helper;
        
        /**
         * Seshat Class Provide All Action And Method To Interact With Seshat Algorthim.
         */
        Class Seshat extends AppShared
        {

            public function createProfileAction(){
                   $this->rOut("tw_id","index/signin");
                   $isWizard = (bool)$this->session->getSession('wizard');
                   if($isWizard === true){
                                /**
                                 *   'firstname' => string 'hazem' (length=5)
                                 *   'email' => string 'gotrendtoday@gmail.com' (length=22)
                                 *   'account_type' => string '6' (length=1)
                                 *   'account_describe' => string 'Please Enter Some Me !' (length=22)
                                 *   'finish' => string 'Finish' (length=6)
                                 *   'formToken' => string 'db67c26dcc53014e4dadc7316d731aab' (length=32)
                                 */
                                $this->detectLang();
                                if(RequestHandler::postRequest()){
                                        //Check If It Comes From The Form.
                                        $formToken = (string)RequestHandler::post('formToken');
                                        $user_submit_form = (bool)CsrfProtection::sessionTokenValidation($formToken);
                                        if($user_submit_form === true){
                                                $name = RequestHandler::post('firstname');
                                                $email = RequestHandler::post('email');
                                                $account_type = RequestHandler::post('account_type');
                                                $account_describe = RequestHandler::post('account_describe');
                                                $filter = $this->filterWizardForm($name,$email,$account_type,$account_describe);
                                                if($this->anyAppError() === true){
                                                           $return = ['error'=>$this->error];
                                                }else{
                                                       $this->createProfile($filter); 
                                                       if($this->anyAppError() === true){
                                                            $return = ['error'=>$this->error];       
                                                       }else{
                                                            $return = ['location'=>BASE_URL.LINK_SIGN.'seshatTimeline'];
                                                       }
                                                }
                                                
                                        }else{
                                                    //User Not Submit The Form.
                                                    $return = ['error'=>BOT_ACCESS];
                                        }

                                        echo json_encode($return);
                                        exit;
                                }
                                //Email , And Name , And Image Must Be Taken From Twitter And Send To View Here. --Done.
                                $userCredients = $this->verfiyCredentials();
                                if($this->anyAppError() === true){
                                        $user_need_reauth = $this->reauthUser($this->error);
                                        $send_to_view = ['error'=>($user_need_reauth === false) ? $this->error : $user_need_reauth];
                                }else{
                                        $send_to_view = (object)['data'=>$userCredients];
                                }
                                $this->actionView->setDataInView(["user"=>$send_to_view]);
                                $this->render();
                   }else{
                            $this->rIn("tw_id","seshatTimeline"); 
                   } 
            }


            private function filterWizardForm(string $name,string $email,int $account_type,string $account_describe){
                    $data = [YOUR_NAME=>['value'=>$name , 'type'=>'string','min'=>3 , 'max'=>'100'],
                             EMAIL=>['value'=>$email , 'type'=>'email','min'=>3 , 'max'=>'400'],
                             DESCRIBE_YOUR_ACCOUNT=>['value'=>$account_describe , 'type'=>'string','min'=>20 , 'max'=>400]
                            ];
                    $filter = new FilterDataFactory($data);
                    $filter = $filter->getSecurityData();
                    $account_type = Helper::accountType($account_type);
                    $anyError = WebViewError::anyError('form' , $data , $filter);
                    if(is_array($anyError)){
                        //There Is And Error.
                        $this->error[] = WebViewError::userErrorMsg($anyError);
                    }else{
                        $filter['account_type'] = array_keys($account_type)[0];
                        return $filter;//Return Back Filter Data.
                    }
            }



           private function createProfile(array $data){
                   $name = $data[YOUR_NAME];  $email = $data[EMAIL]; $account_describe = $data[DESCRIBE_YOUR_ACCOUNT]; $account_type = $data['account_type'];
                   $cmd = Shared\CommandFactory::getCommand('user');
                   $createProfile = $cmd->execute(['Method'=>['name'=>'createProfile','parameters'=>['id'=>(int)$this->session->getSession('id'),'name'=>$name
                   ,'email'=>$email,'user_describe'=>$account_describe,'account_type'=>$account_type]]]);
                   if($createProfile === true){
                                /**
                                 * 1-Wizard Session To False. --Done.
                                 * 2-Redirect User To SeshatTimeLine. --Done.
                                 * 3- Welcome Msg To User For First Time Only.
                                 */
                                $this->session->setSession('wizard',false);
                   }elseif($createProfile === false){
                                //Error Happen At Inserting Data To DataBase Try Again Later.
                                $this->error[] = GLOBAL_ERROR;
                   }else if(array_key_exists('emailExists',$createProfile)){
                                //Email Exists.
                                $this->error[] = EMAIL_EXISTS;
                   } 
           }
           /**
            *@method verfiyCredentials Get User Email-Name-Image For Wizard Proccess.
            *@return array. 
            */
           private function verfiyCredentials(){
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
        }