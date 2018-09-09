<?php
            namespace App\Model\App\Seshat;
            use App\Model\BaseModel;
            use App\DomainMapper\Seshat\PublishMapper;

            Class PublishModel extends BaseModel
            {
                /**
                 * Table Of Content Of seshat_publish.
                 */
                protected $id;
                protected $tweet_id;
                protected $user_id;
                protected $category_id;
                protected $public_access;


                private static $seshatPublishMapper = null;

                public function save(){
                        return self::getFinder()->save(  $this );
                }

                /**
                * @method getFinder Find The Mapper Which Object Related To It.
                * @return Mapper.
                */
                protected static function getFinder(){
                        if(is_null(self::$seshatPublishMapper) === true){
                                self::$seshatPublishMapper = new PublishMapper;
                        }
                                return self::$seshatPublishMapper;
                }

                
            }

