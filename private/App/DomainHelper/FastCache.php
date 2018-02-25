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

            private function save($item){
                    $this->cache->save($item);
            }            
        }