<?php 

    namespace App\DomainMapper;
    use Framework\Lib\DataBase\DataMapper\AbstractDataMapper;
    use Framework\Registry as Registry;
    use Framework\Shared\Model;
    use Framework\Lib\DataBase\Query\QueryBuilder\SelectQueryBuilder;
    use Framework\Lib\DataBase\Query\QueryBuilder\UpdateQueryBuilder;
    use Framework\Lib\DataBase\Query\QueryBuilder\InsertQueryBuilder;
    use App\Model\App\UserModel;
    use Framework\Lib\DataBase\DataMapper\Collections\AbstractGeneratorCollection as Collection;

 
        /**
        *This Class Is Provide Access Of App To User Data In DataBase. 
        */
        Class UserMapper extends AbstractDataMapper
        {
            private $tableA        = 'user';
            private $tableB        = 'user_data';
            private $tableC        = 'license';
            private $foreign_key   = 'user_id';
            private $columnsTableA = ['primarykey'=>'user.id','tw_id','screen_name'];
            private $columnsTableB = ['email','account_type','user_describe','iswizard','created_at','name','license_id'];
            private $columnsTableC = ['license_type','license_name'];
            private $modelName     = 'UserModel';
           
                
            public function stepOne(Model $userModel){
                    $insertBuilder = new InsertQueryBuilder;
                    $stm = $insertBuilder->insert($this->tableA,
                    [$this->columnsTableA[0]=>$userModel->getProperty("tw_id"),
                    $this->columnsTableA[1]=>$userModel->getProperty("screen_name")])->createQuery();
                    $insertNewUser = $this->pdo->prepare($stm['query']);
                    $this->bindParamCreator(2,$insertNewUser,$stm['data']);
                    $insertNewUser->execute();
                    if($insertNewUser->rowCount() > 0){
                        return ['success'=>true,'id'=>$this->pdo->lastInsertId()];//User Inserted !.
                    }
                        return false;// Something Error.
            }


            public function userExists(string $tw_id){
                   $selectBuilder = new SelectQueryBuilder;
                   $stm = $selectBuilder->select()->from($this->tableA)->where([$this->columnsTableA[0] . ' = ?'=>$tw_id])->createQuery();
                   $userExists = $this->pdo->prepare($stm['query']);
                   $this->bindParamCreator(1,$userExists,$stm['data']);
                   $userExists->execute();
                   $userExists = $userExists->fetch(\PDO::FETCH_ASSOC);
                   if($userExists !== false){
                        return true;//User Exists.
                   } 
                        return false;//User Not Exist.
            }


            public function getUserData(string $tw_id){
                   $selectBuilder = new SelectQueryBuilder;
                   $stm = $selectBuilder->select(array_merge($this->columnsTableA,$this->columnsTableB,$this->columnsTableC))
                   ->from($this->tableA)->
                   join($this->tableB,$this->columnsTableA['primarykey'],$this->foreign_key)->
                   join($this->tableC,$this->columnsTableB[6],'id')->
                   where([$this->columnsTableA[0] . ' = ?'=>$tw_id])->createQuery();
                   $findUser = $this->pdo->prepare($stm['query']);
                   $this->bindParamCreator(1,$findUser,$stm['data']);
                   $findUser->execute();
                   $findUser = $findUser->fetch(\PDO::FETCH_ASSOC);
                   if($findUser !== false && is_array($findUser) && !empty($findUser)){
                         return $this->createObject($findUser);
                   } 
                         return false;//Error Happen.!
            }

            public function createProfile(int $id , string $name , string $email,string $account_decribe,int $account_type){
                if($this->emailExists($email) === false){
                    $insertBuilder = new InsertQueryBuilder;
                    $stm  = $insertBuilder->insert($this->tableB,[$this->foreign_key=>$id,$this->columnsTableB[0]=>$email
                    ,$this->columnsTableB[1]=>$account_type,$this->columnsTableB[2]=>$account_decribe,$this->columnsTableB[5]=>$name,$this->columnsTableB[3]=>false])->createQuery();
                    $createProfile = $this->pdo->prepare($stm['query']);
                    $this->bindParamCreator(6,$createProfile,$stm['data']);
                    $createProfile->execute();
                    if($createProfile->rowCount() > 0){
                        return true;//Success.
                    }
                        return false;//Fail To Insert.  
                }else{
                        return ['emailExists'=>true];
                }
            }

            private function emailExists(string $email):bool{
                    $selectBuilder = new SelectQueryBuilder;
                    $stm = $selectBuilder->select()->from($this->tableB)->where([$this->columnsTableB[0] . ' = ?'=>$email])->createQuery();
                    $emailExists = $this->pdo->prepare($stm['query']);
                    $this->bindParamCreator(1,$emailExists,$stm['data']);
                    $emailExists->execute();
                    $emailExists = $emailExists->fetch();
                    if(is_array($emailExists) && !empty($emailExists)){
                        return true;
                    }
                        return false;
            } 
            protected function doSave(Model $model){
            }
            /**
             * i Can Use PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE Instead Of This.
             */
            protected function createObject(array $fields):Model{
                      $userModel = new UserModel;
                      $userModel->setProperty("tw_id",$fields['tw_id']);
                      $userModel->setProperty("screen_name",$fields['screen_name']);
                      $userModel->setProperty("email",$fields['email']);
                      $userModel->setProperty("name",$fields['name']);
                      $userModel->setProperty("iswizard",$fields['iswizard']);
                      $userModel->setProperty("created_at",$fields['created_at']);
                      $userModel->setProperty("account_type",(int)$fields['account_type']);
                      $userModel->setProperty("user_describe",$fields['user_describe']);
                      $userModel->setProperty("id",(int)$fields['id']);
                      $userModel->setProperty("license_type",(int)$fields['license_type']);
                      $userModel->setProperty("license_name",$fields['license_name']);
                      return $userModel;  
            }
            protected function getCollection(array $raw): Collection{

            }
            protected function selectAllStatement():\PDOStatement{

            }
 




        }
