<?php

    namespace App\Controller;
    use App\DomainHelper\ControllerHelper\CronHelper;       


    /**
    *This Class Responsable for Cron system.
    */

    Class Cron extends AppShared
    {
        public function executeAction( array $params = [] ){
            $this->detectLang();
            CronHelper::execute( $this , $params );
        }
    }