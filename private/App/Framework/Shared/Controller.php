<?php

        namespace Framework\Shared;
        use Framework\ConstructorClass as Base;
        use Framework\Shared\ViewHandler;
        use Framework\Lib\Security\Session\SessionSecurity;
        use Framework\Registry;
        use App\DomainHelper\Helper;
        
        /**
        *This Class Provide The Base Of Controller Class In Which All SubController
        *Extends From This Controller
        */


        /**
        *Notice In The Near Update I Must Provide The Layout So This Class Must Handle Layout 
        *But Need Some Research @Written @06:20Am 21/06/2017
        */

        Class Controller extends Base
        {

            CONST DEFAULT_ACTION_VIEW_PATH = VIEWS_PATH;

            protected $parameters = [];
            //protected $renderLayoutView = false;
            protected $renderActionView = false;
            //protected $layoutView;
            protected $actionView;

            protected $actionToCall;
            /**
            *@property session This Property Found To Initilize Session Class With Registry In The 
            *Constructor Of Each Controller
            */
            protected $session;

            /**
             * @property viewInstance.
            */
            protected $viewInstance = null;


            public function __construct(array $option = []){

                //Empty For Now @Written @06:44Am 21/06/2017
                /*
                *Updated OpenSession From Registry
                */
                if(isset($option['parameters'])){
                        $this->parameters = $option['parameters'];
                }
                
                $this->session = Registry::getInstance('session');
                
            }

            public function findIt(array $option = []){
                    if(isset($option['action']) && !empty($option['action'])){
                        $this->actionView  = $option['action'];
                        
                    }else if(is_null($option['action'])){
                        $this->actionView = 'default';
                    }
                    $this->setActionView($this->actionView);
                    //Call Action ..
                    $this->methodToCall($this->actionToCall,$this->parameters);
            }

            /**
             * @method viewInstance.
             * @return instance of viewHandler Class.
             */
            protected function viewInstance(string $viewName = ''){
                    if(is_null($this->viewInstance)){
                            $this->viewInstance = new ViewHandler(['file'=>$viewName]);
                    }
                    return $this->viewInstance;
            }


            protected function setActionView(string $actionView){
                   $this->actionToCall = $this->actionView . "Action"; 
                   if(method_exists($this , $this->actionToCall)){
                        $this->renderActionView = true;
                             //$className = explode('\\',basename(get_class($this)));//Liunx System $className[2]
                             $className = basename(get_class($this));//Windows System
                             $this->actionView       = self::DEFAULT_ACTION_VIEW_PATH . DS . $className. DS . $actionView . ".View.php";
                   }else{
                        $this->actionToCall     = 'notFoundAction';
                        $this->actionView       = self::DEFAULT_ACTION_VIEW_PATH . 'NotFound'. DS . "notFound.View.php";
                   }

                        $this->actionView       = $this->viewInstance($this->actionView);
                        
            }

            protected  function methodToCall(string $actionName , array $param = []){
                    $this->$actionName($param);
            }

            public function notFoundAction(){
                      $this->render();
            }

            protected function render(){
                
                if($this->renderActionView){
                         $this->actionView->render();   
                }else{
                         $this->actionView->notFound();
                }
            }
            
            /**
            *@method sessionSecurity This Method Call One Time When User Enter The App (Login Or SignUp)
            */
            protected  function sessionSecurity(string $agentSalt = ''){
                
                        $agent = SessionSecurity::createUserAgent($agentSalt);
                        $this->session->setSession('userAgent',$agent);
            }

            protected  function attacker(string $salt , string $savedSession = 'userAgent'){
                        return SessionSecurity::isAttacker(SessionSecurity::createUserAgent($salt), $this->session->getSession($savedSession));
                      
            }
            /**
            *@param dataToLayoutOrError This Array Must Be Have The Following:-
            *1-Steps In Which User Can Solve The Error 
            *2-Some Sorry
            *3-Prizes Or Some Thing Like This 
            *4-Some Returned Back Or Helper Link 
            */

            protected function renderLayout(string $layoutName , array $dataToLayoutOrError = [] , $ajax = false){
                      ViewHandler::renderLayout($layoutName,$dataToLayoutOrError,$ajax);
            }



            /**
            *@method detectLang User Can Change Lang.
            *This method resobonsable for get the lang. User Want 
            *@return file with spcefic lang.
            */

            protected function detectLang(){
                      $userLang = strtolower((string)$this->session->getSession('lang'));
                      if(in_array($userLang , $this->supportedLang())){
                                require(LANG_PATH."$userLang.lang.php");
                      }else {
                                require(LANG_PATH."en.lang.php"); //Default Lang By Default Is Arabic ! 
                      } 
                               
            }


            protected function supportedLang():array{
                     return ['ar','en'];
            }
            /**
            *@method rOut Create Intelligent Redirect Based On User Status
            */
            protected function rOut(string $salt ,string $redirectTo){
                      Helper::redirectOutSide($salt,BASE_URL.LINK_SIGN.$redirectTo);
            }

            protected function rIn(string $salt ,string $redirectTo){
                     Helper::redirectInside($salt,BASE_URL.LINK_SIGN.$redirectTo);
            }





           

        }