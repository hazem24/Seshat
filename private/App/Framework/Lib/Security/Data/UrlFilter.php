<?php

        namespace Framework\Lib\Security\Data;
        use Framework\Exception\SecurityException as SecurityException;
        use Framework\Lib\Security\Data\FilterData as FilterData;

        Class UrlFilter extends FilterData 
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
                        /**
                        *@forget Don't Forget This Note 
                        *If Bussiness Model Need Flag Like 
                        * FILTER_FLAG_SCHEME_REQUIRED || FILTER_FLAG_HOST_REQUIRED || FILTER_FLAG_PATH_REQUIRED ||FILTER_FLAG_QUERY_REQUIRED
                        *I Must Add It ! 
                        */
                        $validateString = (filter_var($this->santizeData , FILTER_VALIDATE_URL ,FILTER_FLAG_SCHEME_REQUIRED) === false) ? $this->errorMsg[$this->htmlAttribute][] = [$this->htmlAttribute.'@error@InvailedData'=>'Please Enter Vailed Data In ' . $this->htmlAttribute . ' Field Url(Links) Only Allowed']: true;
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
                * santize Data As Url
                */
                $clearData = filter_var($dirtyData , FILTER_SANITIZE_URL);
                $this->santizeData=$clearData; //Data That Can Be Used In DataBase Or View To User
            }


        }

