<?php


        namespace Framework\Helper;
        use   Framework\Exception\HelperException;


        class UploadHelper
        {
            public static function convertFromBytes(int $bytes){
                   if(is_int($bytes) && $bytes > 0){
                        $bytes /= 1024;
                        if($bytes > 1024){
                            return number_format($bytes/1024 , 1) . 'MB';

                        }else{
                            return number_format($bytes , 1) . 'KB';
                        }
                   }

            }

            public static function convertIntoBytes($value){
                    $prefix = strtoupper(substr($value , -2));
                    $returnValue = (int) $value;
                    if(is_string($prefix) && strlen($prefix) <= 2){
                                switch ($prefix) {
                                    case 'GB':
                                    $returnValue *= 1024;
                                    case 'MB':
                                    $returnValue *= 1024;
                                    case 'KB':
                                    $returnValue *= 1024;
                                    break;   
                                    default:
                                        throw new HelperException("Error Can Not Convert $value    Into Bytes Wrong Syntax");
                                    break;
                                }
                        return $returnValue;        

                    }

            }
            /**
            *@method createFileName The Same Logic As AbstractUpload createFileName Logic
            */
            public static function createFileName(string $prefix , string $unique){
                   return uniqid($unique . '@' . $prefix); 
            }
        }