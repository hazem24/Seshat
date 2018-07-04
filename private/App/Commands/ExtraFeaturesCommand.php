<?php
    namespace App\Commands;
    use App\Model\WebServices\Google\Translate\Translate;


    /**
     * this class responsble for all commands for extra features.
     */

    Class ExtraFeaturesCommand extends BaseCommand
    {
        /**
         * @note any method that want to call it from outside the class must be protected and can be called via "execute" method in the base.
         * incase of parameters must send to method send it as an array of $param.
         */

        protected function translate(array $params = []){
               $google_translate = new Translate(['text'=>$params['text'],'from'=>$params['from'],'to'=>$params['to']]);
               return $google_translate->getText(); 
        } 
        
    } 