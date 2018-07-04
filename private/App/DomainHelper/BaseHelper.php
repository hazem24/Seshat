<?php

    namespace App\DomainHelper;

    /**
     * This class is a Base Class For All Helper classes in the App.
     */

    Class BaseHelper 
    {
        private function  __construct(){
            //Cannot Initilize Instance From This Class Only Allow As Static.
        }

        private function __clone(){
                    //Cannot Clone Instance From This Class Only Allow As Static.
        } 
    }
