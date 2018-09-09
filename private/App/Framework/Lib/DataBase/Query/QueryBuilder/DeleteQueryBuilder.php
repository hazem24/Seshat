<?php

      namespace Framework\Lib\DataBase\Query\QueryBuilder;
      use Framework\Exception\DbException as DbException;
      use Framework\Lib\DataBase\Query\QueryBuilder\AbstractQueryBuilder as AbstractQueryBuilder;
      use Framework\Helper\ArrayHelper as ArrayHelper;

      class DeleteQueryBuilder extends AbstractQueryBuilder
      {
         protected $sql = "DELETE ";

         protected $multiDelete= [];

           
          /**
          *@param data That Carry The Data Which Wll Delete By Conditions Where col = data from this @param  @parent
          *@param targetTable That Will Create Delete Some Data From It @parent
          *@param columns That Carry The Specific Col To Delete Value From It  @parent
          */

          /**
          *Client
          * delete('<table>' ,['id','user'])->where(<conditions>)->[join() Optional To Delete From more than one table];
          *@param table string
          *DELETE users,about FROM users  
          *LEFT JOIN about ON users.id = about.id  where users.id=30 AND about.id = 30
          */

          public function delete(string $targetTable , array $moreTableToDeleteFromIt = []):DeleteQueryBuilder{
            $this->targetTable = $targetTable;
            if(!empty($moreTableToDeleteFromIt)){
                  $this->multiDelete = $moreTableToDeleteFromIt;
            }
            return $this;
          }

          public function createQuery():array{
              if(!empty($this->multiDelete)){
                    $this->sql .= implode(',',$this->multiDelete);
              }
              
              $this->sql .= " FROM " ;
              parent::createQuery();
              if(!empty($this->multiDelete) && is_null($this->join)){
                  throw new DbException("<br> Syntax Error In Mysql Query You Must Provide Join Method To Delete From More Than One Table @class " . __CLASS__);
                        
              }
              if(!is_null($this->join)){
                  $this->sql .= implode(" ",$this->join);
              }
              if(!isset($this->where) || empty($this->where)){
                  throw new DbException("<br>Warning  Dangrous You Try To Delete All Data In This $this->targetTable Please Create Where Conditions To Delete Specfic Data @class " . __CLASS__);
              }
                  $this->sql .= $this->where;
              if(empty($this->multiDelete)&&isset($this->orderBy) && !empty($this->orderBy)){
                  $this->sql .= $this->orderBy;     
              }else if(!empty($this->multiDelete) && isset($this->orderBy) && !empty($this->orderBy))
              {
                  throw new DbException("<br> Syntax Error In Mysql Query You Can Not Provide orderBy Method When Delete From More Than One Table @class " . __CLASS__);
              }

              if(empty($this->multiDelete)&&isset($this->limit) && !empty($this->limit)){
                  $this->sql .= $this->limit;
              }else if(!empty($this->multiDelete) && isset($this->orderBy) && !empty($this->orderBy))
              {
                throw new DbException("<br> Syntax Error In Mysql Query You Can Not Provide Limit Method When Delete From More Than One Table @class " . __CLASS__);
              }
                  return ['query'=>sprintf($this->sql) , 'data'=>$this->data];
          }


      }
