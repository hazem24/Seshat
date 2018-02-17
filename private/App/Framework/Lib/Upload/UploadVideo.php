<?php
             namespace Framework\Lib\upload;
             use Framework\Lib\Upload\AbstractUploadFile;


             Class UploadVideo extends AbstractUploadFile
             {
                    protected  $maxSize = 51200000; // 50.68MB
                    protected  $allowableType = ['video/webm',
                                                'video/mp4',
                                                'video/ogg'
                                                ];
                    /**
                    *@method vailedType
                    *Used For Create More Secury Check If The Uploaded File Is Specific Video
                    *@return Boolean 
                    *Fixed -- (Done) @sameDay 04:48 AM :) Until Now I Not Found Good Way To Detect If The Upload File Is Video Or Not Written @02:32 17/06/2017
                    */                                        
                    protected function vailedFile():bool{

                                    $originalType = mime_content_type($this->tmp_name);
                                    if(!in_array($originalType , $this->allowableType)){
                                            $this->webError['webErrorView@FileNotVideo'] = "Our System Detect That You Try To Upload A File That Not Video As Video Please Fix This";
                                            return false;
                                    }
                                    return true;
                    }                                    

             }