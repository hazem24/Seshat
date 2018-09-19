<?php 

    namespace App\DomainMapper\Seshat;
    use Framework\Lib\DataBase\DataMapper\AbstractDataMapper;
    use Framework\Registry as Registry;
    use Framework\Shared\Model;
    use Framework\Lib\DataBase\Query\QueryBuilder\SelectQueryBuilder;
    use Framework\Lib\DataBase\Query\QueryBuilder\InsertQueryBuilder;
    use Framework\Lib\DataBase\Query\QueryBuilder\DeleteQueryBuilder as Delete;
    use App\Model\App\Seshat\TaskModel;
    use Framework\Lib\DataBase\DataMapper\Collections\AbstractGeneratorCollection as Collection;

 
        /**
        *This Class Is Provide Access Of App To SeshatPublish Data In DataBase. 
        */
        Class TaskMapper extends AbstractDataMapper
        {
            private $table        = 'tasks';
            private $foreign_key  = 'user_id';
            private $primaryKey   = 'id';
            private $columnsTable = ['task_id','details','expected_finish','is_finished','progress','task_name','status'];
            private $modelName    = 'TaskModel';
            
            public function save(Model $taskModel){
                return $this->doSave($taskModel); 
            }
            
            public function scheduleExists($user_id,$date){
                   $selectBuilder = new SelectQueryBuilder;
                   $stm = $selectBuilder->select()->from($this->table)->
                   where([$this->foreign_key ."=? && "=>$user_id,$this->columnsTable[2]."=?"=>$date])->createQuery();
                   $schedule_exists = $this->pdo->prepare($stm['query']);
                   $this->bindParamCreator(2,$schedule_exists,$stm['data']);
                   $schedule_exists->execute();
                   $schedule_exists = $schedule_exists->fetch();
                   if($schedule_exists !== false){
                        return true; //Exists.
                   }
                   return false;//Not Exists.
            }

            public function getTweetAsInfo ( int $user_id ){
                $selectBuilder = new SelectQueryBuilder;
                $stm           = $selectBuilder->select()->from($this->table)->where([ $this->foreign_key . ' = ? && '=>$user_id ,
                $this->columnsTable[0] . '  = ?' => 2])->createQuery();
                $tweetAsInfo = $this->pdo->prepare($stm['query']);
                $this->bindParamCreator(2,$tweetAsInfo,$stm['data']);
                $tweetAsInfo->execute();
                $tweetAsInfo = $tweetAsInfo->fetchAll( \PDO::FETCH_CLASS, 'App\\Model\\App\\Seshat\\TaskModel' );
                return $tweetAsInfo; // array || false in failure.
            }

            public function controlFollowersTaskExists ( int $user_id , int $task_id) {
                $selectBuilder = new SelectQueryBuilder;
                $stm           = $selectBuilder->select()->from($this->table)->where([$this->columnsTable[0] . ' = ? && ' => $task_id
                , $this->foreign_key . ' = ? && '=>$user_id , $this->columnsTable[3] . ' <> ?'=> true])->createQuery();
                $exists = $this->pdo->prepare( $stm['query'] );
                $this->bindParamCreator( 3 , $exists , $stm['data'] );
                $exists->execute();
                $exists = $exists->fetch();
                if($exists !== false){
                    return true; //Exists.
                }
                    return false;//Not Exists.
            }
            /**
             * this method return last 50 tasks.
             */
            public function showTasks( int $user_id ){
                $select    = new SelectQueryBuilder;
                $select    = $select->select()->from( $this->table )->limit(50)->orderBy([$this->primaryKey=>"DESC"])
                ->where([$this->foreign_key . ' = ? '=>$user_id])->createQuery();
                $getTasks  = $this->pdo->prepare( $select['query'] );
                $this->bindParamCreator( 1 , $getTasks , [$user_id] );
                $getTasks->execute();
                $getTasks = $getTasks->fetchAll(\PDO::FETCH_ASSOC);
                return $getTasks;
            }

            /**
             * this method used to delete specific task.
             */
            public function deleteTask( int $user_id , int $task_id ){
                $delete = new Delete;
                $delete = $delete->delete( $this->table , $this->columnsTable )->where([ $this->primaryKey . " = ? && "=> $task_id , 
                $this->foreign_key . " = ? " => $user_id])->createQuery();
                $deleteTask = $this->pdo->prepare( $delete['query'] );
                $this->bindParamCreator( 2 , $deleteTask , [$task_id,$user_id] );
                $deleteTask->execute();
                return (bool) $deleteTask->rowCount();
            }
            protected function doSave(Model $model){
                    if(is_null($model->getProperty('id'))){
                        //Insert New Publish Model.
                        $insertBuilder = new InsertQueryBuilder;
                        $stm = $insertBuilder->insert($this->table,[$this->foreign_key=>$model->getProperty('user_id'),
                        $this->columnsTable[0]=>$model->getProperty('task_id'),$this->columnsTable[1]=>$model->getProperty('details'),
                        $this->columnsTable[2]=>$model->getProperty('expected_finish'),
                        $this->columnsTable[5]=>$model->getProperty('task_name')])->createQuery();
                        $newTask  = $this->pdo->prepare($stm['query']);
                        $this->bindParamCreator(5,$newTask,$stm['data']);
                        $newTask->execute();
                        if($newTask->rowCount() > 0){
                            return true;  //Saved Succesfully.
                        }
                        return false; //Not Saved.
                    }     

            }
            protected function createObject(array $fields):Model{
            }
            protected function getCollection(array $raw): Collection{

            }
            protected function selectAllStatement():\PDOStatement{

            }
 




        }
