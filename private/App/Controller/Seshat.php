<?php
        namespace App\Controller;
        use Framework\Shared;
        use Framework\Request\RequestHandler;
        use Framework\Lib\Security\Data\FilterDataFactory;
        use Framework\Error\WebViewError;
        use Framework\Lib\Security\Forms\CsrfProtection;
        use App\DomainHelper\Helper;
        use App\DomainHelper\FrontEndHelper;
        
        /**
         * Seshat Class Provide All Action And Method To Interact With Seshat Algorthim.
         */
        Class Seshat extends AppShared
        {

            public function createProfileAction(){
                   $this->rule();
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

            /**
             * @method analyticAction this method responsable for create an analytic for specific tweet id and user.
             * @return 
             */

             public function analyticAction(array $params = []){
                     $this->rule();
                     //Error Handler Api Or App Logic.
                     $screenName = (isset($params[0]) && !empty($params[0]))?(string) $params[0] : false;
                     $tweet_id   = (isset($params[1]) && !empty($params[1]))? (string) $params[1] : false;
                     
                     if($screenName !== false && $tweet_id !== false){
                                $getAnalytic = $this->seshatAnalyticData($screenName,$tweet_id);
                                
                     }else{
                                $this->error[] = CANNOT_UNDERSTAND;
                     }

                     //Check for any error in this request.
                     if($this->anyAppError() === true){
                                $user_need_reauth = $this->reauthUser($this->error);
                                $send_to_view = ['error'=>($user_need_reauth === false) ? FrontEndHelper::notify($this->error,'top','center'): $user_need_reauth];
                     }else{
                                $send_to_view = $getAnalytic;
                     }
                     
                     $haveError = (bool)(array_key_exists('error',$send_to_view));
                     $this->renderLayout("HeaderApp");
                     
                     if($haveError === true){
                                $this->renderLayout('Notfound');
                     }else{
                                $this->actionView->setDataInView(["analyticData"=>$send_to_view,'FrontHelperClass'=>new FrontEndHelper]);        
                                $this->render();
                     }

                     $this->renderLayout("FooterApp",($haveError === true)?['error'=>$send_to_view]:[]);
                     
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
            * @method seshatAnalyticData.
            */
           private function seshatAnalyticData(string $screenName,string $tweet_id){
                $tokens = $this->getTokens();   
                $cmd = Shared\CommandFactory::getCommand('seshat');
                $analyticData =  $cmd->execute(['Method'=>['name'=>"getAnalytic",'parameters'=>['screenName'=>$screenName,
                      'tweet_id'=>$tweet_id,
                      'oauth_token'=>$tokens['oauth_token'],'oauth_token_secret'=>$tokens['oauth_token_secret']]]]);
                if(is_array($analyticData) && is_object($analyticData['tweet']) === false && array_key_exists('error',$analyticData['tweet'])){
                        $this->error[] = $analyticData['tweet']['error'];
                } 
                return $analyticData;
           }
        }