<?php ob_start(); ?><!DOCTYPE HTML>
<html>
	<head>
		<title>AllMyFriendsSay - Find out what your twitter friends are thinking!</title>
		<link rel="stylesheet" href="style.css" type="text/css"/>
		<link href='http://fonts.googleapis.com/css?family=Sue+Ellen+Francisco' rel='stylesheet' type='text/css'>
		<link href='http://fonts.googleapis.com/css?family=Droid+Sans' rel='stylesheet' type='text/css'>
		<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js"></script>
	</head>
<?php

session_start();

include 'secret.php';
include 'twitter-async/EpiCurl.php';
include 'twitter-async/EpiOAuth.php';
include 'twitter-async/EpiTwitter.php';

$twitterObj = new EpiTwitter($consumer_key, $consumer_secret);

$notoken = 0;

if (isset($_GET['logout'])) {
  session_destroy();
  echo "Logged out.";
  exit;
}

if (isset($_GET['oauth_token']) && !isset($_SESSION['oauth_token'])) {
  $twitterObj->setToken($_GET['oauth_token']);
  $token = $twitterObj->getAccessToken();
  $twitterObj->setToken($token->oauth_token, $token->oauth_token_secret);
  $_SESSION['oauth_token'] = $token->oauth_token;
  $_SESSION['oauth_secret'] = $token->oauth_token_secret;
}

else if (isset($_SESSION['oauth_token'])) {
  $twitterObj->setToken($_SESSION['oauth_token'], $_SESSION['oauth_secret']);
}

else {
  $notoken = 1;
}
?>

	<body>