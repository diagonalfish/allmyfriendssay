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

include "twitter_access.php";

if (isset($_GET['logout'])) {
  session_destroy();
  header("Location: .");
}

?>

	<body>