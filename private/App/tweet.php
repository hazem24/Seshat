<?php


session_start();
require("../vendor/autoload.php");
use Abraham\TwitterOAuth\TwitterOAuth;
define('CONSUMER_KEY', "aunVGBbtjyWAFiZhp9lZJ2pSD");
define('CONSUMER_SECRET', "UcuWgqSaruS8o1NY43gKaBZkMDLrJIfOEfLie0nKaPE7Eteey5");

$data = [];
//This Must Be Saved In DataBase !.
$data["oauth_token"] = $_SESSION['oauth_token'];
$data["oauth_token_secret"] =$_SESSION['oauth_token_secret'];
$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $data['oauth_token'], $data['oauth_token_secret']);
$user = $connection->get('account/verify_credentials', ['include_email'=>"true"]);
$post_tweet_setup = $connection->post('statuses/update',['status'=>"...بيانات المستخدم "]);
var_dump($post_tweet_setup->errors);
exit;
