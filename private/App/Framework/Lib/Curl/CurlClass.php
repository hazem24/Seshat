<?php

    namespace Framework\Lib\Curl;
    use \Curl\Curl;
    use Framework\Exception\CurlException;

    /**
     * @class CurlClass This Class Provide Some Method For Curl Library (Simple Class).
     */

    Class CurlClass
    {
        private $curl;
        private $url;
        private $options=[];
        


        public function __construct(){
                $this->curl = curl_init();
        }
        

        public function setOpts(array $options = []){
               foreach($options as $key=>$value){
                        if(array_key_exists($key,$this->options) === false){
                                $this->options[$key]=$value;
                        }
               } 
        }


        public function get(string $url,array $data = []){
                $this->setOpts([CURLOPT_POST=>false]);
                if(!empty($data)){
                        $url  .= "?".$this->orgData($data);
                }
                $this->url = $url;
                $this->setUrl($this->url);
                $this->sendOptionsToCurl(); 
        }

        public function post(string $url ,array $data){
               $this->setUrl($url);
               $this->setOpts([CURLOPT_POST=>true]);
               if(!empty($data)){

                        $this->setOpts([CURLOPT_POSTFIELDS=>$this->orgData($data)]);
                        //Search For Any Other Options and Send It To Curl.
                        $this->sendOptionsToCurl();
               }else{
                        throw new CurlException("Post Method Must Have Some Data To Send Post Request With It.");
               } 
        }

        public function execute(){
               return curl_exec($this->curl); 
        }

        public function close(){
                curl_close($this->curl);
        }

        private function sendOptionsToCurl(){
            if(!empty($this->options)){
                    curl_setopt_array($this->curl,$this->options);
            } 
        }

        private function setUrl(string $url){
            $this->url = $url;
            $this->setOpts([CURLOPT_URL=>$this->url]);
        }

        private function orgData(array $data){
            $string_data = '';
            foreach($data as $key =>$value){
                    $string_data = $string_data."$key=$value";
                    if(strtolower(end($data)) != strtolower($value)){
                            $string_data .= "&";
                    }
            }
            return $string_data;
        }


    }