<?php
            namespace App\Model\App\Seshat;
            use Framework\Shared\Model;
            use App\DomainMapper\Seshat\TaskMapper;

            Class TaskModel extends Model
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

                /**
                * @method saveNewPublish 
                * @return bool.
                */
                public  function save():bool{
                        return self::getFinder()->save($this);        
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

