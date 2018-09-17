<?php 

    namespace App\DomainMapper\Seshat;
    use Framework\Lib\DataBase\DataMapper\AbstractDataMapper;
    use Framework\Registry as Registry;
    use Framework\Shared\Model;
    use Framework\Lib\DataBase\Query\QueryBuilder\SelectQueryBuilder as select;
    use Framework\Lib\DataBase\Query\QueryBuilder\InsertQueryBuilder as insert;
    use Framework\Lib\DataBase\Query\QueryBuilder\UpdateQueryBuilder as update;
    use App\Model\App\Seshat\NotificationModel;
    use Framework\Lib\DataBase\DataMapper\Collections\AbstractGeneratorCollection as Collection;

 
        /**
        *This Class Is Provide Access Of App To SeshatPublish Data In DataBase. 
        */
        Class NotificationMapper extends AbstractDataMapper
        {
            private $table = 'notification';
            private $foreign_key = 'user_id';
            private $columnsTable = ['primaryKey'=>'id','type','status','notify_msg','is_read','created_at'];
            private $modelName = 'NotificationModel';
            


            public function getUserNotifications( int $owner ){
                $select = new select;
                $select = $select->select()->from( $this->table )->where([$this->foreign_key . ' = ?'=>$owner])->
                limit(100)->orderBy([$this->columnsTable['primaryKey'] => 'DESC'])->createQuery();
                $notifications = $this->pdo->prepare( $select['query'] );
                $this->bindParamCreator( 1 , $notifications , $select['data'] );
                $notifications->execute();
                $notifications = $notifications->fetchAll( \PDO::FETCH_ASSOC );
                return $notifications;
            }

            public function unReadNotifications( int $owner ){
                $select = new select;
                $select = $select->select()->from( $this->table )->where([$this->columnsTable[3] . ' <> ? && ' =>true,
                $this->foreign_key . ' = ?'=>$owner])->createQuery();
                $unRead = $this->pdo->prepare( $select['query'] );
                $this->bindParamCreator( 2 , $unRead , $select['data'] );
                $unRead->execute();
                $unRead = $unRead->fetchAll( \PDO::FETCH_ASSOC );
                if ( $unRead !== false && !empty( $unRead ) ){
                    //update unRead notification to read.
                    $update = new update;
                    $update = $update->update($this->table,[$this->columnsTable[3]=>true])
                    ->where([$this->foreign_key . ' = ?'=>$owner])->createQuery();
                    $updateNotifications = $this->pdo->prepare( $update['query'] );
                    $this->bindParamCreator( 2 , $updateNotifications , array_merge($update['updateto'],$update['data']));
                    $updateNotifications->execute();
                }
                return $unRead;
            }

            public function getNotificationsByType( int $type , int $owner ){
                $select = new select;
                $select = $select->select()->from( $this->table )->where([$this->columnsTable[0] . ' = ? &&' =>$type,
                $this->foreign_key . ' = ?'=>$owner])->createQuery();
                $notifications = $this->pdo->prepare( $select['query'] );
                $this->bindParamCreator( 2 , $notifications , $select['data'] );
                $notifications->execute();
                $notifications = $notifications->fetchAll( \PDO::FETCH_ASSOC );
                return $notifications;
            }

            public function save(Model $taskModel){
                return $this->doSave($taskModel); 
            }
            protected function doSave(Model $model){
                if(is_null($model->id)){
                    $insert = new insert;
                    $insert = $insert->insert( $this->table ,[$this->columnsTable[0]=>$model->type , 
                    $this->columnsTable[1]=>$model->status ,
                    $this->columnsTable[2]=>$model->notify_msg,
                    $this->foreign_key => $model->owner])->createQuery();
                    $notify = $this->pdo->prepare( $insert['query'] );
                    $this->bindParamCreator( 4 , $notify  , $insert['data'] );
                    $notify->execute();
                }else{
                    //update will not present in notification system i think this .. written @16/09/2018 at 04:02 PM.
                }
            }
            protected function createObject(array $fields):Model{
            }
            protected function getCollection(array $raw): Collection{

            }
            protected function selectAllStatement():\PDOStatement{

            }
        }
