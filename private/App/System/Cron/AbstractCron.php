<?php
    namespace App\System\Cron;
    use Framework\ConstructorClass as Base;
    use App\DomainHelper\Twitter\Action as Media;

    /**
     * this class is a base class for all cron system in the app.
     */
    Abstract Class AbstractCron extends Base 
    {
        /**
         * media in which cron must happen in.
         * @property media.
         */
        protected $media;
        /**
         * this cron in user scope or App scope.
         * @property scope.
         */
        protected $scope = false;

        /**
         * type of cron (1=>schedule,2=>tweet_as or post_as , 31=>non followers,32=>fans ,33=>recentFollowers).
         * @property cron_id.
         */
        protected $cron_id;

        /**
         * number of Execution this cron per day.
         * @property EPD.
         */
        protected $EPD;

        /**
         * data which cron need to created.
         * @property data.
         */
        protected $data = [];

        /**
         * how many time seshat can do per request.
         * @property perTime.
         */
        protected $perTime;

        /**
         * owner of this cron.
         * @property owner.
         */
        protected $owner;

        /**
         * order requested.
         * @property order.
         */
        protected $order;

        public function __construct( Media $media ){
            $this->media = $media;
        }

        /**
         * set data of cron.
         * @method setData.
         * @return void.
         */
        public function setData( string $data ){
            $this->data = $this->dataConverter( $data );
        }

        /**
         * get data of cron.
         * @method setData.
         * @return array.
         */
        public function getData(){
            return $this->data;
        }

         /**
         * get perTime of cron.
         * @method getData.
         * @return int.
         */
        public function getPerTime(){
            return $this->perTime;
        }

        /**
         * get order which set by loopCalculation.
         * @method getOrder.
         * @return int.
         */
        public function getOrder(){
            return $this->order;
        }


        /**
         * set owner.
         * @method owner.
         * @return void.
         */
        public function setOwner( int $owner ){
            $this->owner = $owner;
        }

        /**
         * create a cron.
         * @method doCron.
         * @return
         */
        public function doCron(){
            if (empty($this->data)){
                throw new CronException("Cron need a data in order to be executed , data can't be empty @ class " . get_class($this));
            }
            return $this->logic();
        }


        /**
         * set scope of cron.
         * @method setScope.
         * @return void.
         */
        final public function setScope( bool $scope ):void{
            $this->scope = $scope;
        }

        /**
         * logic of specific cron.
         * @method logic.
         * @return array.
         */
        Abstract protected function logic();

        /**
         * this convert data from json to assoc array.
         * @method dataConverter.
         * @return array.
         */
        protected function dataConverter( $data ){
            return json_decode( $data  , true );
        }

        /**
         * return the number in which loop must happen.
         * @method loopCalculation.
         * @return int.
         */
        protected function loopCalculation( int $order ){
            $this->order = ($this->perTime >= $order) ? $order : $this->perTime;
            return $this->order;
        }

    }