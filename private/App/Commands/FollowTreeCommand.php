<?php
    namespace App\Commands;
    use App\Model\App\Seshat\FollowTreeModel;

    /**
    *This Class  has all logic for follow tree feature.
    */

    Class FollowTreeCommand extends BaseCommand
    {
        private static $followTreeModel = null;

        /**
        * Create New Tree logic.
        * @method createNewTreeLogic.
        * @return true in success || array of error in fail.
        */
        protected function createNewTreeLogic(array $treeData){
            /**
             * 1 - check number of trees users is created if less than 3 so user can create it else max is 3.
                * also check if the name of tree exists in specific media if true error returned false create tree.
             * 2 - if user want to follow users in his/her tree must be add to subscribed_in_tree table as user.
             */
            $numberOfTreesUserHas = $this->getModel()->countUserTreesCreated($treeData['user_id'],$treeData['media']);
            $nameExists           = $this->getModel()->nameExists( $treeData['name'] , $treeData['media'] );
            if ( $numberOfTreesUserHas !== false ){
                if( $numberOfTreesUserHas == 3 ){
                    $response = ['error'=>MAX_TREES_REACH];
                }else if ( $nameExists !== false ) {
                    $response = ['error'=>TREE_NAME_EXISTS];
                }else{
                    //save new tree.
                    $response = $this->saveTree( $treeData );
                }
            }else{
                $response = ['error'=>GLOBAL_ERROR];
            }
            return $response;
        }

        /**
         * This method has all logic of editMaxAccounts.
         * @method editMaxAccounts.
         * @return array.
         */
        protected function updateTree( array $data ){
            $response = $this->getModel()->updateTree($data['user_id'] , $data['tree_id'] , $data['description']);
            if ( $response == true ){
                $response = ['treeUpdated'=>TREE_UPDATED];
            }else{
                $response = ['error'=>GLOBAL_ERROR];
            }
            return $response;    
        }
        /**
         * This method has all logic of return user trees.
         * @method getUserTrees.
         * @return array.
         */

        protected function getUserTrees( array $user_data ){
            $response = $this->getModel()->showUserTrees($user_data['user_id']);
            if ( !is_array( $response ) ){
                return  ['AppError'=>GLOBAL_ERROR];
            }
            return $response;
        } 
        
        
        /**
         * This method delete a tree with it's content.
         * @method deleteTree.
         * @return array.
         */
        protected function deleteTree( array $data ){
            $response = $this->getModel()->deleteTree( $data['user_id'] , $data['tree_id'] );
            if ( $response == true ) {
                $response = ['treeDeleted'=>TREE_DELETED];
            }else{
                $response = ['AppError'=>GLOBAL_ERROR];
            }
            return $response;
        }
        /**
         * This method exit user from specific tree.
         * @method exitTree.
         * @return array.
         */
        protected function exitTree( array $data ){
            $response = $this->getModel()->exitTree( $data['user_id'] , $data['tree_id'] );
            if ( $response == true ) {
                $response = ['exit'=>TREE_EXIT];
            }else{
                $response = ['AppError'=>GLOBAL_ERROR];
            }
            return $response;
        }

        /**
         * This method return data of tree .. tree is specifiy by name.
         * @property tree_name.
         * @method show.
         * @return array.
         */
        protected function show ( array $data ){
            $tree_data = $this->getModel()->show( $data['tree_name'] );
            if ( $tree_data  === false){
                $tree_data = ['AppError'=>true];
            }else{
                if ( $data['user_id'] !== false ){
                    if ( !empty( $tree_data['tree_data'] ) ){
                        $user_subscriber = $this->getModel()->alreadySub( $data['user_id'] , $tree_data['tree_data'][0]['id'] );
                        if ($user_subscriber){
                            $tree_data = array_merge( $tree_data , ['member'=>true] );    
                        }else{
                            $tree_data = array_merge( $tree_data , ['member'=>false] );
                        }
                    }
                }
            }
            return $tree_data;
        }

        /**
         * This method used to show all trees in seshat.
         * @method showAll.
         * @return array | false in failure.
         */
        public function showAll(){
            $allTrees = $this->getModel()->showAll();
            if ( is_array( $allTrees ) === false ){
                $allTrees = ['AppError'=>true];
            }else{
                $allTrees = ['trees'=>$allTrees];
            }
            return $allTrees;
        }

        /**
         * This method responsable to add new user to specfic tree.
         * @method joinTree.
         * @return array.
         */
        protected function joinTree( array $data ){
              /**
                 * 1 - check if user not joined 3 trees. --Done.
                    * if join 3 tree error return Max reach else --Done.
                    *1.1 - check if user joined this tree before. --Done.
                        *if joined cannot join again. --Done.
                        *if not join it. --Done.
                    *1.2 - check if the tree reach the limit. --Done.
                        *if yes error return.--Done.
                        *if no nothing return.--Done.    
                 *
                */
            $counter = $this->getModel()->countSubTrees( $data['user_id'] );  
            if ( $counter !== false ){
                if ( $counter < 3  ){
                    $already_subscribers = $this->getModel()->alreadySub( $data['user_id'] , $data['tree_id'] );
                    if ( $already_subscribers === false ){
                        $subscribers  = $this->getModel()->countSubInTree( $data['tree_id'] );
                        $tree_data    = $this->getModel()->getTreeById( $data['tree_id'] );
                        $max_accounts = (int) $tree_data['max_accounts'];
                        if ( $subscribers === false || $tree_data === false ){
                            $response = ['AppError'=>true];
                        }else if ( $subscribers >= $data['limit'][$max_accounts] ){
                            $response = ['error'=>REACH_LIMIT];
                        }else{
                            $response = $this->getModel()->joinTree( $data['user_id'] , $data['tree_id'] , json_encode($data['tokens']) );
                            if ( $response === true ){
                                $response = ['joined'=>JOINED_TREE];
                            }else{
                                $response = ['AppError'=>true];
                            }    
                        }
                    }else{
                        $response = ['error'=>ALREADY_JOINED];
                    }
                }else{
                    $response = ['error'=>MAX_SUB_REACH];
                }
            }else{
                $response = ['AppError'=>true];
            }
            return $response;
        }
    
        private function saveTree( array $treeData ){
            ( isset($treeData['id'] )) ? $this->getModel()->setProperty('id',$treeData['id']) : $this->getModel()->setProperty('id',null);
            $followTreeModel = $this->getModel();
            $followTreeModel->setProperty('name',$treeData['name']);
            $followTreeModel->setProperty('description',$treeData['description']);
            $followTreeModel->setProperty('max_accounts',$treeData['max_accounts']);
            $followTreeModel->setProperty('user_id',$treeData['user_id']);
            $followTreeModel->setProperty('tokens',$treeData['tokens']);
            $followTreeModel->setProperty('tree_media',$treeData['media']);
            $create = $followTreeModel->save();
            return ( $create['created'] === true) ? ['tree_id'=>$create['tree_id'],'tree_created'=>TREE_CREATED]  : ['error'=>GLOBAL_ERROR];
        }

        private function getModel():FollowTreeModel{
            if ( is_null( self::$followTreeModel ) ){
                self::$followTreeModel = new FollowTreeModel;
            }
            return self::$followTreeModel;
        }

    }
