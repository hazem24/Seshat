<?php

        namespace Framework\Shared;
        use Framework\ConstructorClass as Base;
        use Framework\Helper\Html;
        use Framework\Registry;
        use Framework\Lib\Security\Forms\CsrfProtection;

        /**
        *This Class Handle All Views That Needed By The User Request. 
        *Responsable For Require The Wanted View If Exists.
        *Or NotFound View If Not Exists. 
        */


        Class ViewHandler extends Base
        {
            CONST DEFAULT_LAYOUT_VIEW_PATH = LAYOUT_PATH;
            private $file;
            private $data = [];
            private $protectFormView;
            private $session; //Used To Allow Me To Use Session In Any View I Want.



            public function __construct(array $option = []){

                    $this->file = $option['file'];
                    $this->session = Registry::getInstance('session');
                   
            }

            public function render($ajax = false){
         
                if(file_exists($this->file)){
                    $this->protectFormView = CsrfProtection::protectForm(); // Protect Forms In The Views If Forms Found
                    // To Exract Each Assoc. Element In Data Array To Variable Fast More Than Extract Function Which Built In Php
                    foreach($this->getData() as $name => $value){
                            $$name = $value;      
                    }
                    require($this->file);

                }else
                {
                    /*
                    *I Not Choose Require Once Because The Performance If We Will Buy Great
                    *Server && The App Will Be Bigger Than Now I Will Turn It To Require Once
                    */
                    require(BASE_URI . "App".DS."View".DS."NotFound".DS ."notFound.View.php");
                    exit;
                }
            }
            public function getData(){

                   if(isset($this->data) && !empty($this->data) && !is_null($this->data)){
                        return $this->data;
                   }
                   return []; 
            }

            public function setDataInView(array $data){
                if(!empty($data)){
                        foreach($data as $key =>$val){
                                $this->data[$key] = $val;
                        }
                }
                return $this;
            }

            public function notFound(){
                    require(BASE_URI . "App".DS."View".DS."NotFound".DS ."notFound.View.php");
                    exit;
            }
            public  function htmlSafer(string $data):string{
                    return Html::encodeDataToHtml($data);
            }
           /**
           *Change Log @Written 05/08/2017 @08:43 Pm viewHandler Class Now Responable For render Layout Also
           *Change log changes for static to non-static method to uses session in some check in header also.
           */ 
           public  function renderLayout(string $layoutName , array $dataToLayoutOrError = [] , $ajax = false){
                foreach($dataToLayoutOrError as $name => $value){
                        $$name = $value;      
                }                
                if($ajax === true){
                        return file_get_contents(self::DEFAULT_LAYOUT_VIEW_PATH . ucfirst(strtolower($layoutName)) .".Layout.php");
                }
                require(self::DEFAULT_LAYOUT_VIEW_PATH . ucfirst(strtolower($layoutName)) .".Layout.php");                       
            }
        }