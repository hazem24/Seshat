<?php
        namespace App\Controller;
        use Framework\Shared;
        use App\DomainHelper\ControllerHelper\SeshatHelper;

        /**
         * Seshat Class Provide All Action And Method To Interact With Seshat Algorthim.
         */
        Class Seshat extends AppShared
        {

            public function createProfileAction(){
                $this->rule();
                //createProfileAction
                SeshatHelper::createProfileAction( $this );
            }

            /**
             * @method analyticAction this method responsable for create an analytic for specific tweet id and user.
             * @return 
             */

             public function analyticAction(array $params = []){
                $this->rule();
                //analyticActionHelper
                SeshatHelper::analyticActionHelper( $this , $params );
             }


             /**
              * @method createReportAction this method responsable for creating new report (hashtag track , account comparsion).
              */

              public function createReportAction(array $params = []){
                $this->rule();
                SeshatHelper::createReportHelper($this,$params);
              }

              /**
               * can be reach by Logged user or unLogged User.
               * @method getReportAction.
               */
              public function getReportAction(array $params = []){
                $this->detectLang();
                SeshatHelper::renderReportView($this,(isset($params[0]) ? $params[0] : 'notFound'));
              }

              /**
               * report data of specific report.
               * @method 
               * @return json.
               */
              public function reportDataAction(array $params = []){
                SeshatHelper::getReportHelper($this,$params);   
              }

              //search feature. 
              public function searchAction ( array $params = [] ){
                $this->rule();
                SeshatHelper::searchHelper( $this , $params );
              }
        }