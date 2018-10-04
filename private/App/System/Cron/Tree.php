<?php
    namespace App\System\Cron;

    /**
     * this handle logic of tree cron.
     */

    class Tree extends AbstractCron
    {

        protected $scope   = true;

        protected $EPD     = 144;//Every 10 min.

        protected $cron_id = 54;

        protected $perTime = 2;

        protected $list    = [];

        protected function setList( $list ){
            $this->list = $list;
        }
        
        protected function logic(){
            if (isset($this->list['source']) && isset($this->list['target']) && isset($this->list['source_id']) && isset($this->list['target_id'])){
                // do follow here.
                $follow_process = $this->media->do("writeToTwitter",array_merge(['type'=>'follow','scope'=>'User',
                'parameters'=>$this->list['target_id']] , $this->data));
                if ( is_array( $follow_process ) && array_key_exists('error',$follow_process) ){
                    $error = $follow_process;
                }
            }
            return $error ?? ['success'=>true];
        }

    }