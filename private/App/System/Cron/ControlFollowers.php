<?php
    namespace App\System\Cron;

    /**
     * this class handle logic of control followers cron.
     */

     Class ControlFollowers extends AbstractCron 
     {
        /**
         * Execute Every 5 min in 24 hr so 144 time.
         * */ 
        protected $EPD     = 144;

        protected $perTime = 5;

        protected $scope   = true;

        /**
         * 31 => nonFollowers.
         * 32 => fans.
         * 33 => recentFollowers.
         */

        protected $cron_id; 
        protected $whiteListIds = [1=>31,32,33]; 
        
        /**
         * list of people in which the proccess must happen on it.
         */
        private $list = [];

        protected function setCron_id( int $cron_id ){
            $this->cron_id = $cron_id;
        }

        protected function setList( array $list ){
            $this->list = $list;
        }

        protected function getList(){
            return $this->list;
        }

        protected function logic (){
            if (array_search($this->cron_id , $this->whiteListIds) > 0){
                if (!empty( $this->list )){
                    switch ($this->cron_id) {
                        case 31:
                            $return = $this->process('unfollow');
                            break;
                        case 32:
                            $return = $this->process('follow');
                            break;
                        case 33:
                            $return = $this->process('follow');
                            break;    
                    } 
                    return $return;   
                }else{
                    throw new CronException("the list is can't be empty , @class " . get_class($this));
                }
            }
            throw new CronException("this cron id  (" . $this->cron_id . ") undefined , @class " . get_class($this));
        }

        private function process( string $type ){
            $looper = $this->loopCalculation( count( $this->list ) );
            for ($i = 0; $i < $looper ; $i++) { 
                if ( isset($this->list[$i]) ){
                    $process = $this->media->do('writeToTwitter',array_merge( $this->data , 
                    ['type'=>$type,'scope'=>'User','parameters'=>$this->list[$i]->id_str]));
                    if (is_array($process) && array_key_exists( 'error' , $process ) && $this->list[$i]->protected === false){
                        $error = $process;
                        break;
                    }
                }
            }
            return ($error ?? ['success'=>true]);
        }
     }