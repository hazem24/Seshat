<?php 


        namespace App\DomainMapper;
        use Framework\Lib\DataBase\DataMapper\AbstractDataMapper;
        use Framework\Registry as Registry;
        use Framework\Exception\SessionException as SessionException;
        use Framework\Shared\Model;
        use Framework\Lib\DataBase\DataMapper\Collections\AbstractGeneratorCollection as Collection;
        use Framework\Lib\DataBase\Query\QueryBuilder\ReplaceQueryBuilder as ReplaceQueryBuilder;
        use Framework\Lib\DataBase\Query\QueryBuilder\DeleteQueryBuilder as DeleteQueryBuilder;

 
        /**
        *This Class Provide Simple Example To Use Session Mapper 
        */
        Class SessionMapper extends AbstractDataMapper
        {
            protected $tableName = 'seshatsession';
            protected $columns = ['primarykey'=>'id' , 
                                 'access'=>'lastaccess',
                                 'dataColumn'=>'sdata'];
            private $replace;
            private $delete;                     
            public function __construct(){
                parent::__construct();
                $this->replace = new ReplaceQueryBuilder;
                $this->delete  = new DeleteQueryBuilder;
            }

            public function find(array $find){
                 $col   = array_keys($find); // Return Col Name  
                 if(!in_array($col[0],$this->columns)){
                        throw new SessionException("Error You Try To Select Data From Unknown Columns Allowable Columns ( " . implode(',',$this->columns) . " )");
                 }
                 $value = $find[$col[0]];     // Return Value To Find
                 
                 $type  = $this->typeOfDataToEnterDb(gettype($value)); 
                 $data  = $this->selectStatement($value , $col[0]);
                 $data->bindValue(1 , $value , $this->predifined);
                 $data->execute();
                
                 /**
                 *If I need More Fetch Style This @method Must Be Refactor 
                 */
                 $row   = $data->fetch(\PDO::FETCH_ASSOC);
                 
                 $data->closeCursor();
                 if(!is_array($row)){
                        return null; // No Returned Value With This Conditions
                 }
                 if(!$row){
                        return false; // On Failure
                 }


                 return $row[$this->columns['dataColumn']]; 

            }

            public function replace(string $id , string $data){
                    $stm     = $this->replaceStatement($id , $data);
                    $replace = $this->pdo->prepare($stm['query']);
                    $this->bindParamCreator(count($stm['data']) , $replace , $stm['data']);
                    $replace->execute();
                    
                    if($replace->rowCount() >= 1){
                          
                            return true;
                    }
                    return false;

            }

            public function gc(int $lifeTime){
                    $stm = $this->deleteStatement(['lifeTime'=>$lifeTime]);
                    $query = $this->pdo->prepare($stm['query']);
                    $this->bindParamCreator(count($stm['data']) , $query , $stm['data']);
                    $query->execute();
                    if($query->rowCount() >= 1){
                            return true;
                    }
                    return false;
            }

            public function destroy(string $id){
                    $stm = $this->deleteStatement(['id'=>$id]);
                    $query = $this->pdo->prepare($stm['query']);
                    $this->bindParamCreator(count($stm['data']) , $query , $stm['data']);
                    $query->execute();
                    if($query->rowCount >= 1){
                            return true;
                    }
                            return false;
            }

            protected function replaceStatement(string $id , string $data){
                     return $this->replace->replace($this->tableName , [$this->columns['primarykey']=>$id , $this->columns['dataColumn'] =>$data])->createQuery();
            }
            /**
            *array $delete in This Form ['id || lifeTime'=>$val]
            */
            protected function deleteStatement(array $delete){
                      $deleteStm    = $this->delete->delete($this->tableName);
                      $typeOfDelete = array_keys($delete);
                      switch (strtolower($typeOfDelete[0])) {
                          case 'lifetime':
                               return $deleteStm->where([' DATE_ADD('.$this->columns['access'].' , INTERVAL ? SECOND) < NOW()'=>$delete[$typeOfDelete[0]]])->createQuery();
                              break;
                          case 'id':
                              return $deleteStm->where(['id='=>$delete[$typeOfDelete[0]]])->createQuery();
                          default:
                                    throw new SessionException("invaild Type Of Delete Enter To Session Please Fix It You Must Choose Between (LifeTime Or Id) As Type Of Delete In Session Query  ");
                          break;
                      } 

            }


            public function findAll(){
                        throw new SessionException("Cannot Use This Method");

            }
         
           public function save(Model $model){
                        throw new SessionException("Cannot Use This Method");

            }

            protected function doSave(Model $model){

            }
            protected function createObject(array $fields):Model{

            }
            protected function getCollection(array $raw): Collection{

            }
            protected function selectAllStatement():\PDOStatement{

            }
 




        }
