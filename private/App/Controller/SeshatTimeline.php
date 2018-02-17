<?php
        namespace App\Controller;
        use Framework\Shared;
        use Framework\Request\RequestHandler;
        use Framework\Lib\Security\Data\FilterDataFactory;
        use Framework\Error\WebViewError;
        
        
        



        /**
         * Seshat Reader Class Which Resposanble To Read Data From Twitter.
         */
        Class SeshatTimeline extends AppShared
        {
            /**
            *@method defaultAction
            */    
            public function defaultAction(){
                    //$_SESSION = [];
                    $this->detectLang();
                    $this->rOut("tw_id","index/signin");
                    $this->redirectToWizard();
                    $this->renderLayout("HeaderApp");    
                    $this->render();
                    $this->renderLayout("FooterApp");
            }
        }