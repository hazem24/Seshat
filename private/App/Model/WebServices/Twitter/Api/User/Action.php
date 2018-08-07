<?php
    namespace App\Model\WebServices\Twitter\Api\User;
    use App\Model\WebServices\Twitter\Api\AbstractAction;



     /**
        *Class Action Responsable For Do Actions in Twitter Users. 
    */
    Class Action extends AbstractAction
    {
            
    /**
    * @method follow follow specfic user in twitter provided by {{ user_name }}. POST  https://api.twitter.com/1.1/friendships/create.json.
    * @return object | array.
    */
    protected function follow (string $user_id){
        $follow = $this->connection->post("friendships/create",['user_id'=>$user_id]);
        return $this->getResponse($follow);
    }
              
    /**
    * @method unfollow unfollow specific user in twitter provided by {{ user_name }}. POST https://api.twitter.com/1.1/friendships/destroy.json.
    * @return object | array. 
    */
    protected function unfollow (string $user_id){
        $unfollow = $this->connection->post("friendships/destroy",['user_id'=>$user_id]);
        return $this->getResponse($unfollow);
    }
    }