<?php
    namespace App\Model\App\Seshat;
    use App\Model\BaseModel;
    use App\DomainMapper\Seshat\FollowTreeMapper;


    /**
     * This class is model class for FollowTree Feature.
     */

    Class FollowTreeModel extends BaseModel
    {
        /**
         * Table follow_tree contents
         */
        protected $id;//tree_id.
        protected $name;
        protected $description;
        protected $max_accounts;
        protected $user_id;//owner of the tree.
        protected $tree_media;//social media in which tree created for.
        protected $created_at;

        /**
         * Table subscribed_in_tree contents.
         */
        protected $sub_id;//id of subscription.
        protected $tree_id;
        protected $sub_user_id;//id of sub user.
        protected $tokens;
        protected $join_at;


        protected $owner;//the owner of the tree uses in sub_tree select in Mapper.


        protected static $followTreeMapper = null;
        /**
         * get all trees user sub or created.
         * @method showUserTrees.
         * @return array.
         */
        public static function showUserTrees ( int $user_id ){
            return self::getFinder()->userTrees( $user_id );    
        }


        /**
         * Edit max accounts trees can have.
         * @method editMaxAccounts.
         * @return bool.
         */
        public function updateTree( int $user_id , int $tree_id , string $description ){
            $this->id = $tree_id;
            $this->user_id = $user_id;
            $this->description = $description;
            
            return self::getFinder()->save( $this );
        }

        /**
         * delete tree and it's contents.
         * @method deleteTree.
         * @return bool.
         */
        public function deleteTree( int $user_id , int $tree_id ){
            return self::getFinder()->deleteTree( $user_id , $tree_id );
        }

        /**
         * exit specific tree.
         * @method exitTree.
         * @return bool.
         */
        public function exitTree( int $user_id , int $tree_id ){
            return self::getFinder()->exitTree( $user_id , $tree_id );
        }

        /**
         * join user to tree.
         * @method joinTree.
         * @return
         */
        public function joinTree( int $user_id , int $tree_id , string $tokens ){
            return self::getFinder()->joinTree( $user_id , $tree_id , $tokens );
        }

        /**
         * this method count trees specific user join.
         * @method countSubTrees.
         * @return int.
         */
        public function countSubTrees( int $user_id ){
            return self::getFinder()->countSubTrees( $user_id );
        }

        /**
         * this method check if user alreadySub in specific tree.
         * @method alreadySub.
         * @return bool.
         */
        public function alreadySub( int $user_id , int $tree_id ){
            return self::getFinder()->alreadySub( $user_id , $tree_id );
        }

        /**
         * this method counts the subscribers in specific tree.
         * @method countSubInTree.
         * @return int | false in failure.
         */
        public function countSubInTree( int $tree_id ){
            return self::getFinder()->countSubInTree( $tree_id );
        }

        /**
         * this method used to get all trees in database.
         * @method showAll.
         * @return array | false in failure.
         */
        public function showAll(){
            return self::getFinder()->showAll();
        }

        /**
         * this method get tree data by id.
         * @method getTreeById
         * @return array | false in failure.
         */
        public function getTreeById( int $tree_id ){
            return self::getFinder()->getTreeById( $tree_id );
        }

        /**
         * get data of specific tree.
         * @method show.
         * @return array | false in failure.
         */
        public function show ( string $tree_name ){
            return self::getFinder()->show( $tree_name );
        }

        public function save(){
            return self::getFinder()->save(  $this );
        }
        /**
         * this method check if choosen tree name is exists in specific media.
         * @method nameExists.
         * @return bool.
         */
        public function nameExists( string $name , int $media ){
            return self::getFinder()->nameExists( $name , $media );
        }

        /**
         * this method get all tree subscribers by id.
         */
        public function getTreeSubscribersById( int $tree_id ){
            return self::getFinder()->getTreeSubscribersById( $tree_id );
        }

        /**
         * this method return how many trees user created.
         * @method countUserTrees.
         * @return int | false in failure.
         */
        public static function countUserTreesCreated( int $user_id , int $media ){
            $count =  self::getFinder()->countUserTreesCreated( $user_id , $media );
            if ( $count !== false ){
                return count($count);
            }
            return false;
        }


        /**
        * @method getFinder Find The Mapper Which Object Related To It.
        * @return Mapper.
        */
        protected static function getFinder(){
            if(is_null(self::$followTreeMapper) === true){
                self::$followTreeMapper = new FollowTreeMapper;
            }
            return self::$followTreeMapper;
        }

    }
    