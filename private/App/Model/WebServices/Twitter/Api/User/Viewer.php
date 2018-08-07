<?php
    namespace App\Model\WebServices\Twitter\Api\User;
    use App\Model\WebServices\Twitter\Api\AbstractAction;

    /**
    *Class Viewer Responsable For Read Data From Twitter Api about user scope And Return Response To User In Seshat.
    */
Class Viewer extends AbstractAction
{

        /**
        * @method getUser. GET https://api.twitter.com/1.1/users/show.json.
        * @return array.
        */
        public function getUser ( array $parameters = []) {
            $getUser = $this->connection->get("users/show",array_merge(['include_entities' => "true"],$parameters['by']));
            return $this->getResponse($getUser);
        }
        /**
        * @method getFollowersList. GET https://api.twitter.com/1.1/followers/list.json.
        * @return array.
        */
        public function getFollowersList( array $parameters = [] ){
            $followersList = $this->connection->get('followers/list',$parameters['by']);
            return $this->getResponse($followersList); 
        }

        /**
         * @method getFriendsList. GET https://api.twitter.com/1.1/friends/list.json.
         * @return array.
         */
        public function getFriendsList ( array $parameters = [] ) {
            $followersList = $this->connection->get('friends/list',['user_id'=>$parameters['user_id'],'count'=>'50','cursor'=>$parameters['cursor']]);
            return $this->getResponse($followersList); 
        }
        /**
        * @method searchUsers. GET https://api.twitter.com/1.1/users/search.json.
        * @return array. 
        */
        public function searchUsers ( array $parameters = [] ) {
            $searchUsers = $this->connection->get('users/search',['q'=>$parameters['search']]);
            return $this->getResponse( $searchUsers );
        }
        /**
         * @method getFollowersIds. GET https://api.twitter.com/1.1/followers/ids.json.
         * @return array.
        */   
        public function getFollowersIds ( array $parameters = [] ) {
            $getFollowersIds = $this->connection->get('followers/ids',['user_id'=>$parameters['user_id'] , 'crusor'=>$parameters['crusor']]);
            return $this->getResponse( $getFollowersIds );
        }

        /**
         * @method getFriendsIds. GET https://api.twitter.com/1.1/friends/ids.json.
         * @return array. 
         */
        public function getFriendsIds ( array $parameters = [] ) {
            $getFriendsIds = $this->connection->get('friends/ids',['user_id'=>$parameters['user_id'] , 'crusor'=>$parameters['crusor']]);
            return $this->getResponse( $getFriendsIds ); 
        }
        /**
         * @method lookup. GET https://api.twitter.com/1.1/users/lookup.json.
         * @return array.
         */
        public function lookup ( array $parameters = [] ) {
            $lookup = $this->connection->get('users/lookup',['user_id'=>$parameters['user_id']]);
            return $this->getResponse( $lookup ); 
        }
        /**
         * @method checkFriends. GET https://api.twitter.com/1.1/friendships/show.json.
         * @return array.
         */
        public function checkFriends( array $parameters = [] ){
            $checkFriends = $this->connection->get('friendships/show',$parameters);
            return $this->getResponse( $checkFriends );
        }

}