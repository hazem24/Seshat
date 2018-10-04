<?php
    namespace App\System\Cron;

    /**
     * this class handle logic of schedule posts.
     */

     Class Schedule extends AbstractCron 
     {
        /**
         * Execute Every 1 min for 24 hr so 1440 time.
         * */ 
        protected $EPD     = 1440;

        protected $cron_id = 1; 

        protected $perTime = 70;//can create 70 post per one request.

        protected function logic (){
            $this->data = array_merge($this->data,['user_id'=>$this->owner]);
            $post =  $this->media->do("publishNewTweet", $this->data );
            if (is_array($post) && array_key_exists('error',$post)){
                $error = $post;
            }
            return ($error) ?? ['success'=>true];
        }
     }