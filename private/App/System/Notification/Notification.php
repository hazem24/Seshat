<?php
    namespace App\System\Notification;
    use Framework\ConstructorClass as Base;
    use App\Model\App\Seshat\NotificationModel;


    /**
     * This class is implemented by sub class notification like (Task Notify , Tree Notify).
     */
    Class Notification extends Base
    {
        protected $id;
        protected $type = null;//override in the subclasses.

        protected $status = 1;//status is 1 which is info by default.
        protected $statusWhiteList = ['info'=>1,'success'=>2,'fail'=>3,'warning'=>4];

        protected $msg;
        protected $is_read;
        protected $owner;//owner of the notfication.

        protected $model;//Model of this notify system.

        public function __construct( int $owner ){
            $this->model = new NotificationModel;
            if ($owner > 0){
                $this->owner = $owner;
            }else{
                throw new NotificationException("owner id can not be -ve value @" . get_class($this));
            }
        }

        /**
         * set id of notification.
         */
        public function setId( int $id ){
            $this->id = $id;   
        }

        /**
         * set status the notification.
         * @method setStatus.
         * @return void.
         */
        public function setStatus( int $status ){
            if ( array_search($status,$this->statusWhiteList) !== false ){
                $this->status = $status;
            }else {
                throw new NotificationException("Status id : ( " . $status . " ) not vaild you must enter one of the following ids : " . implode(",",$this->statusWhiteList)); 
            }
        }
  

        /**
         * This method set the notifyMsg.
         * @method setMsg.
         * @return void.
         */
        public function setMsg( string $msg ){
            if (!empty( $msg )){
                $this->msg = $msg;
            }else{
                throw new NotificationException("Notification message can not by empty @" . get_class($this));
            }
        }

        /**
         * get id.
         */
        public function getId(){
            return $this->id;
        }

        /**
         * get Msg.
         */
        public function getMsg(){
            return $this->msg;
        }
        
        /**
         * get type.
         */
        public function getType(){
            return $this->type;
        }

        /**
         * get status.
         */
        public function getStatus(){
            return $this->status;
        }

        /**
         * get owner.
         */
        public function getOwner(){
            return $this->owner;
        }

        /**
         * this method return all notification for specfic user.
         * @method getUserNotifications.
         * @return array.
         */
        final public function getUserNotifications(){
            return $this->model::getUserNotifications( $this->owner ); 
        }

        /**
         * This method return all unread notification for specific user.
         * @method unReadNotifications.
         * @return array.
         */
        final public function unReadNotifications (){
            return $this->model::unReadNotifications( $this->owner );
        }

        /**
         * This method return notification of specific type.
         * @method getNotifications.
         * @return array.
         */
        final public function getNotificationsByType(){
            return $this->model::getNotificationsByType( $this->type , $this->owner );
        }

        /**
         * This method push new notification for specific user.
         * @method push.
         * @return bool.
         */
        final public function push(){
            if (is_null( $this->type)  === false){
                return $this->model->push( $this );
            }else{
                throw new NotificationException("You can't push notification without determine the type of notification @" . get_class($this));
            }
        }
    }