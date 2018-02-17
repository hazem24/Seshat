<?php
namespace Framework\Lib\DataBase;
use Doctrine\ORM\Mapping as ORM;
/**
 * @Entity @Table(name="users")
 **/

class Users {
        /** @Column(type="string") **/
        protected $username;
        public function  getUserName(){
                return $this->username;
        }
    }