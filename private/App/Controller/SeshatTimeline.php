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
            public function defaultAction( array $params = [] ){
                $this->rule();
                $data = $params[0] ?? false;
                if ( $data !== false && $data === 'getTimeLineDataAsJson') {
                        $response  = $this->getUserTimeLine();   
                        $this->commonError( $response );
                        $response = $this->returnResponseToUser( $response ?? null );
                        $this->encodeResponse( $response );
                }
                //renderView.
                $this->renderLayout("HeaderApp");     
                $this->render();
                $this->renderLayout("FooterApp");
            }

            private function getUserTimeLine(){
                $user_id = $this->session->getSession('id');    
                //Save or get Data In Cache.
                $this->fastCache();//init cache if not exists.
                $userTimeLine_cache = $this->cache->getItem("userTimeLine".$user_id);//put user_id to be unique when get cache data.
                if(is_null($this->cache->get($userTimeLine_cache)) === true){
                        //Read Data From Twitter First.
                        $readTimeLine = new Twitter\Read();
                        $timeLine = $readTimeLine->do('readTimeLine',["oauth_token"=>$this->session->getSession("oauth_token"),
                        "oauth_token_secret"=>$this->session->getSession("oauth_token_secret")]);       
                        //error exists.
        
                        if(is_array($timeLine) && array_key_exists("error",$timeLine)){
                                $this->error[] = $timeLine['error'];//store an error.
                                return;//an error founded exit this method.
                        }else{
                                //set Cache For Timeline.
                                $this->cache->set($userTimeLine_cache,$timeLine,960);
                        }      
                }
                return ['results'=>['from'=>'twitter','type'=>'posts','echo_at'=>'timeline','tweets'=>FrontEndHelper::tweetsStyle($this->cache->get($userTimeLine_cache))]];
            }
        }