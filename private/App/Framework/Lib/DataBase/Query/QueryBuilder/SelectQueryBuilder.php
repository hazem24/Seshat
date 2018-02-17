<?php

      namespace Framework\Lib\DataBase\Query\QueryBuilder;
      use Framework\Lib\DataBase\Query\QueryBuilder\AbstractQueryBuilder as AbstractQueryBuilder;
      use Framework\Exception\DbException as DbException;
      use Framework\Helper\ArrayHelper as ArrayHelper;

      /**
      *@class this class provide all Essential Tools To Create Select Query
      *@created at 25/05/2017 at 02:26 Am
      */

      /**
      *@client 
      * $query->select('<col|s>')->from('<table>')->where(['id'=>'10']); @return select <col> from <table> where id = ? // To Be Use In Prepare
      */


      /**
      *@note array_filter() Function In Every @method Need Refactor 
      *@written At 28/05/2017 @ 08:03 Am


      *Done Refoactor I Create One @method arrayFilter(@param array $array) With Handle This Problem In AbstractQueryBuilder @parent
      */

      Class SelectQueryBuilder extends AbstractQueryBuilder
      {
          protected $sql = 'SELECT ';
          protected $index;
          protected $groupBy;
          protected $having;
          



          /**
          *@method select this method edit select query until $columns Name
          *@return $this
          *I Think In Future This Method Does Not Take Array Only I Can Put String Also To Provide One Columns Only
          */
          public function select(array $columns = ['*']):SelectQueryBuilder{
                $columns = (is_array($columns)) ? $this->arrayFilter($columns) : false;
                if(!$columns || empty($columns)){
                        throw new DbException("<br>Error For Build Select Statement @COLUMNS Not Vailed OR Empty @Class  " . __CLASS__);
                }
                $columns = implode(',',$columns);
                $this->columns = $columns;
                return $this;
          }
          /**
          *@method from Add The Target Table or tables to be Select From It
          *@param string | array of table\s Name
          */
          public function from($tableName):SelectQueryBuilder{
                if(!empty($tableName) && isset($tableName)){
                        $this->targetTable = (!is_array($tableName)) ?  $tableName : implode(',',$tableName);
                        $this->targetTable = " FROM $this->targetTable ";
                        return $this;
                }
               throw new DbException("<br>Table Name Not Exists Or Empty You Must Add Vailed Table Name @Class  " . __CLASS__);
          }

       

       
   
          /**
          *@method indexBy param Can Be String || Anyoumous Function
          */
          public function indexBy($indexName = null):SelectQueryBuilder{
                  $index = (!empty($indexName) && isset($indexName)) ? $indexName : null;
                
                  if(is_null($index)){
                              throw new DbException("Index Name Not Vailed To Use In Mysql @Class ". __CLASS__);
                  }
                  $this->index = ' USE INDEX ('.$index.')';
                  return $this;
          }
          /**
          *@method groupBy param $groupCol Array Of Columns Name To Grouped By 
          *W3School Description The GROUP BY statement is often used with aggregate functions (COUNT, MAX, MIN, SUM, AVG) 
          *to group the result-set by one or more columns.
          */
          public function groupBy(array $groupCol):SelectQueryBuilder{
                        
                        $groupColFilter = $this->arrayFilter($groupCol);
                        if(!$this->dataCount($groupColFilter , $groupCol)){
                              throw new DbException("You Put One Or More Empty Columns Name To GroupBy Please Fix This @class ". __CLASS__);                                    
                        }
                        $this->groupBy = " GROUP BY " . implode(',',$groupCol) . " ";
                        return $this;
          }
          /**
          *@method having 
          *Does't Support And || Logic 
          *Need Update @written @28/05/2017 @08:37 AM
          *I Must Modifiy It  If I Need It In Domain Logic 
          */

          public function having(array $options):SelectQueryBuilder{
                  $optionFilter = $this->arrayFilter($options);
                  if(!$this->dataCount($options , $optionFilter)){
                              throw new DbException("You Put One Or More Empty Data  To HAVING Not Allowed In Mysql Syntax Please Fix This @class ". __CLASS__);                                    
                  }
                  $options      =  ArrayHelper::filterDataWithArray($options);// Return Tables Of Conditions as [0] Index And Conditional Values As [1] Index
                  $this->having =   " HAVING ".implode(' ',$options[0])." ". implode(' ',$options[1]); // $option[0] Col + Operator , $option[1] Value
                  return $this;
          }
      
          /**
          *@method createQuery This method provide the Right Sequence To Create Right Mysql Select Query
          */
         public function createQuery():array{
                  if(empty($this->columns)){
                        throw new DbException("<br>Syntax Error You Must Add Columns That You Want To Retreive  From DataBase " . __CLASS__);          
                  }
                        $this->sql .= $this->columns;
                   parent::createQuery(); // For Set TargetTable ..
                  if(isset($this->index)){
                          $this->sql .= $this->index;
                  }
                  if(is_array($this->join)){
                          $this->sql .= implode(' ',$this->join);    
                  }
                  if(isset($this->where) && !empty($this->where)){
                        $this->sql .= $this->where;
                  }
                  if(isset($this->groupBy)){
                        $this->sql .= $this->groupBy;
                  }
                  if(isset($this->having)){
                        $this->sql .= $this->having;
                  }
                  if(isset($this->orderBy) && !empty($this->orderBy)){
                         $this->sql .= $this->orderBy;     
                  }
                  if(isset($this->limit) && !empty($this->limit)){
                        $this->sql .= $this->limit;
                  }
                  return ['query'=>$this->sql , 'data'=>$this->data];
         } 



                                




          
          

      }
