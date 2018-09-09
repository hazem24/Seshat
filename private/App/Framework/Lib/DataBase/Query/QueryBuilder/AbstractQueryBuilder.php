<?php

    namespace Framework\Lib\DataBase\Query\QueryBuilder;
    use Framework\ConstructorClass as ConstructorClass;
    use Framework\Lib\DataBase\Query\QueryBuilder\SelectQueryBuilder as SelectQueryBuilder;
    use Framework\Helper\ArrayHelper as ArrayHelper;
    use Framework\Exception\DbException as DbException;



    /**
    *@class This Class Provide Abstract Struction For Any Kind Of DataBase Query
    *@created at 25/05/2017 at 02:20 Am
    */

    Abstract Class AbstractQueryBuilder extends ConstructorClass
    {
        protected $sql = '';
        /**
        *@property targetTable Table In Which Target In Query Ex:- select * from <targetTable>
        */
        protected $targetTable;
        protected $columns;
        protected $limit;
        protected $orderBy;
        protected $union;
        protected $unionAll;


        /**
        *@property data array 
        *This Is The Data Which Will Use Inside Prepare Statement 
        */
        protected $data = [];
        protected static $operators = ['LOGIC'=>['AND','OR'],
                                     'MATHMATICAL'=>['>','<','=','>=','<=','!='],
                                     'SEARCH'=>['LIKE']];
        protected static $orderByList = ['DESC' , 'ASC'];

        /**
        *@property where used in select and update Only
        */
        protected $where;
        protected $join;
        protected static $joinAllowed = ['INNER','LEFT','RIGHT'];  





        /**
        *@method where used in select and update and delete Only
        */
        public function where(array $conditions = null):AbstractQueryBuilder{
            $conditions = $this->arrayFilter($conditions);
            if(empty($conditions) || is_null($conditions)){
                  throw new DbException("You Can Not Create Where Statement With (Empty  || Null) Condition @Class " .__CLASS__);
            }
            $conditional  = ArrayHelper::filterDataWithArray($conditions);// Return Tables Of Conditions as [0] Index And Conditional Values As [1] Index
            $this->data   = $conditional[1]; // Data Which Will Uses Inside Prepare Statement
            $this->where  = ' WHERE ' . implode(' ',$conditional[0]);
            return $this;
          }
         
        public function createQuery():array{
            if(empty($this->targetTable)){
                  throw new DbException("<br>Syntax Error You Must Add Table'(s) That You Want To Do Query On It Data  On It @Class" . __CLASS__);          
            }
            $this->sql .= $this->targetTable;
            return [];
        }

          /**
          *@method join param $tableCol1 Must Be Add In This Way <table>.colName
          *For More Join Tables Logic This Method Must Be Called Multiple Times
          */

          public function join(string $table2 , string $tableCol1 , string $col2 , string $typeOfJoin = 'LEFT'):AbstractQueryBuilder{
                   if(!in_array(strtoupper($typeOfJoin), self::$joinAllowed)){
                        throw new DbException("UnAllowed Type Of Join Passed Allowed This Only ***(". implode(',', self::$joinAllowed) . ')*** ' . $typeOfJoin . ' Given @class '.__CLASS__);     
                   }
                   $arguments = func_get_args();
                   $filter = $this->arrayFilter($arguments);
                   if(!$this->dataCount($arguments , $filter)){
                        throw new DbException("You Put Empty Arguments @join Method Please Fixed It @class " .__CLASS__);     
                   }
                  $this->join[] = " $typeOfJoin JOIN $table2 ON $tableCol1 = $table2.$col2 ";
                  return $this;
          }
             public function limit(int $limit = 10 , int $offset = 0):AbstractQueryBuilder{
                  $offset = ($offset >= 0) ?  " OFFSET $offset " : '';
                  $limit  = ($limit > 0 ) ? " LIMIT $limit "     : '';
                  $this->limit = $limit . $offset;
                  return $this;
          }

          public function orderBy(array $orderData):AbstractQueryBuilder{
                  $orderStatement = ' ORDER BY '; // Start Query Add For orderBy Syntax
                  $orderData = $this->arrayFilter($orderData);
                  if(empty($orderData)){
                        throw new DbException("Error Your Order By Data Empty || Not Completed @Class " . __CLASS__ );         
                  }
                  $this->orderBy  .= $orderStatement;
                  $tableOrderBy = array_keys($orderData);
                  $countData = count($tableOrderBy);
                  foreach ($orderData as $col => $order) {
                        if(!in_array(strtoupper($order) , self::$orderByList)){
                              throw new DbException("Error Order Must Be One Of The Following  **( " . implode(',',self::$orderBy) .' )** Wrong Syntax For Mysql Given **('. $order . ")** @Class  " . __CLASS__);
                        }
                        $this->orderBy .= "$col $order ";
                        if($tableOrderBy[$countData - 1] !== $col){
                              $this->orderBy .= ", ";
                        }
                  }
                  return $this;
          }

          public function union( string $unionQuery){
                 if (!empty( $unionQuery )){
                        $this->union =  ' UNION '.$unionQuery;
                        return $this;
                 }else{
                        throw new DbException("Error you must add the union query to use union method.");
                 } 
          }

          public function unionAll( string $unionAllQuery ){
                  if (!empty( $unionAllQuery )){
                        $this->unionAll =  ' UNION All '.$unionAllQuery;
                        return $this;
                  }else{
                        throw new DbException("Error you must add the union All query to use union method.");
                  }  
          }

        /**
         *@method This Method Provide Automatic Solution For Place Holder
         *return @number of placeholder That $sql Need  ? , ? , ?  , ? ,  ?n
         *@used   In Insert Query Builder
         */
         protected function placeHolder(int $count = 0):string{
                $placeHolder = ($count > 0) ? array_fill(1 , $count , '?') : '';
                return implode(',',$placeHolder);
         }

         protected function dataCount(array $data , array $columns):bool{
                return (count($data) == count($columns))  ? true : false;
         }

         /**
         *@method arraFilter Need More Logic In Refactor 
         *I Must Integrate ArrayHelper Inside It 
         */
         protected function arrayFilter(array $array){
             return array_filter($array);
         }                            

    }