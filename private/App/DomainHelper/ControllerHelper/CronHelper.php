<?php
    namespace App\DomainHelper\ControllerHelper;
    use App\DomainHelper\Helper;
    use App\Controller\Cron;
    use Framework\Shared\CommandFactory as Cmd;

    /**
     * this class is a helper for cron controller.
     */

    Class CronHelper
    {
        public static function execute(Cron $cron , array $params){
            $media_exists = Helper::issetMedia($params[0] ?? '');
            $task_id      =  $params[1] ?? 0;
            if ($media_exists === true && (int) $task_id > 0){
                $user_id = $params[2] ?? 0;
                //cron logic to cmd is here.
                $cmd = Cmd::getCommand('cron');
                $response = $cmd->execute(['Method'=>['name'=>'doTask','parameters'=>['media'=>ucfirst(strtolower($params[0])),'task_id'=>$task_id
                ,'user_id'=>$user_id]]]);
                $cron->encodeResponse($response);
            }
            $cron->encodeResponse(['error'=>'Request not found' , 'code'=>404]);
        }
    }