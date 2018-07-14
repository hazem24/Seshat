<?php

        namespace App\Controller;
        use App\DomainHelper\ControllerHelper\TwitterActionHelper as Helper;


        /**
        *This Class Just Provide The Logic Of All Action User Send It To Do Some Action In Twitter Like Tweet,Follow,...
        */

        Class TwitterAction extends AppShared
        {
                   
            /**
             * @method composeTweetAction This Method Resonsable For All Logic Of Compose Tweet In Twitter.
             * @return json. 
             */
            final public function composeTweetAction(){
                $this->rule();
                //Helper Here.
                Helper::composeTweet($this);
            }
            /**
             * @method do Responsable for do action in twitter.
             * action type =>(retweet,like,relay,unlike,unretweet)
             * @return json.
             */
            public function doAction(){
                $this->rule(); 
                //Helper Here. 
                Helper::doHelper($this);  
            }

            /**
             * create a new relation betweet the {{ auth_user }} && {{ requested_user }} (follow-unfollow).
             * @method doRelation.
             */

             public function createRelationAction (){
                $this->rule();     
                //Helper Here.
                Helper::createRelation($this);
             } 
        }