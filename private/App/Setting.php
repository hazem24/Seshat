<?php
        use Configuration\DbConfig;
        use Configuration\ErrorConfig;
        use Configuration\MailerConfig;
        use Configuration\ExceptionConfig;
        use Framework\Exception\DbException;
        use Framework\Exception\CoreException;
        use Framework\Lib\DataBase\Query\QueryBuilder\SelectQueryBuilder;
        use Framework\Registry;
        use Framework\Lib\DataBase\Query\QueryBuilder\UpdateQueryBuilder;
        use Framework\Lib\DataBase\Query\QueryBuilder\InsertQueryBuilder;
        use Framework\Lib\Session\AppSession;



        /**
        * This File Contained All Setting For Whole Website Included:-
            * Application Path Url And Uri
            * DataBase Setup 
            * Error Setup 
            * Exception Setup
            * Require AutoLoad File
            * Create Session 
            * Languagh Choosen Cookies -- Not Thinking About It
        
        */


        // Url And Uri
        define('DS' , '/');
        define('PS',PATH_SEPARATOR);
        define('LINK_SIGN','!');
        define('MAIL',''); //This Will Be The Mail Setting In Which All Mail Of The App Will Send From It
        define('COMPANY_MAIL','Seshat');//Company Name To Be Used In Mailer System
        define('PRODUCTION' , true);
        define('Geo_Location_Server_Access' , 'SAKYW592M92ER296B78Z'); // Uses eurekapi.com As Geolocation Service
        define('APP_FOLDER_PATH' , "seshat/private" . DS);
        define("BASE_URI" , $_SERVER['DOCUMENT_ROOT'] . DS.APP_FOLDER_PATH);//Can Be Access By Browser
        define("OUTSIDE_ACCESS",'/');//Can Not Be Access By Browser But Access By Server
        define("ROOT",'/');
        define("ASSESTS_URI" , "http://" . $_SERVER['HTTP_HOST'] . DS .'seshat/assets' . DS);
        define("BASE_URL" , "http://" . $_SERVER['HTTP_HOST'].DS.'seshat'.DS);
        define("VIEWS_PATH" , BASE_URI . "App" . DS . "View".DS);
        define("LAYOUT_PATH",BASE_URI."App".DS."Layout".DS);
        define("LANG_PATH",BASE_URI.DS."lang".DS);
        define("ATTACKER_SALT",'socialservice'); //Provide A Salt To Be Used In Any Session Attacker But it False To Disable The Feature
        define("UPLOAD_CONTENT_FOLDER" ,  BASE_URI .DS  . 'App'.DS.'DomainFiles'.DS); //This Must Be In SubDomain (Images And Videos (Content) Must Be In SubDomains)
        define("REPORTS_FOLDER",UPLOAD_CONTENT_FOLDER .  'Reports' . DS); //Reports Folder.
        define("REPORTS_HASH_FOLDER", REPORTS_FOLDER . 'hashtag' . DS);
        define("UPLOAD_IMAGE_FOLDER_RELATIVE",UPLOAD_CONTENT_FOLDER . 'Media'. DS. 'Images'.DS);
        define("UPLOAD_VIDEO_FOLDER_RELATIVE",ROOT  . APP_FOLDER_PATH .DS  . 'App'.DS.'DomainFiles'.DS .'Videos');

        // Require AutoLoad 
        require(BASE_URI . "vendor/autoload.php");
        define("TW_MOBILE",'https://mobile.twitter.com');
        define("TW_LOGIN","https://mobile.twitter.com/login");
        define("TWITTER_NEW_SESSION","https://mobile.twitter.com/sessions");
        define("COOKIES_FOLDER",BASE_URI."App".DS."Cookies");
        define('SCREEN_NAME_CHALLANGE','https://mobile.twitter.com/account/login_challenge');
        define("TWEET_SENDER",'https://mobile.twitter.com/compose/tweet');
        

       try
       {
           /**
           *hazem the below data All For Test This File Need Reset
           */
        // DataBase Setup 
                $dbObject =  DbConfig::setup([
                    'setting'=>[
                    'driver' => 'mysql',
                    'host'=>'127.0.0.1',
                    'username' => 'root',
                    'dbname'=> 'seshat',
                    'password' => '5553332',
                    'options'=>[\PDO::MYSQL_ATTR_FOUND_ROWS => true],
                    'charset' => ['utf-8']
                    ]
                ]);
		
                
               // Save In Registry And unset It 
               Registry::setInstance('database',$dbObject);
		
               $selectBuilder  = new SelectQueryBuilder;
               Registry::setInstance('selectBuilder',$selectBuilder); 

               $updateBuilder  = new UpdateQueryBuilder;
               Registry::setInstance('updateBuilder',$updateBuilder);

               $insertBuilder  = new InsertQueryBuilder;
               Registry::setInstance('insertBuilder',$insertBuilder);

            
               $session  = new AppSession;
               Registry::setInstance('session',$session);

              
		


                // Error Setup 
                ErrorConfig::setup([
                    "production"=>PRODUCTION,
                    "logPath"=>BASE_URI . "App".DS. "Logger".DS."error.log",
                    ""=>"",
                ]);

                // Exception Setup
                ExceptionConfig::setup([
                    "production"=>PRODUCTION,
                    "logPath"=>BASE_URI . "App".DS. "Logger".DS."exception.log",
                    ""=>"",
                ]);
                // This For Test Only 
                $sessions = Registry::getInstance('session');
                $sessions->gc(1440);

                $mailerSystem = MailerConfig::setup(
                    [   'service'=>'phpmailer',
                        'option'=>[ 'host'=>'localhost',
                                    'port'=>25,
                                    'smtpAuth'=>false, // Can Be true or false
                                    'username'=>'',
                                    'password'=>'',
                                    'secure'=>''] // This Option For Can Be ssl Or Tsl
                    ]);
			
                Registry::setInstance('mailer' , $mailerSystem);    
                // Unset Global Variables
                unset($dbObject);
                unset($selectBuilder);
                unset($updateBuilder);
                unset($insertBuilder);
                unset($mailerSystem);

               
       }catch(CoreException $core){
            echo $core->getMessage();
       }catch(DbException $e){
            echo $e->getMessage();
       }catch(PDOException $e){
            echo $e->getMessage();
       }   


       

