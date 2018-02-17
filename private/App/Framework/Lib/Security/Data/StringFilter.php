<?php

        namespace Framework\Lib\Security\Data;
        use Framework\Exception\SecurityException as SecurityException;
        use Framework\Lib\Security\Data\FilterData as FilterData;

        Class StringFilter extends FilterData 
        {
            

           

            protected function validateData(){
                    /**
                    * Vaildate Type Of Data String 
                    * Length Of Data 
                    * Pattern Data If Can 
                    * Use Filter Var For Vaildation No Filter Var For Vaildation In String 
                    */
                    
                    parent::validateData();
                    $this->santizeData = $this->trimData($this->santizeData);
                    if(!empty($this->santizeData))
                    {
                            $vailedDataType  = (!is_string($this->santizeData))? $this->errorMsg[$this->htmlAttribute][] = [$this->htmlAttribute.'@error@InvailedData'=>'Please Enter Vailed Data in ' . $this->htmlAttribute . ' Field']:true;
                            $vailedMaxLength = (strlen($this->santizeData) > $this->maxLength) ? $this->errorMsg[ $this->htmlAttribute][] = [$this->htmlAttribute.'@error@MaxLength'=>'Max Length Required ' . $this->maxLength . ' Characters At ' . $this->htmlAttribute . ' Field']:true;
                            $vailedMinLength = (strlen($this->santizeData) < $this->minLength) ? $this->errorMsg[ $this->htmlAttribute][] = [$this->htmlAttribute.'@error@MinLength'=>'Min Length Required ' . $this->minLength . ' Characters At ' . $this->htmlAttribute . ' Field']:true;
                            // I Think This Pattern Can Be Replaced By The Above
                            //^[a-z](?:_?[a-z0-9]+){"+min+","+max+"}$
                            //$isString = (!preg_match ('/^[A-Z \'.-]{'.$this->minLength.','.$this->maxLength.'}$/i', $this->dirtyData)) ? $this->errorMsg[$this->htmlAttribute][] = [$this->htmlAttribute.'@error@InvailedData'=>'Please Enter Vailed Data in ' . $this->htmlAttribute . ' Field']:true;
                            //$isString = (!preg_match ("/^[a-z](?:_?[a-z0-9]+){".$this->minLength.",".$this->maxLength."}$/i", $this->dirtyData)) ? $this->errorMsg[$this->htmlAttribute][] = [$this->htmlAttribute.'@error@InvailedData'=>'Please Enter Vailed Data in ' . $this->htmlAttribute . ' Field']:true;
                            if(empty($this->errorMsg)){
                                    $this->validData = $this->santizeData;
                            }

                    }else{
                        $this->errorMsg[$this->htmlAttribute] = [$this->htmlAttribute.'@error@EmptyData'=>EMPTY_FEILD]; 
                    }
                    
                     

            }

            protected function santizeData($dirtyData){
                /**
                * santize Data As String
                */
                $clearData = filter_var($dirtyData , FILTER_SANITIZE_STRING);
                $this->santizeData=$clearData; //Data That Can Be Used In Vaildation Process
            }


        }

