<?php 

    namespace App\DomainMapper\Seshat;
    use Framework\Registry as Registry;
    use Framework\Shared\Model;
    use Framework\Lib\DataBase\Query\QueryBuilder\SelectQueryBuilder;
    use Framework\Lib\DataBase\Query\QueryBuilder\InsertQueryBuilder;
    use Framework\Lib\DataBase\Query\QueryBuilder\UpdateQueryBuilder as update;
    use Framework\Lib\DataBase\Query\QueryBuilder\DeleteQueryBuilder as Delete;
    use App\Model\App\Seshat\TaskModel;
    use Framework\Lib\DataBase\DataMapper\Collections\AbstractGeneratorCollection as Collection;

 
        /**
        *This Class Is Provide Access Of App To SeshatPublish Data In DataBase. 
        */
        Class TaskMapper extends BaseMapper
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

            /**
             * this method get postAsInfo from DB.
             */
            public function postAsInfo( int $user_id , string $media ){
                $redis = $this->redis();
                $item  = $redis->getItem( "task=postAs,userID=$user_id,media=$media" );
                if ( is_null( $redis->get( $item ) ) === true ){
                    //load info.
                    $select    = new SelectQueryBuilder;
                    $select    = $select->select()->from( $this->table )->where([$this->columnsTable[0] . " = ? && " => 2 , 
                    $this->foreign_key . " = ? && " => $user_id , $this->columnsTable[3] . " <> ? "=> true])->createQuery();
                    $postAsInfo = $this->pdo->prepare( $select['query'] );
                    $this->bindParamCreator( 3 , $postAsInfo , $select['data'] );
                    $postAsInfo->execute();
                    $postAsInfo = $postAsInfo->fetchAll(\PDO::FETCH_ASSOC);
                    if (is_array( $postAsInfo ) && !empty( $postAsInfo )){
                        $redis->set( $item , $postAsInfo , 10800);// Expire after 3 hr.
                    }else{
                        $redis->set( $item , $postAsInfo , 3600);// Expire after 1 hr.
                    }
                }else{
                    $postAsInfo = $redis->get( $item );
                }
                return $postAsInfo;
            }

            /**
             * this method used to load all schedule posts from DB.
             */
            public function getPosts(){
                $redis = $this->redis();
                $posts = $redis->getItem("posts");
                if (is_null( $redis->get( $posts ) ) === true){
                    //load posts from the DB.
                    $select    = new SelectQueryBuilder;
                    $select    = $select->select()->from( $this->table )
                    ->where([$this->columnsTable[3] . " <> ? && " => true , 
                    $this->columnsTable[6] . " <> ? && " => true , 
                    $this->columnsTable[2] . " like ? " => "%" .  date("Y-m-d") . "%" ])
                    ->orderBy([$this->columnsTable[2] => 'ASC'])->createQuery();
                    $getPosts  = $this->pdo->prepare( $select['query'] );
                    $this->bindParamCreator( 3 , $getPosts , $select['data'] );
                    $getPosts->execute();
                    $getPosts = $getPosts->fetchAll(\PDO::FETCH_ASSOC);    
                    if($getPosts !== false && !empty($getPosts)){
                        $redis->set( $posts , $getPosts , 84600); // 24 hr.
                        $posts = $redis->get( $posts );
                    }else{
                        $posts = [];
                    }
                }else{
                    $posts = $redis->get( $posts );
                }
                return $posts;
            }

            /**
             * this method load control followers task data.
             * @method getControlFollowersTask.
             * @return array.
             */
            public function getControlFollowersTask(Model $model , string $media){
                $redis = $this->redis();
                $controlFollowers = $redis->getItem( "taskID=$model->task_id,userID=$model->user_id,media=$media" );
                if ( is_null($redis->get( $controlFollowers )) === true ){
                    $select = new SelectQueryBuilder;
                    $select = $select->select()->from( $this->table )->
                    where([ $this->columnsTable[0] . ' = ? && '=>$model->task_id ,  $this->foreign_key . ' = ? && '=>$model->user_id
                    , $this->columnsTable[3] . ' <> ? '=> true])->createQuery();
                    $controlFollower = $this->pdo->prepare( $select['query'] );
                    $this->bindParamCreator( 3 , $controlFollower , $select['data'] );
                    $controlFollower->execute();
                    $controlFollowerInfo = $controlFollower->fetch(\PDO::FETCH_ASSOC);
                    if (is_array( $controlFollowerInfo ) && !empty( $controlFollowerInfo )){
                        $redis->set($controlFollowers , $controlFollowerInfo , 84600);//24 hr.
                        $controlFollowers = $redis->get( $controlFollowers );
                    }else{
                        $controlFollowers = [];
                    }
                }else{
                    $controlFollowers = $redis->get( $controlFollowers );
                }
                return $controlFollowers;
            }

            protected function doSave(Model $model){
                    if(is_null($model->id)){
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
                            $task_id = $this->pdo->lastInsertId();//id of task.
                            $this->saveInRedis( ['id'=> $task_id , 'details'=>$model->getProperty('details'),
                            'expected_finish'=>$model->getProperty('expected_finish'),
                            'task_name'=>$model->getProperty('task_name') , 
                            'user_id'=>$model->getProperty('user_id'),
                            'progress'=>'0','status'=>'0','is_finished'=>'0','task_id'=>$model->getProperty('task_id')] , (int) $model->getProperty('task_id'));
                            return true;  //Saved Succesfully.
                        }
                        return false; //Not Saved.
                    }else{
                        return $this->editTask($model);
                    }
            }

            /**
             * Edit task.
             * @method editTask.
             * @return bool.
             */
            private function editTask(Model $model){
                $update = new update;
                $update = $update->update( $this->table , [
                $this->columnsTable[4]=>$model->progress,$this->columnsTable[6]=>$model->status,$this->columnsTable[3]=>$model->is_finished] )
                ->where([$this->primaryKey . ' = ? '=>$model->id])->createQuery();
                $edit = $this->pdo->prepare( $update['query'] );
                $this->bindParamCreator( 4 , $edit , array_merge($update['updateto'],$update['data']) );
                $edit->execute();
            }
            /**
             * uses to set the task in Redis Db if needed.
             * @method saveInRedis.
             * @return void.
             */
            private function saveInRedis( array $task_data , int $task_id , int $user_id = 0){
                $redis = $this->redis();
                switch ($task_id) {
                    case 1://posts.
                        $posts = $redis->getItem( 'posts' );
                        if (is_null( $redis->get( $posts ) ) === false){
                            $expiredAt = $posts->getExpirationDate()->format("Y-m-d H:i");
                            if ($task_data['expected_finish'] < $expiredAt){
                                $posts->append( $task_data );
                                $redis->save( $posts );
                            }
                        }
                        break;
                    
                    default:
                        // Nothing Here.
                        break;
                }
            }
            protected function createObject(array $fields):Model{
            }
            protected function getCollection(array $raw): Collection{

            }
            protected function selectAllStatement():\PDOStatement{

            }
        }
