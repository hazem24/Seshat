<?php
    namespace App\Commands;
    use Framework\Shared;

    /**
     * this class is base class for all commands in the app.
     */

    Abstract Class BaseCommand extends Shared\AbstractCommand
    {
        /**
         * @method execute.
         * @return Mixed.
         */
        public function execute(array $data = []){
            $method = $data['Method']['name']; 
            if(method_exists($this,$method)){
                     return call_user_func_array(array($this,"$method") , array($data['Method']['parameters']));
            }  
        }


    } 