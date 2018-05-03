<?php
        namespace App\Commands;
        use Framework\Lib\Upload\UploadImage;



        /**
        *This Class  is Parent Class For All Child Twitter Commands.
        */

        Class TwitterCommand extends BaseCommand
        {
            CONST UPLOAD_IMAGE_PATH = UPLOAD_IMAGE_FOLDER_RELATIVE;    
            CONST MAX_IMAGE_SIZE = 5242880;//5MB.

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
