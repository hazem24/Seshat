<?php
        namespace App\DomainHelper\ControllerHelper;
        use App\DomainHelper\BaseHelper;
        use Framework\Request\RequestHandler;
        use Framework\Lib\Security\Data\FilterDataFactory;
        use Framework\Error\WebViewError;
        use Framework\Lib\Security\Forms\CsrfProtection;
        use App\DomainHelper\FrontEndHelper;
        use App\Controller\Seshat;
        use Framework\Shared;

        /**
         * This Class Provide All Helper Methods That used inside seshat controller.
         */

        Class SeshatHelper extends BaseHelper
        {

            public static function createReportHelper ( Seshat $seshatController , array $params = [] ){
                    /**
                      * 1- check params[0] if isset or not && check the type of report if allowable.
                        *if true => start logic proccess for this type of report. 
                        *else not found page.
                      */
                      $report_type = (isset($params[0]) && !empty($params[0]))? self::reportType($params[0]) : false;
                      if($report_type !== false){
                              /**
                               * check if reports value if found at least one params is found $params[1].
                               * start to create a report based on type of report.
                              */
                              if (RequestHandler::postRequest()) {
                                      $hashtag_name = (string) RequestHandler::post('hashtag');
                                      $response = self::createReport( $seshatController , $report_type,['hash_tag'=>$hashtag_name]);
                                      $response = $seshatController->returnResponseToUser($response);
                                      $seshatController->encodeResponse($response);    
                              }       
                      }
                      //Web View.
                      $seshatController->renderLayout("headerApp");
                      if($report_type === false){
                              //render not found layout.   
                              $seshatController->renderLayout("NotFound");  
                      }else {
                              //Hashtag report form.
                              $seshatController->renderLayout("createReport_hashTag");
                      }
                      $seshatController->renderLayout("footerApp");      
            }


            public static function getReportHelper( Seshat $seshatController , array $params = [] ){
                $folder_path = (isset($params[0]) && !empty($params[0])) ? (string) REPORTS_FOLDER.$params[0].DS : '';
                $file_name = (isset($params[1]) && !empty($params[1])) ? (string) $folder_path.$params[1] . '.json' : null;
                if (is_null($file_name) === false && file_exists($file_name) === true) {
                        echo file_get_contents($file_name);
                        $seshatController::stop();
                }
                $seshatController->encodeResponse(['notFound'=>true]);
            }

            public static function renderReportView ( Seshat $seshatController , string $report_type ){
                $seshatController->renderLayout("HeaderApp");
                   switch (strtolower($report_type)) {
                       case 'hashtag':
                           $seshatController->renderLayout("get_hashtag_Report");
                           break;
                       default:
                           $seshatController->renderLayout("NotFound");
                           break;
                   }
                   $seshatController->renderLayout("footerApp"); 
            }

            /**
              * @method reportType Responsble for check type of report and if exists or not.
              * @return int of type report || false.
              */

            private static function reportType(string $report_name){
                $report = ['hashtag'=>1];
                if(array_key_exists(strtolower($report_name),$report)){
                    return $report[$report_name];
                }   
                    return false;
            }

            /**
            * responsable for create specific report for specific type.
            * @method createReport.
            */
            private static function createReport (Seshat $seshatController , int $report_type , array $data_to_create_report = []){
                switch ($report_type) {
                    case 1://Hashtag report data type.
                        //Hashtag report $data_to_create_report must have one and only one element which is hashtag name to track.
                        if(isset($data_to_create_report['hash_tag']) && !empty($data_to_create_report['hash_tag'])){
                                 return self::createHashTagReport( $seshatController , $data_to_create_report['hash_tag']);
                        } 
                    default:
                         return ['code'=>404,'error'=>["Request Not found."]];
                         break;
                } 
            }

           /**
            * @method createHashTagReport Responasble for create hashtag report.
            * @return string => indicate uniq_id_of_report || void otherwise.
            */
            private static function createHashTagReport( Seshat $seshatController , string $hashtag_name){
                $tokens = $seshatController->getTokens();  
                $screenName = $seshatController->session->getSession("username");
                $cmd = Shared\CommandFactory::getCommand('seshat');
                $getHashTagReport =  $cmd->execute(['Method'=>['name'=>"createHashTagReport",'parameters'=>['screenName'=>$screenName,
                'hashtag'=> urlencode((stripos($hashtag_name,'#') !== false) ? '' : '#').$hashtag_name,
                'oauth_token'=>$tokens['oauth_token'],'oauth_token_secret'=>$tokens['oauth_token_secret']]]]);
                $seshatController->commonError($getHashTagReport); 
                return $getHashTagReport;
            }

        }