<?php 


    /**
     * This Class Extends By All Classes In The Framework .. 
     * This Class Provide Essential Data And Features For All SubClasses  ..
     *@author Hazem Khaled
    */
    namespace Framework;
    use Framework\Exception\CoreException as CoreException;
    Class ConstructorClass
    {
        /**
        *@property encapsulateSign array
        * Contains The Signs Of Public - Private - Protected Uml Which I Need For End Of Method  Name 
        */
        // Waring : This Need Update For Specific Signs 
        // Does Not Make Scence Remove It Soon !
        protected $encapsulateSign = array("_"); /*
                                                 *  _ Means This Is Private || Protected @method Cannot Access
                                                 *  
                                                 */
        /**
        *@param array argumnets
        */
        public function __construct(array $arguments = []){



        }
        /**
        *@method
        *@return call the accessiable method for specific Property || Return Exception 
        */
        public function __get(string $property){
            $method = 'get' . ucfirst($property);
            if(method_exists($this , $method)){
                  return  call_user_func(array($this,"$method"));
            }
            // If Method Not Found Return Exception 
            throw new CoreException("You Don't Have Permission To Read To This Property $property");

            
        }
        /**
        *@method
        * set Values For Specific Property || Return Exception 
        */
        public function __set(string $property , $value){
            $method = 'set' . ucfirst($property);
            if(method_exists($this , $method)){
                    // Call To The Method 
                    return call_user_func_array(array($this,"$method") , array($value));
            }
            // If Method Not Found Return Exception 
            throw new CoreException("You Don't Have Permission To Write To This Property $property");

        }
        /**
        *@method
        * This Function Prevent Call For innaccessiable Method And AutoMatic Call To Accessaible One
        * @return Mixed    
        */
        public function __call(string $method , array $arguments = null){
            $methodEncapsulate = stripos($method , $this->encapsulateSign[0] , 0);
            if(method_exists($this,$method) && is_int($methodEncapsulate) !== true){
                    return $this->$method($arguments);
            }
                // If Method Not Found Return Exception 
                throw new CoreException("You Don't Have Permission To Call  This Method :  $method");
        }

        public function setProperty(string $propertyName,$value){
            if(property_exists($this,$propertyName)){
                            $this->$propertyName = $value;
            }
        }

        public function getProperty(string $propertyName){
                if(property_exists($this,$propertyName)){
                    return $this->$propertyName;
                } 
        }

        // Isset And Unset Not Completed Here!
        
    }