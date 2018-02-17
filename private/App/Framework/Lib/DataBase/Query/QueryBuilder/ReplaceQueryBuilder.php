<?php

      namespace Framework\Lib\DataBase\Query\QueryBuilder;
      use Framework\Exception\DbException as DbException;
      use Framework\Lib\DataBase\Query\QueryBuilder\AbstractQueryBuilder as AbstractQueryBuilder;
      use Framework\Registry   as Registry;
      use Framework\Helper\ArrayHelper as ArrayHelper;

      Class ReplaceQueryBuilder extends AbstractQueryBuilder
      {
            // REPLACE INTO <table>  ( %s, %s) VALUES (%s , %s) " ,
          protected $sql = "REPLACE INTO ";

           
          /**
          *@param data That Carry The Data Which Want To Insert Found In The @parent VALUES(From This @param)
          *@param targetTable That Carry The Table Which Want To Insert Data To It .. It Found In The @parent
          *@param columns That Carry The Specific Col To Insert It With New Value Found In The @parent
          */

          /**
          *Client
          * replace('<table>' ,['id'=>10,'user'=>'hazem'])
          *@param table string
          */

          public function replace(string $table , array $colWithVal):ReplaceQueryBuilder{
                        $this->targetTable = $table;
                        $colWithVal = ArrayHelper::filterDataWithArray($colWithVal); // $colWithVal[0] Col Want To Insert Inside It .. $colWithVal[1] Values To Insert
                        $this->columns = implode(',',$colWithVal[0]);
                        $this->data    = $colWithVal[1];
                        return $this;
                   
          }

            public function where(array $conditions = null):AbstractQueryBuilder{
              throw new DbException("<br> Syntax Error You Can Not To Use Where Statment With Replace Query @class" . __CLASS__);
              
          }
          

          public function join(string $table2 , string $tableCol1 , string $col2 , string $typeOfJoin = 'LEFT'):AbstractQueryBuilder{
               throw new DbException("Syntax Error You Cannot Create Join With Replace Method @class " . __CLASS__);
               
          }


          public function limit(int $limit = 10 , int $offset = 0):AbstractQueryBuilder{
                        throw new DbException("Syntax Error You Can Not Create Limit @replace Method @class " . __CLASS__);
                        
          }



          public function orderBy(array $orderData):AbstractQueryBuilder{
                    throw new DbException("Syntax Error You Can Not Create OrderBy @replace Method @class " . __CLASS__);

          }


          public function createQuery():array{
            parent::createQuery();
            if(empty($this->columns)){
                        throw new DbException("<br>Syntax Error You Must Add Columns That You Want To Replace Data To It  " . __CLASS__);          
            }
            $this->sql .= "  ( $this->columns ) VALUES ";
            if(empty($this->data)){
                        throw new DbException("<br>Syntax Error You Must Add Data That You Want To Replace Data To It  " . __CLASS__);          
            }
            $this->sql .= " ( "  . $this->placeHolder(count($this->data)) . " ) ";
            return ['query'=>sprintf($this->sql) , 'data'=>$this->data];
          } 
      }

