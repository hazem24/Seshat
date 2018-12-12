<?php
        namespace App\Controller;
        use Framework\Shared;
        use App\DomainHelper\ControllerHelper\SeshatHelper;

        /**
         * Seshat Class Provide All Action And Method To Interact With Seshat Algorthim.
         */
        Class Seshat extends AppShared
        {

              public function defaultAction( array $params = [] ){
                $this->rule();
                //renderView.
                $this->renderLayout("HeaderApp");     
                $this->render();
                $this->renderLayout("FooterApp");
            }

            public function createProfileAction(){
                $this->rule();
                //createProfileAction
                SeshatHelper::createProfileAction( $this );
            }
            /**
             * this method have most of user account in seshat.
             */
            public function accountAction(){
              $this->rule();
              SeshatHelper::account( $this );
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
                $this->detectLang();
                SeshatHelper::getReportHelper($this,$params);   
              }

              /**
               * get user statics.
               */
              public function statisticsAction(){
                $this->rule();
                SeshatHelper::statistics( $this );
              }

              /**
               * get account activity.
               */
              public function activityAction(){
                $this->rule();
                SeshatHelper::accountActivity( $this );
              }

              /**
               * get account tasks
               */
              public function tasksAction(){
                $this->rule();
                SeshatHelper::tasks( $this );
              }

              /**
               * used to delete task from seshat.
               */
              public function deleteTaskAction(){
                $this->rule();
                SeshatHelper::deleteTask( $this );
              }

              //search feature. 
              public function searchAction ( array $params = [] ){
                $this->rule();
                SeshatHelper::searchHelper( $this , $params );
              }

              public function controlFollowersAction ( array $params = [] ){
                $this->rule();
                SeshatHelper::controlFollowers( $this , $params );
              }

              public function checkFriendsAction ( array $params = [] ){
                $this->rule();
                SeshatHelper::checkFriends( $this , $params );
              }
              
              public function controlFollowersTaskAction ( array $params = [] ){
                $this->rule();
                SeshatHelper::controlFollowersTask( $this );
              }
        }