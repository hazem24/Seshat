<?php

            namespace Framework\Error;
            use Framework\Helper\ArrayHelper;
            /**
            *This Class Provide Static Method Which Can Be Used 
            *To Convert The Inside webError Which Provide With The App To Readable Error To Client User Ex:- Bad Data 
            */

            Class WebViewError 
            {
                protected static $dataBeforeFilter;
                protected static $dataAfterFilter;

                private function __construct(){
                        //Empty!!
                }
                private function __clone(){
                        //Empty!!
                }
                /**
                *@method anyError Search If The @param $data Has Any Error With It 
                *@return bool true If any Error Found False OtherWise
                */
              
                public  static function anyError(string $typeOfSearch ,  $dataBeforeFilter , array $dataAfterFilter = []){
                            self::$dataBeforeFilter = $dataBeforeFilter;
                            self::$dataAfterFilter  = $dataAfterFilter;
                           
                            $detect     = self::detectTypeOfError($typeOfSearch);
                            return (empty($detect)) ? false : $detect ;

                }

                /**
                *@method readableErrorMsg Convert this Error                                 
                *$this->webError['webErrorView@CannotUploadFile'] = "Your File Cannot Uploaded Please Try It Again Later";
                *To Readable Error Msg
                *Any Error In The App Error Mean SomeThing User Do It Wrong Like :- Invailed Data This Method Convert It To Readable Msg
                */
                public static  function userErrorMsg(array $webError){
                        
                       foreach($webError as $key => $val){
                             $field[] = $key;   
                             if(is_array($val)){
                                    $userView[$key] = $val;
                             }else{
                                    $userView[] = $val;
                             }
                                    

                       }
                       return (is_array($userView)) ? implode('<br>' , ArrayHelper::convertMultiArray(ArrayHelper::convertMultiArray($userView))): $userView; 
                }

                /**
                
                */

                protected static function detectTypeOfError($typeOfSearch){
                          switch (strtolower($typeOfSearch)) {
                              case 'form':
                                  // Handle Logic
                                  foreach(self::$dataAfterFilter as $key => $value){
                                      
                                      $anyError[$key] = (is_array(self::$dataAfterFilter[$key])) ? self::$dataAfterFilter[$key] : false;

                                  }
                                  
                                  return array_filter($anyError);
                                  break;
                              case 'upload';                                      
                                      $anyError = (is_array(self::$dataBeforeFilter)) ? self::$dataBeforeFilter : false;   
                                  return array_filter($anyError); 
                                  break;    
                              
                              default:
                                  # code...
                                  break;
                          }  

                }

                
                 
            }

            