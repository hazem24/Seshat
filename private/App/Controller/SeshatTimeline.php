<?php
        namespace App\Controller;
        use Framework\Shared;
        use Framework\Request\RequestHandler;
        use Framework\Lib\Security\Data\FilterDataFactory;
        use Framework\Error\WebViewError;
        use App\DomainHelper\Twitter;
        use App\DomainHelper\FrontEndHelper;
         
        
        
        



        /**
         * SeshatTimeLine Class Which Resposanble To Read From Twitter.
         */
        Class SeshatTimeline extends AppShared
        {
            /**
            *@method defaultAction.
            */    
            public function defaultAction(){
                //$_SESSION = [];
                $this->detectLang();
                $this->rOut("tw_id","index/signin");
                $this->redirectToWizard();
                $this->renderLayout("HeaderApp");  
                $timeLineData  = $this->getUserTimeLine();  
                //Return Reponse To User.
                if($this->anyAppError() === false){
                        $toView = $timeLineData;          
                }else{
                        $user_need_reauth = $this->reauthUser($this->error);
                        $toView = ['error'=>($user_need_reauth === false) ? FrontEndHelper::notify($this->error): $user_need_reauth];
                }    
                $this->actionView->setDataInView((array_key_exists('error',$toView) === false)?["userTimeLine"=>$toView]:[]);     
                $this->render();
                $this->renderLayout("FooterApp",(array_key_exists('error',$toView) === true)?['error'=>$toView]:[]);
            }

            private function getUserTimeLine(){
                $user_id = $this->session->getSession('id');    
                //Save or get Data In Cache.
                $this->fastCache();//init cache if not exists.
                $userTimeLine_cache = $this->cache->getItem("userTimeLine".$user_id);//put user_id to be unique when get cache data.
                if(is_null($this->cache->get($userTimeLine_cache)) === true){
                        //Read Data From Twitter First.
                        $readTimeLine = new Twitter\Read();
                        $timeLine = $readTimeLine->readTimeLine(["oauth_token"=>$this->session->getSession("oauth_token"),
                        "oauth_token_secret"=>$this->session->getSession("oauth_token_secret")]);       
                        //error exists.
                        if(is_array($timeLine) && array_key_exists("error",$timeLine)){
                                $this->error[] = $timeLine['error'];
                        }else{
                                //set Cache For Timeline.
                                $this->cache->set($userTimeLine_cache,$timeLine,960);
                                return $this->cache->get($userTimeLine_cache);
                        }
                                
                }else{
                        //Get Data Directly From Cache,(Read From Cache).
                        return $this->cache->get($userTimeLine_cache);
                }
            }
        }