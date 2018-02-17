<?php

        namespace Framework\Lib\Security\Data;
        use Framework\Exception\SecurityException as SecurityException;
        use Framework\Lib\Security\Data\FilterData as FilterData;

        Class FloatFilter extends FilterData 
        {


            protected function validateData(){
                    /**
                    * Vaildate Type Of Data String 
                    * Length Of Data 
                    * Pattern Data If Can 
                    * Use Filter Var For Vaildation No Filter Var For Vaildation In String 
                    */
                    
                    parent::validateData();
                    $this->santizeData = (float)$this->trimData($this->santizeData);
                    if(!empty($this->santizeData))
                    {
                        $validateString = (filter_var($this->santizeData , FILTER_VALIDATE_FLOAT) === (false || 0  )) ? $this->errorMsg[$this->htmlAttribute][] = [$this->htmlAttribute.'@error@InvailedData'=>'Please Enter Vailed Data In ' . $this->htmlAttribute . ' Field Number Only Allowed']: true;
                        $vailedMaxLength = (strlen($this->santizeData) > $this->maxLength) ? $this->errorMsg[ $this->htmlAttribute][] = [$this->htmlAttribute.'@error@MaxLength'=>'Max Length Required ' . $this->maxLength . ' Characters']:true;
                        $vailedMinLength = (strlen($this->santizeData) < $this->minLength) ? $this->errorMsg[ $this->htmlAttribute][] = [$this->htmlAttribute.'@error@MinLength'=>'Min Length Required ' . $this->minLength . ' Characters']:true;
                        if(empty($this->errorMsg)){
                            $this->validData = $this->santizeData;
                        }
                    }else{
                        $this->errorMsg[$this->htmlAttribute][] = [$this->htmlAttribute.'@error@EmptyData'=>'Please Fill  Data At ' . $this->htmlAttribute . ' Can Not Be Empty']; 
                    }
            }

            protected function santizeData($dirtyData){
                /**
                * santize Data As Float
                */
                $clearData = filter_var($dirtyData , FILTER_SANITIZE_NUMBER_FLOAT , FILTER_FLAG_ALLOW_THOUSAND);
                $this->santizeData=$clearData; //Data That Can Be Used In Vaildation Process
            }


        }

