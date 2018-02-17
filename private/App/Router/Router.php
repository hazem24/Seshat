<?php

        namespace App\Router;
        use Framework\ConstructorClass as Base;
        use App\Controller;
        /**
        *This Class Handle All Requests By The App And Search For The Response
        */

        Class Router extends Base
        { 
            protected $url;
            protected $controller;
            protected $action;
            protected $routes = [];


            public function __construct(string $url){
                    /**
                    *This Is Default Controller And Action If The App Cannot Create Controller And Action For The Request
                    */
                    // This Default Controller
                    $this->controller = "Index";
                    if(!empty($url)){
                            $this->url = $url;
                    }else{
                        throw new CoreException("We Cannot Search For Route For Empty Url Please Provide That");
                    }
            }
            /**
                *(['simple'=>new Simple])
            */
            public function addRoutes(array $routes){
                    foreach($routes as $name => $route){

                        $this->routes[$name] = $route;
                    }
                    return $this;
            }

            /**
            *@method removeRoute This method unset The Value Of route By Name 
            */
            public function removeRoute(string $routeName){

                foreach($this->routes as $name => $instance){
                        if($routeName === $name){
                                unset($this->routes[$name]);
                        }
                }
                return $this;
            }
            /**
            *@method getRoutes Return Array With All Routes Name Which Saved In The Router
            *@return array
            */
            public function getRoutes():array{
                $routedSaved = [];
                foreach($this->routes as $name => $instance){
                        $routedSaved[] = $name;    
                }
                return $routedSaved;
            }
            /**
            *@method dispatch 
            */
            public function dispatch(){
                   foreach($this->routes as $route){
                        $match = $route->match($this->url);
                        if($match){
                            /**
                            *1-Take Controller Name 
                            *2-Take Action Name 
                            *3-Take Parameters Array
                            *4-Send This Data To __pass @method To Do What The Url Need
                            */
                            $this->controller = strtolower($route->getController());
                            $this->action     = $route->getAction();
                            $parameters       = (array) $route->getParams();
                            $this->__pass($parameters);
                            return;
                        }
                   }
                   
                   $this->__pass([]); 
            }

            public function __pass(array $parameters){


                   $this->controller = "App\\Controller\\" . ucfirst($this->controller);                   
                   $actionName     = $this->action;
                   if(Class_exists($this->controller)){
                            $this->controller = new $this->controller(['parameters'=>$parameters]); // Send Parameter To Controller If Exists
                   }else{
                            $actionName = "notFound";
                            $this->controller = new Controller\NotFound();
                   }
                           $this->controller->findIt(['action'=>$actionName]); //Check If The Action Exists 

            }


        }