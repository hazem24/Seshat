<?php

      namespace Framework\Lib\DataBase\Query\QueryBuilder;
      use Framework\Exception\DbException as DbException;
      use Framework\Lib\DataBase\Query\QueryBuilder\AbstractQueryBuilder as AbstractQueryBuilder;
      use Framework\Helper\ArrayHelper as ArrayHelper;


      class InsertQueryBuilder extends AbstractQueryBuilder
      {
          protected $sql = 'INSERT INTO ';

         
          /**
          *@param data That Carry The Data Which Want To Insert Found In The @parent VALUES(From This @param)
          *@param targetTable That Carry The Table Which Want To Insert Data To It .. It Found In The @parent
          *@param columns That Carry The Specific Col To Insert It With New Value Found In The @parent
          */

          /**
          *Client
          * insert('<table>' ,['id'=>10,'user'=>'hazem'])
          *@param table string
          */

          public function insert(string $table , array $colWithVal):InsertQueryBuilder{
              $this->targetTable = $table;
              $colWithVal = ArrayHelper::filterDataWithArray($colWithVal); // $colWithVal[0] Col Want To Insert Inside It .. $colWithVal[1] Values To Insert
              $this->columns = implode(',',$colWithVal[0]);
              $this->data    = $colWithVal[1];
              return $this;
                
          }

          public function where(array $conditions = null):AbstractQueryBuilder{
              throw new DbException("<br> Syntax Error You Can Not To Use Where Statment With Insert Query @class" . __CLASS__);
              
          }
          

          public function join(string $table2 , string $tableCol1 , string $col2 , string $typeOfJoin = 'LEFT'):AbstractQueryBuilder{
               throw new DbException("Syntax Error You Cannot Create Join With Insert Method @class " . __CLASS__);
               
          }

          public function limit(int $limit = 10 , int $offset = 0):AbstractQueryBuilder{
                        throw new DbException("Syntax Error You Can Not Create Limit @insert Method @class " . __CLASS__);
                        
          }

          public function orderBy(array $orderData):AbstractQueryBuilder{
                    throw new DbException("Syntax Error You Can Not Create OrderBy @insert Method @class " . __CLASS__);

          }

          /**
          INSERT INTO tbl_name
                (a,b,c)
            VALUES
                (1,2,3),
                (4,5,6),
                (7,8,9);
          */  
          public function createQuery($multiInsert = false):array{

            parent::createQuery();
            if(empty($this->columns)){
                        throw new DbException("<br>Syntax Error You Must Add Columns That You Want To Insert Data To It  " . __CLASS__);          
            }
            $this->sql .= "  ( $this->columns ) VALUES ";
            if(empty($this->data)){
                        throw new DbException("<br>Syntax Error You Must Add Data That You Want To Insert Data To It  " . __CLASS__);          
            }
            if($multiInsert === true){
                    $data = ArrayHelper::convertMultiArray($this->data);
                    $endElement = end($data);
                    $columns = count(explode(',',$this->columns));
                    $counter = count($data);//6
                    $limit   = $counter/2;//3
                    $count = 0;    
                    for($i=0;$i<$counter;$i++){
                            
                            if($endElement === $data[$i]){
                                         $this->sql .= " ( "  . $this->placeHolder($columns) . " )";
                            }else{
                                    if($limit - 1 == $count){
                                        // Do Nothing                                           
                                    }else{
                                        $count+=1;   
                                        $this->sql .= " ( "  . $this->placeHolder($columns) . " ),"; 
                                    }
                            }
                    }
                        /**
                        *Rearrange Data So To Be Easy To Enter To DataBase
                        */
                        $sliceFirstValue = array_slice($data ,$limit);
                        $sliceSecondValue = array_slice($data , 0 , $limit);
                        for($i=0;$i<$limit;$i++){
                                $merge[]   = $sliceSecondValue[$i];
                                $merge[]   = $sliceFirstValue[$i];
                        }
                        $this->data = $merge;
            }else{
                $this->sql .= " ( "  . $this->placeHolder(count($this->data)) . " ) ";
            }            
            return ['query'=>sprintf($this->sql) , 'data'=>$this->data];
      }
}
