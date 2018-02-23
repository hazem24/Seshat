<?php 

    namespace App\DomainMapper\Seshat;
    use Framework\Lib\DataBase\DataMapper\AbstractDataMapper;
    use Framework\Registry as Registry;
    use Framework\Shared\Model;
    use Framework\Lib\DataBase\Query\QueryBuilder\SelectQueryBuilder;
    use Framework\Lib\DataBase\Query\QueryBuilder\InsertQueryBuilder;
    use App\Model\App\Seshat\TaskModel;
    use Framework\Lib\DataBase\DataMapper\Collections\AbstractGeneratorCollection as Collection;

 
        /**
        *This Class Is Provide Access Of App To SeshatPublish Data In DataBase. 
        */
        Class TaskMapper extends AbstractDataMapper
        {
            private $table = 'tasks';
            private $foreign_key = 'user_id';
            private $columnsTable = ['primarykey'=>'id','task_id','details','expected_finish','is_finished','progress','task_name'];
            private $modelName = 'TaskModel';
            
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
