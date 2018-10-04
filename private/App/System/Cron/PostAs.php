<?php
    namespace App\System\Cron;

    /**
     * this class handle the logic of post as || tweet as.
     */
    Class PostAs extends AbstractCron
    {
        protected $scope   = true;

        protected $EPD     = 6;

        protected $cron_id = 2;

        protected $perTime = 1;

        /**
         * post content to post in specific media.
         * @property post_content.
         */
        protected $post_content = null;

        /**
         * set post content.
         */
        protected function setPost_content( string $post_content ){
            $this->post_content = $post_content;
        }

        protected function logic(){
            if ( is_null( $this->post_content ) === false || empty( $this->owner )){
                //publish logic here.
                $post = $this->media->do('publishNewTweet',array_merge($this->data,['tweetContent'=>$this->post_content,'user_id'=>$this->owner]));
                if ( is_array($post) && array_key_exists('error' , $post) ){
                    $error = $post;
                }
            }else{
                throw new CronException("Can not do post as cron without post data (owner_id , text post ( post content )). @class" . get_class( $this ));
            }
            return $error ?? ['success'=>true];
        }
    }