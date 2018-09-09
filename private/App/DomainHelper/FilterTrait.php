<?php

    namespace App\DomainHelper;
    /**
    * Trait Handle All Filter needed In The App.
    */
    trait FilterTrait
    {
        protected static function usernamePattern( string $username , int $minLength = 2 ,int $maxLength = 200 ):bool{
            preg_match("/^[A-Za-z0-9_]{" . $minLength.","."$maxLength}$/" , $username , $match);
            if (!empty( $match )){
                return true;
            }
            return false;
        }
    }

        