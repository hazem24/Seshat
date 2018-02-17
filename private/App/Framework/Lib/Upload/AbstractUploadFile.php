<?php
            namespace Framework\Lib\Upload;
            use Framework\ConstructorClass;
            use Framework\Exception\FileException;
            use Framework\Helper\UploadHelper;
            Abstract Class AbstractUploadFile extends ConstructorClass
            {
                protected $fileName;
                protected $uploadType;
                protected $size;
                protected $tmp_name;
                protected $error;
                protected $destination;
                protected $extension;
                protected $webError = [];
                protected $maxSize = 51200; //50Kb
               
                


                public function __construct(string $destination = '/'){
                       $file = current($_FILES);
                       $this->fileName    = $file['name'];
                       $this->error       = $file['error'];
                       $this->tmp_name    = $file['tmp_name'];
                       $this->size        = $file['size'];
                       $this->uploadType  = $file['type'];
                       $this->setDestination($destination);
                }


                public function intelligentUpload(string $prefix = '' , $unique = '' , string $successMsg = ''){
                       if($this->checkFile()){
                            /** Move The File To The Destination
                            *1-Create New Name
                            *2-Upload File
                            *3-Return Successfully Message
                            */
                            $this->extractExtension();
                            $this->createFileName($prefix , $unique); // I Need This Value
                            if($this->moveFile()){
                                    return ['successUpload'=>$successMsg,'fileName'=>$this->fileName.'.'.$this->extension];
                            }else{
                                $this->webError['webErrorView@CannotUploadFile'] = "Your File Cannot Uploaded Please Try It Again Later";
                            }    
                       }
                       return $this->webError;
                }
                

                
                 

                public  function setMaxSize(int $maxSize){
                          
                          if(is_int($maxSize) && $maxSize > 0 ){
                                    $this->maxSize  = $maxSize;
                          }  
                }




                protected function checkFile():bool{
                    
                        if($this->error != 0){
                                    $this->errorHandler();
                        }
                                    
                                    $this->checkSize();
                                    $this->checkType();
                                    if(!empty($this->tmp_name)){
                                            $this->vailedFile();
                                    }
                                    
                        if(is_array($this->webError) && !empty($this->webError)){
                                    return false;
                        }
                        return true;
                }


                protected function checkSize():bool{
                       
                       if($this->size == 0){
                                $this->webError['webViewError@NoFileSelected'] = 'Please Select A File To Upload';
                       }
                       if($this->size > $this->maxSize){
                                $this->webError['webViewError@MAX_SIZE']       = 'This File ' . $this->fileName . ' Is Too Big Please Do Not  Exceeds ' . $this->useHelper();
                       }
                       return true;
                }


                protected function createFileName(string $prefix , $unique){
                    $this->fileName = uniqid($unique . '@' . $prefix);
                }

                protected function checkType():bool{
                          if(!in_array($this->uploadType,$this->allowableType)){
                                $this->webError['webViewError@UnknownFileExtension'] = "Unknown File Type Allowable Type Is: " . implode(',',$this->allowableType);
                                return false;
                          }
                          return true;  
                }

                protected function extractExtension(){
                            $this->extension = pathinfo($this->fileName)['extension'];
                }


                protected function useHelper():string{
                            return UploadHelper::convertFromBytes($this->maxSize);  
                }    

                final protected function moveFile(){
                    $moveFile = move_uploaded_file($this->tmp_name , $this->destination.$this->fileName.'.'.$this->extension);
                    //Delete The Tmp File 
                    if(file_exists($this->tmp_name) && is_file($this->tmp_name)){
                           unlink($this->tmp_name);  
                    }
                    return $moveFile;
                }

               final  protected function errorHandler(){
                        
                        switch ($this->error) {
                            case 1 || 2:
                                $this->webError['webViewError@MaxSize'] = 'This File ' . $this->fileName . ' Is Too Big Please Do Not  Exceeds ' . $this->useHelper();
                                break;
                            case 3:
                                $this->webError['webViewError@PartiallyUploaded'] = "The File Partailly Uploaded ";
                                break;    
                            case 4:
                                $this->webError['webViewError@NoFileUploaded'] = 'Please Select A File To Upload ';
                                break;
                            default:
                                $this->webError['webViewError@CanNotUploadFile'] = "Sorry Your File " . $this->fileName . " CanNot Upload To Our System Please Try Again Latter ";
                                break;
                        }    
                    
                      
                }


                final protected function setDestination(string $destination){
                        if(!is_dir($destination) || !is_writable($destination)){
                                throw new FileException("Error To Upload File @$destination Please Check The Path Or Change The Permission");
                        }
                        if($destination[strlen($destination)-1] != DS){
                                $destination .= DS;
                        }
                        $this->destination = $destination;
                }


                Abstract protected function vailedFile():bool;


            }

