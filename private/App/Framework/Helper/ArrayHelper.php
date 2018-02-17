<?php

    namespace Framework\Helper;
    use Framework\ConstructorClass as ConstructorClass;

    /**
    *Static Class Has Some Helper Function Can Be UseFull In Whole Application
    */
    Class ArrayHelper extends ConstructorClass
    {
        public static function convertMultiArray(array $array , array $return = []){
                
                foreach ($array as $key => $val) {
                    if(is_array($val)){
                        $return = self::convertMultiArray($val , $return);
                    }else{
                        if(!is_int($key)){
                            $return[$key][] = $val;
                        }else{
                            $return[] =  $val;
                        }
                    }
                }
                return $return;
        }
        /**
        *@used In SelectQueryBuilder @class @method where 
        *@used At Data Security Also 
        */
        public static function filterDataWithArray(array $dataToFilter):array{
                $fieldNameToFilter = array_keys($dataToFilter);
                $valuesToFilterIt =  array_values($dataToFilter);
                return [$fieldNameToFilter , $valuesToFilterIt];

        }

        /**
        *@method trimArray trim An Array
        *@return trimed Array Data
        */ 
        public static function trimArray(array $arrayToTrim):array{
            $callbackTrim = function($e){
                if(is_array($e)){
                        foreach($e as $val){
                            trim($val);
                        }
                }else{
                    trim($e);
                }
                return $e;
            };

            $callbackErrorIfEmpty = function($e){
                    if(empty($e)){
                            $e = 'empty';
                    }
                return $e;
            };
            $arrayAfterTrim =  array_map( $callbackTrim , $arrayToTrim);
            return array_map($callbackErrorIfEmpty , $arrayAfterTrim);

        }

    }