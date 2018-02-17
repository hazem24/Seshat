<?php
        namespace App\Commands;
        use App\Model\App\UserModel;
        use Framework\Shared\AbstractCommand;


        /**
        *This Class Have All Commands That Interact with User Model.
        */

        Class UserCommand extends AbstractCommand
        {
            private $userModel;

            public function execute(array $data = []){
                   $method = $data['Method']['name']; 
                   if(method_exists($this,$method)){
                            return call_user_func_array(array($this,"$method") , array($data['Method']['parameters']));
                   } 
            }


            private function checkUserStatus(array $parameters){
                $tw_id = $parameters['tw_id'];
                $userExists = UserModel::userExists($tw_id);
                if($userExists === false){
                        //Insert New User (StepOne).
                        $screen_name = $parameters['screen_name'];
                        $saveNewUser =  $this->stepOne($tw_id,$screen_name);
                        if(is_array($saveNewUser) && array_key_exists('success',$saveNewUser)){
                                $return = ['wizard'=>true,'id'=>$saveNewUser['id']];
                        }else{
                                $return = ['error'=>GLOBAL_ERROR];
                        }     
                }else{
                    /**
                     * User Exists !.
                     * @return object of UserModel In Success.
                     * @return false In Error/
                     * */
                    $userData = $this->getUserData($tw_id);
                    if($userData === false){
                            $return = ['error'=>GLOBAL_ERROR];
                    }else{
                            //Check If User Not Submit Wizard.
                            if($userData->isWizard() === true){
                                $return = ['wizard'=>true,'id'=>$userData->getProperty('id')];
                            }else{
                                 $return = $userData;
                            }

                    }
                }
                return $return;
            }
            /**
             *@method stepOne Step One When New User Register Into App.
             */
            private function stepOne(string $tw_id,string $screen_name){
                    $this->userModel = new UserModel;
                    $this->userModel->setProperty("tw_id",$tw_id);
                    $this->userModel->setProperty("screen_name",$screen_name);
                    return $this->userModel->stepOne();

            }

            /**
             * @method getUserData.
             * @return object of UserModel In Success.
             * @return false In Error.
             */
            private function getUserData(string $tw_id){
                    return UserModel::getUser($tw_id);
            }


            /**
             * @method createProfile Responsable For Create Profile For New User.
             * @return bool|array.
             */

            private function createProfile(array $parameters){
                    $createProfile =  UserModel::createProfile($parameters['id'],$parameters['name'],$parameters['email'],$parameters['user_describe'],$parameters['account_type']); 
                    return $createProfile;   
            } 



        }
