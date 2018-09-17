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
                protected $is_finished;
                protected $progress;
                protected $task_name;
                protected $user_id;


                private static $tasksMapper = null;

                public function save(){
                        return self::getFinder()->save(  $this );
                }

                
                public static function scheduleExists($user_id,$date):bool{
                        return self::getFinder()->scheduleExists($user_id,$date);
                }

                /**
                * @method getTweetAsInfo. 
                */
                public function getTweetAsInfo ( int $user_id ){
                        return self::getFinder()->getTweetAsInfo( $user_id );
                }

                /**
                 * @method controlFollowersTaskExists.
                 * @return bool.
                 */
                public static function controlFollowersTaskExists( int $user_id , int $task_id ){
                        return self::getFinder()->controlFollowersTaskExists( $user_id , $task_id ); 
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

