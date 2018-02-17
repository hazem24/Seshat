<?php

      namespace Framework\Lib\DataBase\Query\QueryBuilder;
      use Framework\Exception\DbException as DbException;
      use Framework\Lib\DataBase\Query\QueryBuilder\AbstractQueryBuilder as AbstractQueryBuilder;


      class UpdateQueryBuilder extends AbstractQueryBuilder
      {
          protected $sql = 'UPDATE ';
          /**
          *@param updateValue Carry The New Data In Which Will Updated col = new value from this @param
          */
          protected $updateValue;
          /**
          *@param data That Carry The Data Which Want To Update From It With Where Statment Found In The @parent where col = dataFromHere
          *@param targetTable That Carry The Table Which Want To Update Col At It Found In The @parent
          *@param columns That Carry The Specific Col To Update It With New Value Found In The @parent
          */

          /**
          *Client
          * update(['user','email'] ,['id'=>10,'user'=>'hazem'])
          *@param table can be mixed (string || Array)
          *@edited 11/11/2017 @03:45 Am Improved The Login Of Update And , Logic 
          */

          public function update($table , array $colWithVal):UpdateQueryBuilder{
                $this->targetTable = (is_array($table)) ? implode(',',$this->arrayFilter($table)) : $table ;
                $endElement = array_values(array_slice($colWithVal , -1 , true));
                //Counter
                $counter = count($colWithVal); 
                $count = 0;
                $this->updateValue = array_values($colWithVal);
                foreach($colWithVal as $col => $val){
                        $count++;
                        $this->columns .= " $col = ?";
                        if($count !== $counter){
                            $this->columns .= " , ";
                        }     
                }
                return $this;
          }


          public function join(string $table2 , string $tableCol1 , string $col2 , string $typeOfJoin = 'LEFT'):AbstractQueryBuilder{
               throw new DbException("Syntax Error You Cannot Create Join With Update You Can Create Automatic Multi Query With (update) Method Method @class " . __CLASS__);
               
          }

          


          public function createQuery():array{

            parent::createQuery();
            $this->sql .= " SET ";
            if(empty($this->columns) || !isset($this->columns)){
                        throw new DbException("<br>Syntax Error You Must Provide The Table Name In Which You Want To Select From It  @Class  " . __CLASS__);          
            }
                        $this->sql .= $this->columns;
            if(isset($this->where) && !empty($this->where)){
                        $this->sql .= $this->where;
            }
            // Check If Multi Update Happen Or Not To Create Sequence For Use Order By Or Limit method
            $multiUpdate = (explode(',',$this->targetTable));
            $multiUpdate = (count($multiUpdate) == 1) ? true : false;
            if($multiUpdate && isset($this->orderBy) && !empty($this->orderBy)){
                         $this->sql .= $this->orderBy;     
            }else if(!$multiUpdate && isset($this->orderBy) && !empty($this->orderBy)){
                    throw new DbException("<br> Syntax Error In Mysql Query You Can Not Provide OrderBy Method When Update  Single Table @class " . __CLASS__);

            }
            if($multiUpdate && isset($this->limit) && !empty($this->limit)){
                        $this->sql .= $this->limit;
            }else if(!$multiUpdate && isset($this->limit) && !empty($this->limit)){
                        throw new DbException("<br> Syntax Error In Mysql Query You Can Not Provide Limit Method When Update  Single Table @class " . __CLASS__);
            }           
                
                return ['query'=>sprintf($this->sql) , 'data'=>$this->data , 'updateto'=>$this->updateValue];
          }



      }
