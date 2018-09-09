<?php
    namespace App\Controller;
    use Framework\Request\RequestHandler;
    use App\DomainHelper\ControllerHelper\FollowTreeHelper;


    /**
    *This Class responsable for all logic of follow tree feature in app.
    */

    Class FollowTree extends AppShared
    {
        /**
         * This action show trees which users created or subscribed.
         * @method defaultAction.
         */
        public function defaultAction (){
            $this->rule();
            FollowTreeHelper::default( $this );
        }
        /**
         * this action show specific tree provided by name.
         * @method showAction.
         */
        public function showAction(){
            $this->detectLang();
            FollowTreeHelper::show( $this );
        }
        /**
         * this action responsable to delete a tree which created by user.
         * @method deleteAction.
         */
        public function deleteAction(){
            $this->rule();
            FollowTreeHelper::deleteTree( $this );
        }
        /**
         * this action responsable for exit from specific tree.
         * @method exitAction.
         */
        public function exitAction(){
            $this->rule();
            FollowTreeHelper::exitTree( $this );
        }

        /**
         * this action edit specific tree.
         * @method editAction.
         */
        public function editAction(){
            $this->rule();
            FollowTreeHelper::editTree( $this );
        }

        /**
         * this action createNewTree.
         * @method createNewTreeAction.
         */
        public function createNewTreeAction(){
            $this->rule();
            FollowTreeHelper::createNewTree( $this );
        }

        /**
         * this action join user to specific tree.
         * @method joinAction.
         */
        public function joinAction(){
            $this->detectLang();
            FollowTreeHelper::join( $this );
        }

        /**
         * this action show all trees in seshat.
         * @method showAllAction.
         */

        public function showAllAction(){
            $this->detectLang();
            FollowTreeHelper::showAll( $this );
        }

    }