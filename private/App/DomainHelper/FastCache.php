<?php
        namespace App\DomainHelper;
        use phpFastCache\CacheManager;
        


        /**
         * This Class Provide All Static Function That App Need To Create Cache System Used(PhpFastCache).
         */

        Class FastCache 
        {
            /**
             *Important methods in this library 
                *hasItem($key) // Tests if an item exists
                *deleteItem($key) // Deletes an item
                *More Method at http://www.phpfastcache.com.
 
             */    
            private $cache;    
            public function __construct(){
                
                CacheManager::setDefaultConfig([
                        "path" => sys_get_temp_dir()
                  ]);
                $this->cache = CacheManager::getInstance('files');  
            }


            public function getItem(string $name){
                   return $this->cache->getItem($name); //Return Item.
            }

            public function get($item){
                    return $item->get(); //Return Data.
            }
            
            public function set($item,$data,int $expire = 0){
                   $item->set($data)->expiresAfter($expire);
                   $this->save($item); 
            }

            public function clear(){
                $this->cache->clear();//Clear It and Start From Beg.
            }


             /**
             *@method increment Tweet increment tweet (retweet-like) for specific tweet in cache system.
             *@return void.
             */
            public function incrementTweet(string $type,int $key,$item,int $expire = 0){
                $tweet_to_increment = $this->get($this->getItem($item)); //Return Data of specific tweet.
                if(isset($tweet_to_increment[$key]) && is_null($tweet_to_increment) === false){
                         if($type == 'retweet'){
                                 if(isset($tweet_to_increment[$key]->retweet_count)){
                                         $tweet_to_increment[$key]->retweet_count = $tweet_to_increment[$key]->retweet_count + 1; //increment the tweet.
                                         $tweet_to_increment[$key]->retweeted  = true; //Tweet Retweeted.
                                         $this->updateExistingItem($item,$tweet_to_increment[$key],$key,$expire);
                                 }
                         }else if($type=='like'){
                            if(isset($tweet_to_increment[$key]->retweeted_status->favorite_count)){
                                $tweet_to_increment[$key]->retweeted_status->favorite_count = $tweet_to_increment[$key]->retweeted_status->favorite_count + 1; //increment the tweet.
                                $tweet_to_increment[$key]->favorited  = true; //Tweet liked.
                                $this->updateExistingItem($item,$tweet_to_increment[$key],$key,$expire);
                            }else if(isset($tweet_to_increment[$key]->favorite_count)){
                                $tweet_to_increment[$key]->favorite_count = $tweet_to_increment[$key]->favorite_count + 1; //increment the tweet.
                                $tweet_to_increment[$key]->favorited  = true; //Tweet liked.
                                $this->updateExistingItem($item,$tweet_to_increment[$key],$key,$expire);
                            }
                     } 
                }
                
            }

            /**
             * @method decrementTweet decrement tweet (unretweet-unlike) for specific tweet in cache system.
             * @return void.
             */
            public function decrementTweet(string $type , int $key , $item , int $expire = 0){
                $tweet_to_decrement = $this->get($this->getItem($item)); //Return Data of specific tweet.
                if(isset($tweet_to_decrement[$key]) && is_null($tweet_to_decrement) === false){
                         if($type == 'unretweet'){
                                 if(isset($tweet_to_decrement[$key]->retweet_count)){
                                         $tweet_to_decrement[$key]->retweet_count = $tweet_to_decrement[$key]->retweet_count - 1; //decrement the tweet.
                                         $tweet_to_decrement[$key]->retweeted  = false; //Tweet unRetweeted.
                                         $this->updateExistingItem($item,$tweet_to_decrement[$key],$key,$expire);
                                 }
                         }else if($type=='unlike'){
                                if(isset($tweet_to_decrement[$key]->retweeted_status->favorite_count)){
                                    $tweet_to_decrement[$key]->retweeted_status->favorite_count = $tweet_to_decrement[$key]->retweeted_status->favorite_count - 1; //decrement the tweet.
                                    $tweet_to_decrement[$key]->favorited  = false; //Tweet unliked.
                                    $this->updateExistingItem($item,$tweet_to_decrement[$key],$key,$expire);
                                }else if(isset($tweet_to_decrement[$key]->favorite_count)){
                                    $tweet_to_decrement[$key]->favorite_count = $tweet_to_decrement[$key]->favorite_count - 1; //decrement the tweet.
                                    $tweet_to_decrement[$key]->favorited  = false; //Tweet unliked.
                                    $this->updateExistingItem($item,$tweet_to_decrement[$key],$key,$expire);
 
                                }
                         } 
                } 
            }

            private function save($item){
                    $this->cache->save($item);
            } 
            
            /**
             * @method updateExistingItem update data inside specific item.
             * @return void.
             */
            private function updateExistingItem(string $item_name,$new_data,int $key,int $expire = 0){
                $item = $this->getItem($item_name);
                $item_data = $this->get($item);
                $item_data[$key] = $new_data;
                $this->set($item,$item_data,$expire);
            }
        }