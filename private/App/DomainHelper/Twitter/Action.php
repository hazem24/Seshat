<?php
    namespace App\DomainHelper\Twitter;



    /**
     * This Class An Abstract For All Action User Want To Do In Twitter.
     */

    Abstract class Action
     {
        protected $command;
        /**
         * @method do Responsable For Search If Logic Exists For  Specific Critira And Call it.
         * @return Mixed. 
         */ 
        final public function do (string $logicName,array $parameters=[]){
               if(method_exists($this,$logicName)){
                       return call_user_func_array(array($this,"$logicName") , array($parameters));
               } 
        }

        Abstract protected function initCommand();


     } 