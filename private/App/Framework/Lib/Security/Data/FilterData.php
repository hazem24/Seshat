<?php 

        namespace Framework\Lib\Security\Data;

        use Framework\Exception\SecurityException as SecurityException;
        use Framework\ConstructorClass as ConstructorClass;
        /**
        *@class 
        *This Class Abstract Class For All Data Type Filter : json - string - integer - ip - float - email
        */
        Abstract Class FilterData extends ConstructorClass
        {

            protected $dirtyData;
            protected $htmlAttribute;
            protected $maxLength;
            protected $minLength;
            protected $pattern;
            protected $errorMsg = [];
            protected $clearData= [];
            protected $option = [];
            protected $santizeData;
            protected $validData;
            /**
            *['Name'=>'value','max'=>'length','min'=>'length',[option]]
            *option set for drop down menu or select value as Constant For integer or float or string or anything
            */
            public function setDirtyData(string $htmlAttribute , array $dirtyData , array $option = null){
                $this->htmlAttribute = $htmlAttribute;
                $this->option = $option;
                $this->dirtyData = $dirtyData['value'];
                $this->maxLength = $dirtyData['max'];
                $this->minLength = $dirtyData['min'];
                return $this;
            }
            public  function trimData($data,string $postion ='both',string $charlist = ''){
                    switch (strtolower($postion)) {
                            case 'left':
                                    if(!empty($charlist)){
                                                $trim = ltrim($data,$charlist);
                                    }else{
                                                $trim = ltrim($data);
                                    }
                                    break;
                            case 'right':
                                    if(!empty($charlist)){
                                                $trim = rtrim($data,$charlist);
                                    }else{
                                                $trim = rtrim($data);
                                    }
                                    break;
                            default:
                                    if(!empty($charlist)){
                                                $trim = trim($data,$charlist);
                                    }else{
                                                $trim = trim($data);
                                    }
                                    break;
                    }
                    return $trim;
            }

             public function proccessFilter(){
                /**
                * Create Two Steps :-
                        1- validateData from its specific method
                        2- santizeData  from its specific method
                 * check if there is any error in the errorMsg
                        if Yes  Create Web Error View To See By User
                        if not return the clearedData      
                */
                        //Santize Data
                        $this->santizeData($this->dirtyData); // Good Data At This Property

                        $this->validateData();
                        
                        if(!array_key_exists($this->htmlAttribute , $this->errorMsg)){
                                
                                //Return Good Data
                                
                                return $this->validData;

                        }else{
                                // Return WebView Error
                               
                                return  $this->errorMsg[$this->htmlAttribute];
                        }

                        throw new SecurityException("No Data Returned In Process Filter @ Class " . get_class($this));
                        

            }

            protected function validateData(){
                   if(isset($this->option) && !is_null($this->option)){
                            $valuesFound = (in_array($this->dirtyData , $this->option)) ? true : false;
                            if(!$valuesFound){
                                    $this->errorMsg[$this->htmlAttribute][] = [$this->htmlAttribute.'@error@option'=>'This Information Only Allowed (' . implode(',',$this->option) .')']; 
                            }
                    }
                    return true; 
            }
            Abstract protected function santizeData($dirtyData);
            // i think for web view error msg function
        }
