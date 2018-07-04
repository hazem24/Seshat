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

        /**
         * @method arsortArray.
         * @param by can be one of the following flags : 
            *Sorting type flags:
                *SORT_REGULAR - compare items normally (don't change types)
                *SORT_NUMERIC - compare items numerically
                *SORT_STRING - compare items as strings
                *SORT_LOCALE_STRING - compare items as strings, based on the current locale. It uses the locale, which can be changed using setlocale()
                *SORT_NATURAL - compare items as strings using "natural ordering" like natsort()
                *SORT_FLAG_CASE - can be combined (bitwise OR) with SORT_STRING or SORT_NATURAL to sort strings case-insensitively
         * @return array.
         */
        public static function arsortArray(array $array , $by = null):array{
            if(is_null($by)){
                    //aSort by number.
                    $by = SORT_NUMERIC;
            }
            arsort($array,$by); 
            return $array;
        }

    }