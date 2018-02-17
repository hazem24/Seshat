<?php
        session_start();
        require("../vendor/autoload.php");
        use Abraham\TwitterOAuth\TwitterOAuth;
        define('CONSUMER_KEY', "aunVGBbtjyWAFiZhp9lZJ2pSD");
        define('CONSUMER_SECRET', "UcuWgqSaruS8o1NY43gKaBZkMDLrJIfOEfLie0nKaPE7Eteey5");

        $data = [];

        $data["oauth_token"] = $_SESSION['oauth_token'];
        $data["oauth_token_secret"] = $_SESSION['oauth_token_secret'];
        if(isset($_REQUEST['oauth_token']) && $data['oauth_token'] == $_REQUEST['oauth_token']){
                $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $data['oauth_token'], $data['oauth_token_secret']);
                $access_token = $connection->oauth("oauth/access_token", ["oauth_verifier" => $_REQUEST['oauth_verifier']]);//Get Access Token From Users !
                $_SESSION['access_token'] = $access_token;//Save In DataBase !
                $_SESSION['oauth_token'] = $access_token["oauth_token"];
                $_SESSION['oauth_token_secret'] = $access_token["oauth_token_secret"];
        
                /**
                 * For AnyTime I Want To Act As User I Must Use $access_token['oauth_token'] and $access_token['oauth_token_secret']
                 */
                $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $access_token['oauth_token'], $access_token['oauth_token_secret']);
                $user = $connection->get('account/verify_credentials', ['include_email'=>"true"]);
                //$post_tweet_setup = $connection->post('statuses/update',['status'=>"مرحبا اسمي سيشات واعمل كمساعد شخصي لك في حسابك بتويتر تم تهئيتي علي هذا الحساب بنجاح "]);
                var_dump($access_token);
                exit;


        }else{
                    echo "Wrong Access !";
        }