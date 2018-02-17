<?php 

    namespace Framework\Lib\Security;

    use Framework\ConstructorClass as ConstructorClass;
    use Framework\Exception\SecurityException as SecurityException;
    use Framework\Registry as Registry;

    /**
    *@class 
    * This Class Abstract Factory  Class For All Security Class :- File Security , Data , Session
    */
    Abstract Class AbstractSecurityFactory extends ConstructorClass
    {

        protected $dirtyData = [];
        
        /**
        * Data Format 
        * ['username1'=>array('value'=>'hazem2','type'=>'string','min'=>'5' , 'max'=>'10')];
        * 
        */
        public function __construct(array $dirtyData){
            $this->dirtyData = $dirtyData;
        }

        /**
        * To Get The Specific Factory ex:- Data , File 
        */
        Abstract public    function getSecurityData();
        Abstract protected function prepareFilterType();
        Abstract protected function dataType(string $type);
          /**
            *@method objectExists Check If This Object of specific filter data Exists Or Not in registry
            * This @method Will Be OverRide In FilterFormFactory (Security File)
          */
            protected function objectExistsInRegistry($className){
                  $className = ucfirst($className) . 'Filter';
                   if(is_object(Registry::getInstance($className))){
                        return  Registry::getInstance($className);
                   }
                   return false;
                      
            }
        

    }