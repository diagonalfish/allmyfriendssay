<?php
session_start();

include 'secret.php';
include 'twitter-async/EpiCurl.php';
include 'twitter-async/EpiOAuth.php';
include 'twitter-async/EpiTwitter.php';

$twitterObj = new EpiTwitter($consumer_key, $consumer_secret);

$notoken = 0;

if (isset($_GET['oauth_token']) && !isset($_SESSION['oauth_token'])) {
  $twitterObj->setToken($_GET['oauth_token']);
  $token = $twitterObj->getAccessToken();
  $twitterObj->setToken($token->oauth_token, $token->oauth_token_secret);
  $_SESSION['oauth_token'] = $token->oauth_token;
  $_SESSION['oauth_secret'] = $token->oauth_token_secret;
  header("Location: .");
}

else if (isset($_SESSION['oauth_token'])) {
  $twitterObj->setToken($_SESSION['oauth_token'], $_SESSION['oauth_secret']);
}

else {
  $notoken = 1;
}
?>