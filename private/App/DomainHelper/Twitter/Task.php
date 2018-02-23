<?php
        namespace App\DomainHelper\Twitter;
        use Framework\Shared\CommandFactory;



        /**
         * Class Task Responsable For Create Helper Method For Tasks Before Send It To Command.
         */

         class Task extends Action
         {
            protected $tasks = [1=>SC,ARL,FANS,UNFOLLOW,INACTIVE_ACCOUNTS];
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
            protected function newTask(array $paramater){
                    if(array_key_exists((int)$paramater['task_id'],$this->tasks)){
                            //Create Logic Of Add New Task.
                            if((int)$paramater["task_id"] == 1){
                                    $paramater['task_name'] = $this->taskName($paramater['tweetContent']);
                                    $expectedFinish = $paramater['expected_finish'];
                            }else{
                                        //logic For Expected Finish Seshat Will Finish The Tasks For All Type Of Tasks Except Scheule.
                            }
                            return $this->command->execute(['Method'=>['name'=>'saveTask','parameters'=>$paramater]]);
                    }
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
            private function taskName(string $tweetContent){
                    $tweetLength = strlen($tweetContent);
                    if($tweetLength > 10){
                            $taskName = substr($tweetContent,0,9)." ...";
                    }else{
                            $taskName = $tweetContent;
                    }
                    return $taskName;
            }
         }
