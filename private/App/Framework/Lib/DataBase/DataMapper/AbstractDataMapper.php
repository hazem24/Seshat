<?php

       namespace Framework\Lib\DataBase\DataMapper;
       use Framework\ConstructorClass;
       use Framework\Registry;
       use Framework\Exception\DbException;
       use Framework\Lib\DataBase\Query\QueryBuilder\SelectQueryBuilder;
       use Framework\Lib\DataBase\DataMapper\Collections\AbstractGeneratorCollection as Collection;
       use Framework\Shared;

       Abstract Class AbstractDataMapper extends ConstructorClass
       {
           protected $pdo;
           protected $selectBuilder;
           protected $updateBuilder;
           protected $insertBuilder;
           protected $replaceBuilder;
           protected $predifined;


           /**
           *Some Schema Can Be Share Via All Mapper
           */
           protected $tableName;
           protected $columns;

           public function __construct(){
                    $this->pdo           = Registry::getInstance('database');
                    $this->selectBuilder =  new SelectQueryBuilder;
           }
           /**
           *@method find this find specific type Of specific  Model
           *$find parameter In This Shape ['find'=>'whatTofind','type'=>'integer || string']
           **/

           public function find(array $find){
                 $col   = array_keys($find); // Return Col Name
                 $value = $find[$col[0]];     // Return Value To Find
                 /**
                 *I Must Put Here The Check Conditions Like Session Mapper 
                 *I Not Write It Until Now Beacuse I Dont Now Which Exception Will Be Throw
                 */
                 $type  = $this->typeOfDataToEnterDb(gettype($value));
                 $find  = $this->selectStatement($value , $col[0]);
                 $find->bindParam(1 , $value , $this->predifined);
                 $find->execute();
                 /**
                 *If I need More Fetch Style This @method Must Be Refactor 
                 */
                 $row   = $find->fetch(\PDO::FETCH_ASSOC);
                 $find->closeCursor();
                 if(!is_array($row)){
                        return null; // No Returned Value With This Conditions
                 }
                 if(!$row){
                        return false; // On Failure
                 }
                 $object = $this->createObject($row);
                 return $object;

           }
           /**
           *@method findAll Find All Values From Specific Object In DB
           */

           public function findAll(){
               $findAll = $this->selectAllStatement();
               $findAll->execute();
               $rows = $findAll->fetchAll(\PDO::FETCH_ASSOC);
               $findAll->closeCursor();
                if(!is_array($rows)){
                        return null; // No Returned Value With This Conditions
                 }
                if(!$rows){
                        return false; // On Failure
                 }
                 return $this->getCollection($rows);


           }
           /**
           *@method save Model $model 
           *The mechainsim Of This Method To Know If Update Query Or Insert Query 
           *First It Check If The Primary Key Of Specific Table  (Mapper) Exists Or Not 
           *If Exists This Mean Update Query Must Be Happen Else Insert Query 
           */
           public function save(Shared\Model $model){
                    $c = 'get'.ucfirst($this->columns['primarykey']);
                    if(!is_null($model->$c()) ){
                                $this->updateBuilder = Registry::getInstance('updateBuilder');
                    }else{
                                $this->insertBuilder = Registry::getInstance('insertBuilder');
                    }
                    $this->doSave($model);
           }


           protected function bindParamCreator(int $count , \PDOStatement &$create , array $data){
                
                for($i=0; $i<$count ; $i++){
                                
                                $create->bindParam($i+1,$data[$i],$this->typeOfDataToEnterDb(gettype($data[$i])));
                }
           }
           /**
           *@return The Filter In Which Can Be Use In Pdo Statement
           */
           protected function typeOfDataToEnterDb(string $type){
                    switch ($type) {
                        case 'integer':
                            $this->predifined = \PDO::PARAM_INT;
                            break;
                        case 'string':
                            $this->predifined = \PDO::PARAM_STR;
                        break;
                        case 'boolean':
                            $this->predifined = \PDO::PARAM_BOOL;
                            break;
                        case 'unknown type':
                            throw new DbException("You Try To Enter DataBase Query With Unknown Type Of Data @class ". get_class($this));    
                            
                        default:
                            throw new DbException("Oops I See Strange Type Of Data You Want To Enter To DataBase  @class ". get_class($this));    
                        break;
                    }

           }
           /**
           *@method createObject This Method Responsiable For Create Object Of Specific Class (Type)
           *@return Model 
           */

           protected function createObject(array $fields):Shared\Model{
                          $profileObjectFactory = Shared\ObjectFactory::getObjectFactory(static::class);
                          return $profileObjectFactory->createObject($fields);   
            }

            /**
            *@return Object From ObjectFactory Which Can Generate Object Of Specific Model
            */
            protected function getFactory(string $factoryName){
                            return Shared\ObjectFactory::getObjectFactory($factoryName);
            }
           /**
           *@method createCommand This Method Handle The Logic If Uses DataBase Function Or Stored Procedure Or Manual Query
           *@param command Must Be => "CALL storedProcedureName(?, ?)" Or Any Manual Query => "Select * from <tableName>"
           *@return PDOSTATMENT
           */
           final protected function createCommand(string $command):\PDOStatement{
                        return $this->pdo->prepare($command);   
           }
           final protected function selectStatement($value , $col):\PDOStatement{
                        $stm  =  $this->selectBuilder->select()->from($this->tableName)->where([$col."=?"=>$value])->createQuery();
                        return $this->pdo->prepare($stm['query']);
           }
           
           Abstract protected function doSave(Shared\Model $model);
           Abstract protected function getCollection(array $raw): Collection;
           Abstract protected function selectAllStatement():\PDOStatement;
 
       }
