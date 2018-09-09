<?php 

        namespace Framework\Lib\Cache;
        use Framework\ConstructorClass as ConstructorClass;
        use Framework\Exception\CacheException as CacheException;
        use Predis\Autoloader as Autoloader;
        use Predis\Client as Client;
        /**
        *@class This Class uses redis cache system.
        */
         Class Redis extends Cache
        {


            /**
            *@method cacheConnect  Create Instance For Specific Third Party Services
            *@method setDataToCached Saved Cached Data
            *@method getData Get Data From Cache With Specific Key
            */
            public static function  cacheConnect(){

                  try{
                        Autoloader::register();
                        
                        self::$service =  new Client(self::$setting);
                  }catch(Exception $e){
                        echo $e-getMessage();
                  }
  

            }
            // Expire In Seconds 
            public static function setDataToCached(string $key , $values , int $expire = 0):bool{

                    $values = (is_array($values)) ? self::encodeAndDecodeData('serialize' , $values) : $values;
                    
                   if(self::$service->set($key , $values) == 'OK'){
                            if($expire > 0){
                                self::$service->expire($key , $expire);
                            }
                            return true;
                   }
                   // Throw New Exception Type Cache
                    
            }
            public static function getData(string $key){
                    /**
                    *@return null in Case No Data Found With Specific Key!
                    */
                    $return = self::encodeAndDecodeData('deserialize' , self::$service->get($key));
                    
                    if(is_null($return) || $return == false){
                        $return = self::$service->get($key);
                    }
                   return $return; 
            }
            public static function updateCacheInRunTime(string $key , string $type ,  $values = null):bool{
                
                    switch ($type) {
                        case 'increment':
                            return self::incrementCache($key , $values);
                            break;
                        case 'decrement':
                            return self::decrementCache($key , $values);
                            break; 
                        case 'addElement':
                            return self::addElement($key , $values);
                            break;   
                        default:
                            throw new CacheException("Error In Update Cache In Run Time Unknown Update Method");
                        break;
                    }
            }

            private static function incrementCache(string $key , array $values = ['incrBy'=>1]):bool{
                        $beforeIncrement = self::getData($key); // Get Number Before Increment +5
                        self::$service->incrby($key , $values['incrBy']);
                        $return = ((self::getData($key) - $beforeIncrement) == $values['incrBy'] )? true : false;
                        return $return;
            }
            // 5 - 2
            private static function decrementCache(string $key , array $values = ['decrBy'=>1]):bool{
                        $beforeDecrement = self::getData($key); // Get Number Before Decrement +5
                        
                        if(self::$service->decrby($key , $values['decrBy']) < 0){
                                    // Stop This Increment By Add Same +ve Value
                                    // Hazem You Do This Beacuse You Think In Your Project That Will Be No -ve Values Updated 06/05/2017
                                    self::incrementCache($key , array('incrBy'=>$values['decrBy']));
                        }
                        $return  = ((self::getData($key) + $values['decrBy']) == $beforeDecrement )? true : false;
                        return $return; 
            }
            private static function addElement(string $key ,  $values):bool{ 
                      $values = (is_array($values)) ? self::encodeAndDecodeData('serialize' , $values) : $values;
                      $return = (self::$service->APPEND($key, $values))?true : false;
                      return $return;
            }

            private static function encodeAndDecodeData(string $type ,  $data ){
                    switch ($type) {
                        case 'serialize':
                            return json_encode($data);
                            break;
                        case 'deserialize':
                            return json_decode($data);
                            break;
                        default:
                            throw new CacheException("Error In Serialize Data In Run Time Unknown Serilize Or Hash Method");
                            break;
                    }

            }
                
        }