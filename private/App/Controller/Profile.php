<?php
    namespace App\Controller;
    use Framework\Shared\Controller;
    use App\DomainHelper\ControllerHelper\ProfileHelper;

    /**
    *This class Handle logic of profile page of specific social media for now (twitter).
    */

    Class Profile extends AppShared
    {
        /**
         * Get Profile Page For Specific userName.
         * @method twitterAction.
         * @param $param[0] => UserName.
         * @return void. 
         */
        public function twitterAction (array $params = []){
            $this->rule();
            ProfileHelper::renderProfileView( $this , $params );
        }
        /**
         * get profile data for specific user.
         */
        public function getTwitterProfileAction(array $params = []){
            $this->rule();
            profileHelper::getProfileDataForTwitter( $this , $params );
        }

        /**
         * calc. fake accounts that following specific user {{ screen_name }}.
         */
        public function fakeAccountsAction (array $params = []) {
            $this->rule();
            ProfileHelper::fakeAccountsCalcualtion( $this , $params );             
        }

        /**
         * tweetAs Feature.
         */
        public function tweetAsAction( array $param = [] ){
            $this->rule();
            ProfileHelper::tweetAsTask( $this );
        }

    }