<?php
    namespace App\Commands;
    use App\System\Notification;
    use App\System\Cron;
    use App\System\Cron\CronException;
    use App\DomainHelper\FastCache;
    use App\DomainHelper\Helper;
    use App\DomainHelper\FrontEndHelper;
    use App\Model\App\Seshat\FollowTreeModel as Tree;
    use App\Model\App\Seshat\TaskModel as Task;
    use App\Model\WebServices\Google\Translate\Translate;


    /**
     * this class responsble for all commands for Cron system.
     */

    Class CronCommand extends BaseCommand
    {
        private $taskModel;

        private $treeModel;

        /**
         * instance of media read class.
         */
        private $mediaReader;
        
        public function execute( array $data = [] ){
            $this->taskModel = new Task;
            return parent::execute($data);
        }
        /**
         * this method route the incoming task to the doing method (Method which cac do the task or cron).
         * @method routeTask.
         * @return
         */
        protected function doTask(array $params){
            $task_id         = $params['task_id'];
            $user_id         = $params['user_id'];
            $mediaSender     = "App\\DomainHelper\\" . $params['media'] . "\\Send";
            if (class_exists($mediaSender)){
                $mediaObject      = new $mediaSender;
                return $this->cronLogic($mediaObject,$task_id,$user_id,strtolower($params['media']));
            }
        }


        private function cronLogic( $mediaObject , int $task_id , int $user_id = 0 , string $media = 'twitter'){
            switch ($task_id) {
                case 1:
                    $cronSystem  = new Cron\Schedule($mediaObject);
                    $cron        = $this->scheduleLogic( $cronSystem );
                    break;
                case 54:
                    $cronSystem      = new Cron\Tree($mediaObject);
                    $this->treeModel = new Tree;
                    $this->mediaReader( $media );
                    // note $user_id that passes to treeCronLogic is tree_id not user_id.
                    $cron        = $this->treeCronLogic( $cronSystem , 54 , $user_id , $media);
                    break;
                case 31:
                    $cronSystem  = new Cron\ControlFollowers($mediaObject);
                    $cron        = $this->controlFollowersCron( $cronSystem , 31 , $user_id , 'nonFollowers' , $media);
                    break;
                case 32:
                    $cronSystem  = new Cron\ControlFollowers($mediaObject);
                    $cron        = $this->controlFollowersCron( $cronSystem , 32 , $user_id , 'fans' , $media);
                    break;
                case 33:
                    $cronSystem  = new Cron\ControlFollowers($mediaObject);
                    $cron        = $this->controlFollowersCron( $cronSystem , 33 , $user_id , 'recentFollowers' , $media);
                    break;
                case 2 :
                    $cronSystem  = new Cron\PostAs($mediaObject);
                    $this->mediaReader( $media );
                    $cron        = $this->postAsCron( $cronSystem , 2 , $user_id , $media );
                    break;    
                default:
                    throw new Cron\CronException("Can't define the wanted cron system.");
                    break;
            }
            return $cron;
        }

        private function scheduleLogic( Cron\AbstractCron $cron ){
            /**
             * 1 - get data from redis db. --Done.
             * 2 - sort posts in which must tweet now by date. --Done.
                * if there's no post close the connection. --Done.
                * if there's a posts post it to the media. --Done.
                * if there's an error log the error to user. --Done.
                * if posts done change the status of post in the DB. --Done.
             */
            $posts = $this->loadPosts();
            if (is_array( $posts ) && !empty($posts)){
                foreach ($posts as $key => $post) {
                    if ($post['expected_finish'] == date('Y-m-d H:i') .':00'){            
                        //Publish post to media now.
                        $cron->data  = $post['details'];
                        $cron->owner = $post['user_id'];
                        $postNow     = $cron->doCron();
                        if (is_array( $postNow ) && array_key_exists('success',$postNow)){
                            $this->notify($post['user_id'] , $post['task_name'] , 2 , 'Task');
                            //change the status of post in the task table , delete tweet from RedisDB.
                            $this->taskModel->id = $post['id'];
                            $this->taskModel->is_finished = 1;
                            $this->taskModel->progress = 100;
                            $this->taskModel->status = 1;
                            $this->taskModel->save();
                        }else{
                            $this->notify($post['user_id'] , $post['task_name'] , 3 , 'Task');
                            //change the status of post in the task table , delete tweet from RedisDB.
                            $this->taskModel->id = $post['id'];
                            $this->taskModel->is_finished = 0;
                            $this->taskModel->progress = 0;
                            $this->taskModel->status = 3;
                            $this->taskModel->save();
                        }
                    }
                }
            }else{
                $return = ['noScheduled'=>true];
            }
            return $return ?? ['finished'=>true]; 
        }


        private function controlFollowersCron( Cron\AbstractCron $cron , int $task_id , int $user_id , string $feature , string $media){
            /**
             * 1 - load the data of task from the dataBase Or Redis.--Done.
                * 1.1 - if the data empty stop the process don't do any thing.--Done.
                * 1.2 - if the data not empty take list of people (From Media) and task id and start the process.--Done.
                * 1.3 - if there's any error update the task in Db and clear it from file cache and stop it.--Done.
                * 1.4 - if every thing is okay update the progress in the dataBase.--Done.
                    *and if finished the task update it and delete it from redisDB.--Done.
             */
            $task_data = $this->loadControlFollowersTask($user_id , $task_id , $media);
            if (is_array( $task_data ) && !empty( $task_data )){
                $cron->data    = $task_data['details'];
                $cron->cron_id = $task_id;
                //load people list.
                $itemName   = "task,$feature,$user_id,$media";
                $list = $this->loadList( $itemName , $feature , $user_id );     
                if (is_array($list)  && isset($list['results']) && empty($list['results']) === false){   
                    $cron->list = $list['results']['users'];
                    $doCron = $cron->doCron();
                    $this->taskModel->id = $task_data['id'];
                    if (is_array( $doCron ) && array_key_exists('success',$doCron)){// -order.
                        /*
                        *check if task finished or not if yes update the task and notify user.--Done.
                        *else update the cache and continue the task.--Done.
                        */
                        $this->taskModel->progress    = (int) (((((int)$cron->data['done'] + (int) $cron->order))/(int)$cron->data['order']) * 100);
                        if ( (count( $cron->list ) - $cron->perTime) <= 0 ){
                            //task finished.
                            $this->notify($task_data['user_id'] , $task_data['task_name'] , 2 , 'Task');
                            //update cache.
                            $this->updateCached( $itemName , true);
                            $this->updateCached( "$feature,$user_id" , true);
                            $this->updateCached( "taskID=$task_id,userID=$user_id,media=$media" , true , [] , 'redis');
                            $this->taskModel->is_finished = 1;
                            $this->taskModel->status      = 1;// success.
                        }else{
                            //not finished.
                            $task_data['details']         = json_decode($task_data['details'] , true );
                            $task_data['details']['done'] = $task_data['details']['done'] + $cron->perTime;// increment the done proccess.
                            $task_data['details']         = json_encode( $task_data['details'] );
                            $reminingList = array_slice( $cron->list , $cron->perTime);
                            //update cache.
                            $this->updateCached( $itemName , false , ['results'=>['users'=>$reminingList , 'type'=>'users','from'=>$media]]);
                            $this->updateCached( "$feature,$user_id" , false ,  ['results'=>['users'=>$reminingList , 'type'=>'users','from'=>$media]]);
                            $this->updateCached( "taskID=$task_id,userID=$user_id,media=$media" , false , $task_data, 'redis' );
                            $this->taskModel->is_finished = 0;
                            $this->taskModel->status      = 0;
                        }
                    }else{
                        //failure stop the task and update it's status and notify user.
                        $this->notify($task_data['user_id'] , $task_data['task_name'] , 3 , 'Task');
                        $this->updateCached( "taskID=$task_id,userID=$user_id,media=$media" , true , [] , 'redis');
                        $this->taskModel->is_finished = 1;
                        $this->taskModel->status      = 2;// fail.
                    }
                    //update task.
                    $this->taskModel->save();
                }
            }else{
                $return = ["noControlFollowersFor(user_id:$user_id,media:$media)"=>true];
            }
            return $return ?? ['finished'=>true]; 
        }

        /**
         * post as feature.
         * @note more logic details in the file.
         * @method postAsCron.
         * @return array.
         */
        private function postAsCron( Cron\AbstractCron $cron , int $task_id , int $user_id , string $media){
            /**
             * 1 - get all data of tweet as that user have from DB or redisDB.--Done.
                * if there's no data stop don't do anything.--Done.
                * if there's a data in task task one user randomly and tweet as him/her with the lang if exists.--Done.
                    * id of the last tweet saved in redisDB to check it again the the incoming request.--Done.
                * if there's an reauth error stop the proccess and notify user.--Done.
             */
            $postAs = $this->postAsInfo( $user_id , $media);
            if (is_array( $postAs ) && !empty( $postAs )){
                $index              =  rand(0,count( $postAs )-1);
                $cron->data         =  $postAs[ $index ]['details'];
                $getUser            =  $this->mediaReader->do('getUser',$cron->data);    
                $itemName           = "task=postAs,screen_name=".$cron->data['screen_name'] . ",userID=$user_id,media=". $media;
                if (is_array( $getUser ) && array_key_exists('error',$getUser)){
                    //there's a problem. 
                    if (isset( $getUser['error']['reauth'] ) === true){
                        //delete the task an notify user , update the cache.
                        $this->notify( $user_id , $postAs[$index]['task_name'] , 3 , 'Task' );
                        $this->updateCached( $itemName , true , [] , 'redis');
                        $this->updateCached( "task=postAs,userID=$user_id,media=$media"  , true , [] , 'redis' );
                        $this->taskModel->id          = $postAs[$index]['id'];
                        $this->taskModel->status      = 2;// fail.
                        $this->taskModel->is_finished = 1;
                        $this->taskModel->save();
                    }  
                }else{
                    $lastPost = Helper::getLastPostTextByUser($getUser , $media);
                    if (is_array( $lastPost ) && !empty( $lastPost ) && isset( $lastPost['text'] )){
                        $cache    = $this->getCache('redis');
                        $item     = $cache->getItem($itemName);
                        $data     = $cache->get( $item );
                        if (is_null( $data ) === true || ( isset( $data['last_post_id'] ) && $data['last_post_id'] == $lastPost['post_id']) ){
                            $cron->owner        =  $user_id;
                            $cron->post_content = $this->translate(['text'=>$lastPost['text'],'from'=>'','to'=>$cron->data['lang']]);
                            //post this content and added it to cache.
                            $cache->set( $item , ['last_post_id'=>$lastPost['post_id']] , 0 );//not expire , only expire in line 213.
                            $post = $cron->doCron();
                        }
                    }
                }
            }else{
                $return = ["postAs(user_id:$user_id,media:$media)"=>true];
            }
            return $return ?? ['finished'=>true];
        }

        private function treeCronLogic( Cron\AbstractCron $cron , int $task_id , int $tree_id , string $media){
            /**
             * 1 - get tree by id.--Done.
                * get all information about this trees and save it in redis for 24 hr.--Done.
                * if there's no information stop the proccess.
                * if there's info in this tree get 2 users and create a relation between them.
                * if user has reauth problem , exit this user from tree and notify to sub on it again , reauth problem.
             */
            $tree_data           =  $this->loadTreeData( $tree_id  );
            $subscribers_counter = count( $tree_data );
            if (!empty( $tree_data ) && $subscribers_counter >= 2){
                //take two random users the array.
                $users_index = array_rand( $tree_data , 2);

                $users_list = ['source'=>$tree_data[$users_index[0]]['screen_name'],
                'target'=>$tree_data[$users_index[1]]['screen_name'] , 
                'source_id'=>$tree_data[$users_index[0]]['tw_id'] , 'target_id'=>$tree_data[$users_index[1]]['tw_id']];
                //setup cron data.
                $cron->data    = $tree_data[ $users_index [0] ]['tokens'];
                $check_friends = $this->mediaReader->do("checkFriends",array_merge( $cron->data , $users_list));

                if (is_array( $check_friends ) && array_key_exists('error',$check_friends) && isset( $check_friends['error']['reauth'])){
                    $this->treeModel->exitTree($tree_data[$users_index[0]]['sub_user_id'],$tree_id);
                    //notify user.
                    $this->notify( $tree_data[$users_index[0]]['sub_user_id'] ,$tree_data[$users_index[0]]['name'] , 4 , 'Tree' );
                }else{
                    $checker = Helper::follow( $check_friends , $media );
                    if (is_null( $checker ) === false){
                        $cron->list = $users_list;
                        $follow = $cron->doCron();
                    }    
                }
            }else{
                $return = ["Tree(tree_id:$tree_id)"=>true];
            }
            return $return ?? ['finished'=>true];
        }

        /**
         * this method load Schedule posts from database every 24 hr.
         */
        private function loadPosts(){
            return $this->taskModel->getPosts();
        }

        private function postAsInfo( int $user_id , string $media ){
            return $this->taskModel->postAsInfo( $user_id , $media);
        }

        /**
         * this method load control followers task from file.
         */
        private function loadControlFollowersTask( int $user_id , int $task_id , string $media ){
            if ($user_id > 0 ){
                $this->taskModel->user_id = $user_id;
                $this->taskModel->task_id = $task_id;    
            }else{
                throw new CronException("Control Followers task must have +ve value of user id fix this @class " . get_class( $this ));
            }
            return $this->taskModel->getControlFollowersTask( $media );
        }

        /**
         * load the tree data.
         */
        private function loadTreeData( int $tree_id ){
            return $this->treeModel->getTreeSubscribersById( $tree_id );
        }

        /**
         * this method load the list of specific control followers task.
         */
        private function loadList(  string $itemName , string $feature , int $user_id){
            $cache = $this->getCache();
            $list  = $cache->getItem("$feature,$user_id");
            if (is_null($cache->get( $list )) !== true){
                $taskItem = $cache->getItem( $itemName );
                $cache->set($taskItem, $cache->get( $list ) , 0);
                $list = $cache->get( $taskItem );
            }else{
                $list = [];
            }
            return $list;            
        }

        /**
         * this method update the list for control followers.
         */
        public function updateCached( string $itemName , bool $clear_it = false , array $updated_data = [] , string $cache = 'files'){
            $cache = $this->getCache($cache);
            $item  = $cache->getItem( $itemName );
            if ( $clear_it === true ){
                $cache->cache->deleteItem($itemName);
            }else{
                $cache->set( $item , $updated_data , 0);
            }
        }

        /**
         * notify user.
         */
        private function notify(int $user_id , string $msg , int $status , string $type){
            $notify = "App\\System\\Notification\\".ucfirst(strtolower($type))."Notification";
            $notify = new $notify( $user_id );
            $notify->status = $status;
            $notify->msg    = $msg;
            $notify->push();
        }


        private function translate(array $params = []){
            $google_translate = new Translate(['text'=>$params['text'],'from'=>$params['from'],'to'=>$params['to']]);
            return $google_translate->getText(); 
        } 

        /**
         * get file system cache.
         */
        private function getCache( string $cache = 'files' ){
            return new FastCache( $cache );
        }

        /**
         * uses for get instance of media read for post As feature only.
         */
        private function mediaReader( string $media ){
            $this->mediaReader =  "App\\DomainHelper\\" . $media . "\\Read";
            if (class_exists( $this->mediaReader )){
                $this->mediaReader = new $this->mediaReader;
            }else{
                throw new CronException("Can not find the class of media which read from it please fix this @class " . get_class( $this ));
            }
        }
        
    } 