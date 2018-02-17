<?php
            namespace App\Model\App;
            use Framework\Shared\Model;
            use App\DomainMapper\UserMapper;

            Class UserModel extends Model
            {
                /**
                *Table Contents Of User.
                */
                protected $id;
                protected $tw_id;
                protected $screen_name;

                /**
                 * Table Contents Of User_data Which Have Relation With User Table.
                */

                protected $email;
                protected $account_type;
                protected $user_describe;
                protected $iswizard;
                protected $created_at;

                protected static $userMapper = null;



                /**
                 * @method isWizard Check If The User Not Solve The Wizard Area.
                 * @return boolean.
                        *false If No.
                        *true If Yes.
                 */
                public function isWizard():bool{
                       $iswizard = (is_null($this->iswizard) === true) ? true : (bool) $this->iswizard; 
                       return $iswizard;
                }

                /**
                 * @method userExists Check If The User Is New  Or Not.
                 * @return boolean.
                 */

                 public static function userExists(string $tw_id):bool{
                        return (bool)self::getFinder()->userExists($tw_id);
                 }

                /**
                 * @method getUser Get User Data.
                 * @return Model Type User | false in fail.
                */

                public static function getUser(string $tw_id){
                       return self::getFinder()->getUserData($tw_id); 
                }

                /**
                 * @method stepOne Provide Step one Of Insert processing Which Include Table A.
                 * @return false|array. 
                */

                public function stepOne(){
                       return self::getFinder()->stepOne($this);         
                }

                /**
                 * @method createProfile Just Create Profile To User.
                 * @return bool|array.
                 */

                public static function createProfile(int $id , string $name , string $email,string $account_decribe,int $account_type){
                       return self::getFinder()->createProfile($id,$name,$email,$account_decribe,$account_type);         
                } 


                /**
                * @method getFinder Find The Mapper Which Object Related To It.
                * @return Mapper.
                */
                protected static function getFinder(){
                        if(is_null(self::$userMapper) === true){
                                self::$userMapper = new UserMapper;
                        }
                                return self::$userMapper;
                }

                
            }

