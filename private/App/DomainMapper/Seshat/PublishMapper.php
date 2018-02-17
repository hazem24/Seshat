<?php 

    namespace App\DomainMapper\Seshat;
    use Framework\Lib\DataBase\DataMapper\AbstractDataMapper;
    use Framework\Registry as Registry;
    use Framework\Shared\Model;
    use Framework\Lib\DataBase\Query\QueryBuilder\SelectQueryBuilder;
    use Framework\Lib\DataBase\Query\QueryBuilder\InsertQueryBuilder;
    use App\Model\App\Seshat\PublishModel;
    use Framework\Lib\DataBase\DataMapper\Collections\AbstractGeneratorCollection as Collection;

 
        /**
        *This Class Is Provide Access Of App To SeshatPublish Data In DataBase. 
        */
        Class PublishMapper extends AbstractDataMapper
        {
            private $table = 'seshat_publish';
            private $foreign_key = 'user_id';
            private $columnsTable = ['primarykey'=>'id','tweet_id','category_id','public_access','publish_at'];
            private $modelName = 'PublishModel';
            
            public function save(Model $publishModel){
                   return $this->doSave($publishModel); 
            }    
            protected function doSave(Model $model){
                    if(is_null($model->getProperty('id'))){
                        //Insert New Publish Model.
                        $insertBuilder = new InsertQueryBuilder;
                        $stm = $insertBuilder->insert($this->table,[$this->foreign_key=>$model->getProperty('user_id'),
                        $this->columnsTable[0]=>$model->getProperty('tweet_id'),$this->columnsTable[1]=>$model->getProperty('category_id'),
                        $this->columnsTable[2]=>$model->getProperty('public_access')])->createQuery();
                        $newPublish  = $this->pdo->prepare($stm['query']);
                        $this->bindParamCreator(4,$newPublish,$stm['data']);
                        $newPublish->execute();
                        if($newPublish->rowCount() > 0){
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
