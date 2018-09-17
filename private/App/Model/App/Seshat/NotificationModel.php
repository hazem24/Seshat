<?php
    namespace App\Model\App\Seshat;
    use App\Model\BaseModel;
    use App\DomainMapper\Seshat\NotificationMapper;
    use App\System\Notification\Notification;
    /**
     * This class is a model class for notification system.
     */
    Class NotificationModel extends BaseModel
    {
        
        protected $id = null;
        protected $type;
        protected $status;    
        protected $notify_msg;
        protected $is_read;
        protected $owner;//owner of the notfication.

        protected static $notificationMapper = null;

        /**
         * set id.
         */
        public function setId( int $id ){
            $this->id = $id;
        }

        /**
         * set msg.
         */
        public function setMsg( string $msg ){
            $this->notify_msg = $msg;
        }

        /**
         * set type.
         */
        public function setType( int $type ){
            $this->type = $type;
        }

        /**
         * set msg.
         */
        public function setStatus( int $status ){
            $this->status = $status;
        }

        /**
         * set msg.
         */
        public function setOwner( int $owner ){
            $this->owner = $owner;
        }

        /**
         * get id.
         */
        public function getId(){
            return $this->id;
        }

        /**
         * get msg.
         */
        public function getNotify_msg(){
            return $this->notify_msg;
        }

        /**
         * get type.
         */
        public function getType(){
            return $this->type;
        }

        /**
         * get msg.
         */
        public function getStatus(){
            return $this->status;
        }

        /**
         * get msg.
         */
        public function getOwner(){
            return $this->owner;
        }

        /**
         * this method return all unRead notification for specific user.
         * @method unReadNotifications.
         * @return array.
         */
        public static function unReadNotifications( int $owner ){
            return self::getFinder()->unReadNotifications( $owner );
        }

        /**
         * this method return notification by type.
         * @method getNotificationsByType.
         * @return array.
         */
        public static function getNotificationsByType( int $type , int $owner ){
            return self::getFinder()->getNotificationsByType( $type , $owner );
        }

        /**
         * this method return all notifications for specific users read and unread.
         * @method getUserNotifications.
         * @return array.
         */
        public static function getUserNotifications( int $owner ){
            return self::getFinder()->getUserNotifications( $owner );
        }

        /**
         * this method save new notification for specific User.
         * @method push.
         * @return void.
         */
        public function push( Notification $notification ){
            $this->owner      = $notification->owner;
            $this->type       = $notification->type;
            $this->status     = $notification->status;
            $this->notify_msg = $notification->msg;
            self::getFinder()->save( $this );
        }
        
        public function save(){
            //nothing here.
        }

        /**
        * @method getFinder Find The Mapper Which Object Related To It.
        * @return Mapper.
        */
        protected static function getFinder(){
            if(is_null(self::$notificationMapper) === true){
                self::$notificationMapper = new NotificationMapper;
            }
            return self::$notificationMapper;
        }

    }
 