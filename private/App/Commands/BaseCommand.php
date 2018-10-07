<?php
    namespace App\Commands;
    use Framework\Shared;
    use Framework\Lib\Upload\UploadImage;
    use App\System\License\ControlLicense;

    /**
     * this class is base class for all commands in the app.
     */

    Abstract Class BaseCommand extends Shared\AbstractCommand
    {
        CONST UPLOAD_IMAGE_PATH = UPLOAD_IMAGE_FOLDER_RELATIVE;    
        CONST MAX_IMAGE_SIZE = 5242880;//5MB.

        protected $controlLicenses = null;

        /**
         * @method execute.
         * @return Mixed.
         */
        public function execute(array $data = []){
            $method = $data['Method']['name']; 
            if(method_exists($this,$method)){
                return call_user_func_array(array($this,"$method") , array($data['Method']['parameters']));
            } 
            return ['code'=>404,"msg"=>"Request Not found."]; 
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

        protected function controlLicenses( int $license_type , int $feature_id ){
            if ( is_null( $this->controlLicenses ) === true){
                $this->controlLicenses = new ControlLicense();
            }
            $this->controlLicenses->initilzation( $license_type , $feature_id );
            $this->controlLicenses->media = 'twitter';//hard coded , must be changed based on media.
            return $this->controlLicenses->feature_license_data();
        }

    } 