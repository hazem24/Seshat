<?php
        namespace App\DomainHelper\Twitter;
        use Framework\Shared\CommandFactory;



        /**
         * Class Task Responsable For Create Helper Method For Tasks Before Send It To Command.
         */

         class Task extends Action
         {
            protected $tasks = [1=>SC,TWTAS,31=>UNFOLLOW,32=>FANS,33=>RECENT_FOLLOWERS];
            public function __construct(){
                $this->command = $this->initCommand();
            }

            protected function initCommand(){
                return CommandFactory::getCommand('task');
            }
            /**
             * @method newTask Add New Task.
             * @return
             */
            protected function addNewTask(array $paramater){
                $paramater['task_id'] = ($paramater['task_id']) ?? 0;    
                if(array_key_exists((int)$paramater['task_id'],$this->tasks)){
                        //Create Logic Of Add New Task.
                        if((int)$paramater["task_id"] == 1){
                                $paramater['task_name'] = $this->taskName($paramater['tweetContent']);
                                $expectedFinish = $paramater['expected_finish'];
                        }else{
                                //logic For Expected Finish Seshat Will Finish The Tasks For All Type Of Tasks Except Schedule.
                                $logic = $this->taskLogic( $paramater ,  $paramater['task_id']); 
                                $paramater = array_merge($paramater,$logic);
                        }
                        return $this->command->execute(['Method'=>['name'=>'saveTask','parameters'=>$paramater]]);
                }
                return ['error'=>[CANNOT_DO_TASK]];
            }

            /**
             * this method used to show tasks.
             * @method showTasks.
             * @return array | false in failure.
             */
            protected function showTasks(array $paramater){
                return  $this->command->execute(['Method'=>['name'=>'showTasks','parameters'=>$paramater]]);
            }
            /**
             * this method used to delete a task.
             * @method deleteTask.
             * @return bool.
             */
            protected function deleteTask( array $params ){
                return $this->command->execute(['Method'=>['name'=>'deleteTask','parameters'=>$params]]);
            }
            /**
             * @method expectedFinished Resbonsable For Calcuate Time Which Seshat Will Be Finish The Task.
             * @property ratioPerDay ratio of task refer to day example {{ seshat can do 720 follow || unfollow per day so ratioPerDay is x/720}}
             * simple equation 24 hr => {{ what seshat can do. }}
             *                 x hr  => {{ order. }}
             * @return date.
             */
            private function expectedFinished(float $ratioPerDay){
                return date('Y-m-d H' , strtotime("+" . ceil((24 * ($ratioPerDay))) . " hours"));//hours.  
            }
            /**
             * @method taskName Create Task Name For Schedule Task From Tweet Content Itself.
             * @return string.
             */
            private function taskName(string $content , bool $full_content_as_name = false){
                    $tweetLength = mb_strlen($content);
                    if($tweetLength > 10 && $full_content_as_name === false){
                            $taskName = substr($content,0,9) . '...';
                    }else{
                            $taskName = $content;
                    }
                    return $taskName;
            }

            /**
             * This method handle the logic for each type of task and return the {{ data }} needed to be use in task command except {{ SC_task }}.
             * @method taskLogic.
             * @return array. 
             */
            private function taskLogic ( array $paramaters , int $task_id ) {
                switch ($task_id) {
                        case 2: //tweetAs Task.
                        $logic = ['task_name'=>$this->taskName( $this->tasks[$task_id] . ' ' . $paramaters['screen_name'] , true ),
                        'expected_finish'=>date('Y-m-d')];         
                        break;
                        case 31 || 32 || 33:
                        $counter = ($parameters['order']) ?? 50;
                        $logic = ['task_name'=>$this->taskName( AUTOMATIC . ' ' . $this->tasks[$task_id] , true ) , 
                        'expected_finish'=>$this->expectedFinished($counter/720)];
                        break; 
                        default:
                                # code...
                        break;
                }   
                return $logic; 
            }
         }
