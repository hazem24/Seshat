<?php


        namespace App\Router;
        use Framework\ConstructorClass as Base;
        use Framework\Helper\ArrayHelper;

        /**
        *This Class Provide The Base Class For All Route Of The App
        *Any Child Route Must Be Extends From This 
        */

        Abstract Class Route extends Base
        {
              protected $pattern;
              protected $controller;
              protected $action;
              protected $parameters = [];

            public function __construct(array $option = []){
                   if(isset($option['pattern']) && !empty($option['pattern'])){
                        $this->pattern = $option['pattern'];
                   }else{
                        throw new \Exception("You Try To Create Instance Of Route Without Specifie The Pattern To Match Please Fix This @Class " . get_class($this));
                   } 
            }  

            public function match(string $url):bool{
                    $this->parseRequestUrl($url);
                    return true;
            }

            public function getController(){
                        return (is_null($this->controller)) ? null : $this->controller;
            }  

            public function getAction(){
                        return (is_null($this->action)) ? null : $this->action;
            }
            public function getParams():array{
                        return $this->parameters;
            }

            protected function parseRequestUrl(string $url){
                        $url = explode('/' , trim(parse_url($url , PHP_URL_PATH) , '/') ,4);
                        $url = ArrayHelper::trimArray($url);
                        if(isset($url[1]) && !empty($url[1])){
                                    $this->controller = $url[1];
                        }
                        if(isset($url[2]) && !empty($url[2])){
                                    $this->action = $url[2];
                        }
                        if(isset($url[3]) && !empty($url[3])){
                                    $this->parameters = explode('/',$url[3]);
                        }

              }
        }