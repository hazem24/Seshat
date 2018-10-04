<?php 

    namespace App\DomainMapper\Seshat;
    use Framework\Registry as Registry;
    use Framework\Shared\Model;
    use Framework\Lib\DataBase\Query\QueryBuilder\SelectQueryBuilder as select;
    use Framework\Lib\DataBase\Query\QueryBuilder\UpdateQueryBuilder as update;
    use Framework\Lib\DataBase\Query\QueryBuilder\InsertQueryBuilder as insert;
    use Framework\Lib\DataBase\Query\QueryBuilder\DeleteQueryBuilder as delete;
    use App\Model\App\Seshat\FollowTreeModel;
    use Framework\Lib\DataBase\DataMapper\Collections\AbstractGeneratorCollection as Collection;

 
        /**
        *This Class Is Provide Access Of FollowTree Data In DataBase. 
        */
        Class FollowTreeMapper extends BaseMapper
        {
            private $tableA = 'follow_tree';
            private $tableB = 'subscribed_in_tree';
            private $foreign_key = 'tree_id';
            private $columnsTableA = ['primaryKey'=>'follow_tree.id','name','description','max_accounts','user_id','tree_media','created_at'];
            private $columnsTableB = ['subscribed_in_tree.id as sub_id','tree_id','sub_user_id','tokens','join_at'];
            private $modelName = 'FollowTreeModel';

            /**
             * check if name of new tree exists or not.
             */
            public function nameExists( string $name , int $media ){
                $stm = new select;
                $stm = $stm->select()->from( $this->tableA )->where([ $this->columnsTableA[0] . ' = ? &&'=>$name , 
                $this->columnsTableA[4] . ' = ?' =>$media])->createQuery();
                $nameExists = $this->pdo->prepare( $stm['query'] );
                $this->bindParamCreator( 2 , $nameExists , $stm['data'] );
                $nameExists->execute();
                $nameExists = $nameExists->fetch();
                return $nameExists;
            }
           
            /**
             * how many trees user created in specific media.
             */
            public function countUserTreesCreated ( int $user_id , int $treeMedia ) {
                $stm = new select;
                $stm = $stm->select()->from( $this->tableA )->where([ $this->columnsTableA[3] . ' = ? &&'=>$user_id , 
                $this->columnsTableA[4] . ' = ?' =>$treeMedia])->createQuery();
                $count = $this->pdo->prepare( $stm['query'] );
                $this->bindParamCreator( 2 , $count , $stm['data'] );
                $count->execute();
                return $count->fetchAll();//false in failure .. empty array in zero set.
            }

            public function userTrees( int $user_id ){
                $sub_trees    = $this->subTrees( $user_id );
                $tree_created = $this->treeCreated( $user_id );
                if ( $sub_trees === false || $tree_created === false ){
                    return false;
                }else{
                    //get accounts enter specific tree.
                    if (!empty( $sub_trees )){
                        foreach ($sub_trees as $key => $tree) {
                            $sub_trees[$key]['subscribers'] = (int) $this->countSubInTree( $tree['id'] );
                        }
                    }
                    if (!empty( $tree_created )){
                        foreach ($tree_created as $key => $tree) {
                            $tree_created[$key]['subscribers'] = (int) ($this->countSubInTree( $tree['id'] ));
                        }
                    }
                    return ['sub_trees'=>$sub_trees , 'created_trees'=>$tree_created];
                }
            }
            /**
             * this method return tree created by user.
             * @method treeCreated.
             * @return array | false in failure.
             */
            public function treeCreated( int $user_id ){
                $tree_created   = new select;
                $tree_created = $tree_created->select()->from( $this->tableA )
                ->where([$this->columnsTableA[3] . ' = ? ' =>$user_id])->createQuery(); //trees created by user.
                $tree_created = $this->pdo->prepare( $tree_created['query'] );
                $this->bindParamCreator(1 , $tree_created , [$user_id]);
                $tree_created->execute();
                $tree_created = $tree_created->fetchAll(\PDO::FETCH_ASSOC);
                
                return $tree_created;
            }

            /**
             * this method return tree data.
             * @method show.
             * @return array | false in failure.
             */
            public function show ( string $tree_name ){
                $stm = new select;
                $stm = $stm->select( array_merge( $this->columnsTableA , $this->columnsTableB  , ['user.screen_name as subscriber']) )
                ->from( $this->tableA )
                ->join( $this->tableB , $this->columnsTableA['primaryKey'] , $this->columnsTableB[1] )
                ->join( "user" , $this->columnsTableB[2] , "id")->limit(100,0)->orderBy([$this->tableB . '.id'=>'DESC'])
                ->where([ $this->columnsTableA[0] . ' = ?'=> $tree_name])->createQuery();
                $show = $this->pdo->prepare( $stm['query'] );
                $this->bindParamCreator( 1 , $show , [$tree_name] );
                $show->execute();
                $show = $show->fetchAll(\PDO::FETCH_ASSOC);
                if ( is_array( $show ) ){
                    $show = ['tree_data'=>$show];
                }
                return $show;
            }

            public function getTreeSubscribersById( int $tree_id ){
                $redis          = $this->redis();
                $tree_data_item = $redis->getItem( "tree_data" . $tree_id );
                if ( is_null( $redis->get( $tree_data_item ) ) === true ){
                    $stm       = new select;
                    $stm       = $stm->select()->from( $this->tableB )->where( [ $this->foreign_key . " = ?" => $tree_id ] )
                    ->join( "user" , $this->columnsTableB[2] , "id")
                    ->join( $this->tableA , $this->columnsTableB[1] , 'id')->createQuery();
                    $tree_data = $this->pdo->prepare( $stm['query'] );
                    $this->bindParamCreator( 1 , $tree_data , $stm['data'] );
                    $tree_data->execute();
                    $tree_data  = $tree_data->fetchAll(\PDO::FETCH_ASSOC);
                    if ( $tree_data !== false ){
                        $redis->set( $tree_data_item  , $tree_data , 84600); //24 hr.
                    }else{
                        // error happen in connection with DB.
                        $redis->set( $tree_data_item  , [] , 3600); //1 hr.
                    }
                }else{
                    $tree_data = $redis->get( $tree_data_item );
                }
                return $tree_data;
            }

            /**
             * get all trees in database but limited for 100 trees only.
             * @method showAll.
             * @return array | false in failure.
             */
            public function showAll(){
                $stm = new select;
                $stm = $stm->select()->from( $this->tableA )->limit(100,0)->orderBy( [$this->columnsTableA['primaryKey'] => 'DESC'] )->createQuery();
                $showAll = $this->pdo->prepare( $stm['query'] );
                $showAll->execute();
                $showAll = $showAll->fetchAll(\PDO::FETCH_ASSOC);
                return $showAll;
            }

            public function getTreeById( int $tree_id ){
                $stm = new select;
                $stm = $stm->select()->from( $this->tableA )->where([ $this->columnsTableA['primaryKey'] . ' = ?'=> $tree_id])->createQuery();
                $show = $this->pdo->prepare( $stm['query'] );
                $this->bindParamCreator( 1 , $show , [$tree_id] );
                $show->execute();
                $show = $show->fetch();
                if ( is_array( $show ) ){
                    $show = $show;
                }
                return $show;
            }

            /**
             * this method join user to tree.
             * @method joinTree.
             * @return bool.
             */
            public function joinTree( int $user_id , int $tree_id , string $tokens ){
                $insert = new insert;
                $insert = $insert->insert($this->tableB,[$this->columnsTableB[1]=>$tree_id,$this->columnsTableB[2]=>$user_id,'tokens'=>$tokens])->createQuery();
                $join   = $this->pdo->prepare( $insert['query'] );
                $this->bindParamCreator( 3 , $join  , $insert['data'] );
                $join->execute();
                if( $join->rowCount() > 0 ){
                    $joined = true;
                }else{
                    $joined = false;
                }
                return $joined;
            }
            /**
             * return the status of user if user is a member in specific tree.
             * @method alreadySub.
             * @return bool.
             */
            public function alreadySub ( int $user_id , int $tree_id ){
                $stm = new select;
                $stm = $stm->select()->from($this->tableB)
                ->where( [ $this->columnsTableB[1] .' =? && '=> $tree_id , $this->columnsTableB[2] .' = ?' => $user_id  ] )
                ->createQuery();
                $alreadySub = $this->pdo->prepare( $stm['query'] );
                $this->bindParamCreator( 2 , $alreadySub , $stm['data'] );
                $alreadySub->execute();
                $alreadySub = $alreadySub->fetch( \PDO::FETCH_ASSOC );
                
                if ( $alreadySub !== false ){
                    $alreadySub = true;
                }
                return $alreadySub;
            }

            /**
             * this method count trees user sub into it.
             * @method countSubTrees.
             * @return int | false in failure.
             */
            public function countSubTrees( int $user_id ){
                $stm = new select;
                $stm = $stm->select(["count(".$this->columnsTableB[2].") as counter"])
                ->from( $this->tableB )
                ->join( $this->tableA , $this->columnsTableB[1] , 'id' )
                ->where([$this->columnsTableB[2] . " = ? && "=>$user_id , $this->columnsTableA[3] . ' != ?'=>$user_id])->createQuery();
                $counter = $this->pdo->prepare( $stm['query'] );
                $this->bindParamCreator( 2 , $counter , $stm['data'] );
                $counter->execute();
                $counter = $counter->fetchAll( \PDO::FETCH_ASSOC );

                if ( is_array( $counter ) ){
                    $counter = (int) $counter[0]['counter'];
                }
                return $counter;
            }
            /**
             * this method return the trees user sub into it.
             * @method subTrees.
             * @return array | false in failure.
             */
            public function subTrees( int $user_id ){
                $sub_trees = new select;
                $sub_trees = $sub_trees->select(array_merge( $this->columnsTableA , $this->columnsTableB ,['user.screen_name as owner'] ))
                ->from( $this->tableA )
                ->join($this->tableB , $this->columnsTableA['primaryKey'] ,$this->foreign_key)
                ->join( "user" , "follow_tree.user_id" , "id")->
                where([$this->columnsTableB[2] . ' = ? &&'=>$user_id , $this->columnsTableA[3] . ' != ? '=>$user_id])
                ->createQuery();//get user trees which user subscribed only.
                $sub_trees = $this->pdo->prepare( $sub_trees['query'] );
                $this->bindParamCreator(2 , $sub_trees , [$user_id , $user_id]);
                $sub_trees->execute();
                $sub_trees = $sub_trees->fetchAll(\PDO::FETCH_ASSOC);
                return $sub_trees;
            }

            /**
             * This method count subscribers in specific tree.
             * @method countSubInTree.
             * @return int.
             */
            public function countSubInTree( int $tree_id ){
                $stm = new select;
                $stm = $stm->select(["count(" . $this->columnsTableB[2] . ") as subscribers"])->from( $this->tableA )
                ->join($this->tableB , $this->columnsTableA['primaryKey'] , $this->columnsTableB[1] )
                ->where([$this->columnsTableB[1] . ' = ? ' =>$tree_id])->createQuery(); //trees created by user.
                $counter = $this->pdo->prepare( $stm['query'] );
                $this->bindParamCreator(1 , $counter , [$tree_id]);
                $counter->execute();
                $counter = $counter->fetchAll(\PDO::FETCH_ASSOC);
                if (is_array( $counter ) && isset( $counter[0]['subscribers'] )){
                    return $counter[0]['subscribers'];
                }
                return false; // error happen. 
            }

            public function deleteTree( int $user_id , int $tree_id ){
                $delete = new delete;
                $delete = $delete->delete( $this->tableA , [ $this->tableB , $this->tableA  ] )
                ->join($this->tableB , $this->columnsTableA['primaryKey'] , $this->columnsTableB[1])
                ->where([$this->columnsTableA['primaryKey'] . ' =  ? &&' => $tree_id , $this->columnsTableA[3] . ' = ? ' => $user_id])
                ->createQuery();
                $deleteTree = $this->pdo->prepare( $delete['query'] );
                $this->bindParamCreator( 2 , $deleteTree  , $delete['data'] );
                $deleteTree->execute();
                if ( $deleteTree->rowCount() > 0 ){
                    return true;
                }
                return false;
            }

            public function exitTree( int $user_id , int $tree_id ){
                $delete = new delete;
                $delete = $delete->delete( $this->tableB )
                ->where([$this->columnsTableB[1] . ' =  ? &&' => $tree_id , $this->columnsTableB[2] . ' = ? ' => $user_id])
                ->createQuery();
                $exitTree = $this->pdo->prepare( $delete['query'] );
                $this->bindParamCreator( 2 , $exitTree  , $delete['data'] );
                $exitTree->execute();
                if ( $exitTree->rowCount() > 0 ){
                    return true;
                }
                return false;
            }
            /**
             * This method save ( insert or update ) in 'tableA' only.
             * @method save.
             * @return bool.
             */
            public function save( FollowTreeModel $treeModel ){
                return $this->doSave( $treeModel );
            }
            
            protected function doSave( Model $model ){
                if (is_null( $model->getProperty('id') )){
                    try{
                        //insert process.
                        $this->pdo->beginTransaction();
                        $insert = new insert;
                        $insert = $insert->insert( $this->tableA , ['name'=>$model->getProperty('name'),
                        'description'=>$model->getProperty('description'),'max_accounts'=>$model->getProperty('max_accounts'),
                        'user_id'=>$model->getProperty('user_id'),'tree_media'=>$model->getProperty('tree_media')])->createQuery();
                        $newTree = $this->pdo->prepare( $insert['query'] );
                        $this->bindParamCreator( 5 , $newTree , $insert['data'] );
                        $newTree->execute();
                        $treeId = (int) $this->pdo->lastInsertId();
                        //tree created successfully.
                        if( $model->getProperty('tokens') !== false ){// owner want to follow users in his/her tree.
                            
                            $insert = new insert;
                            $insert = $insert->insert( $this->tableB , [$this->columnsTableB[1]=>$treeId,$this->columnsTableB[2]=>$model->getProperty('user_id'),
                            $this->columnsTableB[3]=>json_encode($model->getProperty('tokens'))])->createQuery();
                            $followMyUsers = $this->pdo->prepare( $insert['query'] );
                            $this->bindParamCreator( 3 , $followMyUsers , $insert['data'] );
                            $followMyUsers->execute();
                        }    
                        return ['created'=>$this->pdo->commit(),'tree_id'=>$treeId];
                    }catch(\PDOException $pdoEx){
                        $this->pdo->rollBack();
                        return false;
                    }
                }else{
                    //update process.
                    $update = new update;
                    $update = $update->update( $this->tableA , [$this->columnsTableA[1]=>$model->getProperty('description')] )
                    ->where([$this->columnsTableA[3] . ' = ? &&' => $model->getProperty('user_id') , 
                    $this->columnsTableA['primaryKey'] . " = ? " => $model->getProperty('id')])->createQuery();
                    $updateTree = $this->pdo->prepare( $update['query'] );
                    $this->bindParamCreator( 3 , $updateTree , array_merge( $update['updateto'] , $update['data'] ) );
                    $updateTree->execute();
                    return (bool) $updateTree->rowCount();
                }
            }
            /**
             * i Can Use PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE Instead Of This.
             */
            protected function createObject(array $fields):Model{
            }
            protected function getCollection(array $raw): Collection{

            }
            protected function selectAllStatement():\PDOStatement{

            }
        }
