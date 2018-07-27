<?php
        namespace App\DomainHelper\Twitter;
        use Framework\Shared\CommandFactory;



        /**
         * Class Task Responsable For Create Helper Method For Tasks Before Send It To Command.
         */

         class Task extends Action
         {
            protected $tasks = [1=>SC,TWTAS,ARL,FANS,UNFOLLOW,INACTIVE_ACCOUNTS];
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
             * @method expectedFinished Resbonsable For Calcuate Time Which Seshat Will Be Finish The Task.
             * @return date.
             */
            private function expectedFinsihed(string $createdAt){

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
                        default:
                                # code...
                        break;
                }   
                return $logic; 
            }
         }
