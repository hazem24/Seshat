<?php 

        namespace Framework\Lib\Security\Data;
        use Framework\Lib\Security\AbstractSecurityFactory as AbstractSecurityFactory;
        use Framework\Exception\SecurityException as SecurityException;
        use Framework\Helper\ArrayHelper as ArrayHelper;
        use Framework\Registry as Registry;
        /**
        *@class 
        * This Class Factory For All Data Type Filter ex:- String - integer - data from url - float - double
        */
        Class FilterDataFactory extends AbstractSecurityFactory
        {
             /**
            * Data Format 
            * ['username1'=>array('value'=>'hazem2','type'=>'string','min'=>'5' , 'max'=>'10')];
            * 
            */
            public function getSecurityData(){
                    $convert = ArrayHelper::filterDataWithArray($this->dirtyData);
                    $return = $this->prepareFilterType();
                    return $return;
                    
            }
            /**
            *@method prepareFilterType 
            *prepare the filter of specfic type 
            */

            protected function prepareFilterType(){
                $convert = ArrayHelper::filterDataWithArray($this->dirtyData);
                $countArray = count($convert[0]);
                
                for ($i=0; $i < $countArray ; $i++) { 
                   // Convert[0] => Assoc Key 
                   // Convert[1] => values 
                   // $convert[1][$i]['type' || 'min' || 'max' , 'value']
                   $htmlAttribute  = $convert[0][$i]; // This Will Use In Error 
                   $type  = $convert[1][$i]['type']; // string
                   $value = $convert[1][$i]['value'];
                   if(isset($convert[1][$i]['option'])){
                        $option = $convert[1][$i]['option'];
                   }else{
                       $option = null;
                   }
                   

                   $min   = $convert[1][$i]['min'];
                   $max   = (isset($convert[1][$i]['max']) && !empty($convert[1][$i]['max'])) ? $convert[1][$i]['max'] : null;
                   $objectExists = $this->objectExistsInRegistry($type);
                   /**
                   *This Part Just For Save Filter Object In Registry For Create One Object  Only
                   *For Specific Type Of Filter
                   ** Prevent Duplicate Instances For One Object  
                   */
                   if(!$objectExists){
                        Registry::setInstance(ucfirst($type) .'Filter',$this->dataType($type)); // Create Object Of Specific Type);
                        $objectExists = $this->objectExistsInRegistry($type);
                   }
                   // ['Name'=>'value','max'=>'length','min'=>'length']
                   $return[$htmlAttribute]  = $objectExists->setDirtyData($htmlAttribute, ['value'=>$value  , 'min'=>$min , 'max'=>$max] , $option)->proccessFilter();
                   
                }
                return $return;
            }



            /**
            *@method dataType
            *Create Object Of Specific Type To Filter Data  
            */
            protected function dataType(string $type){
                $type = strtolower($type);
                switch ($type) {
                    case 'string':
                        return new StringFilter(); //Done..
                        break;
                    case 'int':
                        return new IntegerFilter();//Done..
                        break;
                    case 'float':
                        return new FloatFilter();//Done..
                        break;
                    case 'url':
                        return new UrlFilter();//Done..
                        break;
                    case 'email':
                        return new EmailFilter();//Done..
                        break;
                     case 'ip':
                        return new IpFilter();
                        break;    

                    default:
                        throw new SecurityException("Unknown Basic Data type To Filter @Class " . get_class($this));
                        break;
                }
            }

          
        }