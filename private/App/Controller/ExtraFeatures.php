<?php

        namespace App\Controller;

        use Framework\Shared;
        use App\DomainHelper\Helper;
        use Framework\Error\WebViewError;
        use Framework\Request\RequestHandler;
        use Framework\Lib\Security\Data\FilterDataFactory;
        


        /**
        *This Class Responsable for Extra Features add to seshat like google translate.
        */

        Class ExtraFeatures extends AppShared
        {

            /**
             * @method translateAction.
             * @return json || array.
             */

             public function translateAction(){
                    $this->rule();
                    if(RequestHandler::postRequest()){
                         $content_to_translate = (string)RequestHandler::post('content_to_translate');
                         $from = (string)RequestHandler::post("from");
                         $to   = (string)RequestHandler::post("to");
                         $translate = $this->translateThis($content_to_translate,$from,$to);
                         //Check if there's any Error Happen In the system.
                      
                         if($this->anyAppError() === true){
                                $response = ['error'=>$this->error];
                         }else{
                            //Return translated tweet.
                            $response = $translate;
                         }
                    }
                    if(isset($response)){
                        $this->encodeRepsonse($repsonse);
                    }
                $this->encodeResponse(["code"=>403,'error'=>"Protected Area You Cannot Access."]);
             }

             private function translateThis(string $content_to_translate , string $from , string $to){
                $data = [TRANSLATE_CONTENT=>['value'=>$content_to_translate , 'type'=>'string','min'=>1, 'max'=>5000]];
                $filter = new FilterDataFactory($data);
                $data_after_clear   = $filter->getSecurityData();
                $anyError =  WebViewError::anyError('form' , $data , $data_after_clear);
                /**
                 * Check For vaildate of from and to if there's in the countries that can be translated.
                 */
                $check_lang = Helper::checkLang($to);
                if(is_string($check_lang) === true){
                        $this->error[] = $check_lang;
                }

                if(is_array($anyError)){
                    $this->error[] = WebViewError::userErrorMsg($anyError);
                }else {
                    //Send tweet to translate to google.
                    $cmd = Shared\CommandFactory::getCommand('ExtraFeatures');
                    $translate_content = $cmd->execute(['Method'=>['name'=>'translate','parameters'=>['text'=>$content_to_translate,'from'=>$from,'to'=>$to]]]);
                    return ['translated_content'=>$translate_content];
                }
             }

        }