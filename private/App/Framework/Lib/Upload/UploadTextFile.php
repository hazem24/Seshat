<?php
             namespace Framework\Lib\Upload;
             use Framework\Lib\Upload\AbstractUploadFile;


             Class UploadTextFile extends AbstractUploadFile
             {
                    protected  $maxSize = 512000; // 500KB
                    protected  $allowableType = ['text/plain'];
                    /**
                    *@method vailedType
                    *Used For Create More Secury Check If The Uploaded File Is Specific Image
                    *@return Boolean 
                    */                                        
                    protected function vailedFile():bool{
                        if(file_exists($this->tmp_name)){
                            
                            if(array_search($this->uploadType,$this->allowableType) !== false) {
                                        return true;
                            }
                                $this->webError['webErrorView@FileNotImage'] = "Our System Detect That You Try To Upload A File That Not Text File As Text File Please Fix This";

                        }else{
                                $this->webError['webErrorView@FileNotImage'] = "Sorry You Cannot Upload File Now Please Try Again Later";
                        }
                        
                         return false;
                    }                                    

             }