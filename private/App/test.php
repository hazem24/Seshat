<?php
        require("setting.php");

        /*session_start();
        require("../vendor/autoload.php");
        $_SESSION = [];
        use Framework\Lib\Curl\CurlClass as Curl;
        use Framework\Lib\Security\Data\FilterDataFactory;
        use Abraham\TwitterOAuth\TwitterOAuth;

        define("CALL_BACK",'http://127.0.0.1/seshat/private/App/callback.php');
        define('CONSUMER_KEY', "aunVGBbtjyWAFiZhp9lZJ2pSD");
        define('CONSUMER_SECRET', "UcuWgqSaruS8o1NY43gKaBZkMDLrJIfOEfLie0nKaPE7Eteey5");

        $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET);
        $request_token = $connection->oauth('oauth/request_token', array('oauth_callback' => CALL_BACK));
        $_SESSION['oauth_token'] = $request_token['oauth_token'];
        $_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];
        $url = $connection->url('oauth/authorize', array('oauth_token' => $request_token['oauth_token']));
        
        echo "<a href=\"$url\">sign With Twitter !</a>";*/


        /**
         * Google Translate Section Tests.
         */

        var_dump(REPORTS_HASH_FOLDER);



