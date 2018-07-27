<?php
    namespace App\Commands;
    use Framework\Shared;

    /**
     * this class is base class for all commands in the app.
     */

    Abstract Class BaseCommand extends Shared\AbstractCommand
    {
        CONST UPLOAD_IMAGE_PATH = UPLOAD_IMAGE_FOLDER_RELATIVE;    
        CONST MAX_IMAGE_SIZE = 5242880;//5MB.

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

        /**
         * @method uploadMedia This Method Handle Image Media Only.
         * @return array If Error Occur. || string of file name at success.
         */
        protected function uploadMedia(int $user_id){
                $uploadMedia = new UploadImage(self::UPLOAD_IMAGE_PATH);
                $uploadMedia->setMaxSize(self::MAX_IMAGE_SIZE);
                $uploadMedia = $uploadMedia->intelligentUpload("Seshat",$user_id,'');
                if(array_key_exists('successUpload',$uploadMedia)){
                          return $uploadMedia['fileName'];  
                }else{
                          return $uploadMedia;  
                }

        }


    } 