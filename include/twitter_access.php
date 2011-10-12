<?php
session_start();

include 'secret.php';
include 'twitter-async/EpiCurl.php';
include 'twitter-async/EpiOAuth.php';
include 'twitter-async/EpiTwitter.php';

$twitter = new EpiTwitter($consumer_key, $consumer_secret);

$notoken = 0;

if (isset($_GET['oauth_token']) && !isset($_SESSION['oauth_token'])) {
  $twitter->setToken($_GET['oauth_token']);
  $token = $twitter->getAccessToken();
  $twitter->setToken($token->oauth_token, $token->oauth_token_secret);
  $_SESSION['oauth_token'] = $token->oauth_token;
  $_SESSION['oauth_secret'] = $token->oauth_token_secret;
  header("Location: .");
}

else if (isset($_SESSION['oauth_token'])) {
  $twitter->setToken($_SESSION['oauth_token'], $_SESSION['oauth_secret']);
}

else {
  $notoken = 1;
}

if (!$notoken) {
	if (!isset($_SESSION['userinfo'])) {
	    $twitterInfo = $twitter->get_accountVerify_credentials();
	    $_SESSION['userinfo'] = $twitterInfo->response;
	}
}
?>