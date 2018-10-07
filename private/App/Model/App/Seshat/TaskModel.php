<?php
            namespace App\Model\App\Seshat;
            use App\Model\BaseModel;
            use App\DomainMapper\Seshat\TaskMapper;

            Class TaskModel extends BaseModel
            {
                /**
                 * Table Of Content Of Tasks.
                 */
                protected $id;
                /**
                 * @property  task_id type of task.
                 */
                protected $task_id;
                protected $details;
                protected $expected_finish;
                protected $is_finished = 0;
                protected $progress = 0;
                protected $task_name;
                protected $user_id;
                protected $status;

                private static $tasksMapper = null;

                public function setId( int $id ){
                  $this->id = $id;
                }

                public function setProgress( int $progress ){
                  $this->progress = $progress;
                }

                public function setIs_finished( int $is_finished ){
                  $this->is_finished = $is_finished;
                }

                public function setStatus( int $status ){
                  $this->status = $status;
                }

                public function setUser_id( int $user_id ){
                   $this->user_id = $user_id;
                }

                public function setTask_id( int $task_id ){
                   $this->task_id = $task_id;
                }

                public function getTask_id(){
                   return $this->task_id;
                }
                public function getId(){
                   return $this->id;
                }
      
                public function getProgress(){
                   return $this->progress;
                }
      
                public function getIs_finished(){
                   return $this->is_finished;
                }
      
                public function getStatus(){
                   return  $this->status;
                }
      
                public function getUser_id(){
                   return $this->user_id;
                }

                /**
                 * this method return last 50 tasks user created.
                 */
                public static function showTasks( int $user_id ){
                     return self::getFinder()->showTasks($user_id);   
                }
                /**
                 * this method used to delete specific task for specific user.
                 */
                public static function deleteTask(int $user_id , int $task_id){
                  return self::getFinder()->deleteTask( $user_id , $task_id );    
                }
                public static function scheduleExists($user_id,$date):bool{
                  return self::getFinder()->scheduleExists($user_id,$date);
                }

                public function save(){
                  return self::getFinder()->save(  $this );
                }

                /**
                * @method getTweetAsInfo. 
                */
                public function getTweetAsInfo ( int $user_id ){
                  return self::getFinder()->getTweetAsInfo( $user_id );
                }

                /**
                 * this method uses to load schedule posts from DB.
                 * @method getPosts.
                 */
                public function getPosts(){
                  return self::getFinder()->getPosts();
                }

                /**
                 * this method load controlFollowers task from Db.
                 */
                public function getControlFollowersTask( string $media ){
                   return self::getFinder()->getControlFollowersTask( $this  , $media);
                }

                /**
                 * @method controlFollowersTaskExists.
                 * @return bool.
                 */
                public static function controlFollowersTaskExists( int $user_id , int $task_id ){
                        return self::getFinder()->controlFollowersTaskExists( $user_id , $task_id ); 
                }

                /**
                 * @method postAsInfo.
                 * @return array | false in failure.
                 */
                public static function postAsInfo( int $user_id , string $media){
                  return self::getFinder()->postAsInfo( $user_id , $media);
                }

                /**
                 * @method countScheduled.
                 * @return int.
                 */
                public static function countScheduled( int $user_id ){
                  return self::getFinder()->countScheduled( $user_id );
                }
                /**
                * @method getFinder Find The Mapper Which Object Related To It.
                * @return Mapper.
                */
                protected static function getFinder(){
                  if(is_null(self::$tasksMapper) === true){
                    self::$tasksMapper = new TaskMapper;
                  }
                  return self::$tasksMapper;
                }

                
            }

