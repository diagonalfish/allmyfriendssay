<?php

/* This is where we load data from Twitter. */

//Twitter token stuff
include "include/twitter_access.php";
include "include/redis.php";

$query = $_REQUEST['query'];

if ($notoken) header("Location: .");

//Current user's ID
$my_id = $_SESSION['userinfo']['id_str'];

//Step 1: Load followed user IDs
$friends = redis_get("friends:".$my_id);
if (!$friends) {
	//Load from twitter
	$twi_friends = $twitter->get('/friends/ids.json');

	$friends = $twi_friends->response;
	if (sizeof($friends) > 100) {
		//Too many friends. Pick 150 at random and keep them.
		shuffle($friends);
		$friends = array_slice($friends, 0, 100);
	}

	redis_set("friends:".$my_id, $friends);
}


//Step 2: Fetch tweets for each of these IDs and store them.
$twitter->useAsynchronous(true);
$responses = array();
foreach($friends as $friend) {
	echo "Friend: ".$friend."\n";
	$have_tweets = redis_exists("tweets:".$friend);
	if ($have_tweets) {
		echo "Already got those!\n";
		continue;
	}

	$twitter->useAsynchronous(true);

	//Fetch 100 tweets
	$responses[] = array($friend,
		$twitter->get('/statuses/user_timeline.json',
		array('user_id' => intval($friend), 'count' => 100)));
}
foreach ($responses as $response) {
	$friend = $response[0];
	$twi_resp = $response[1];
	redis_set("tweets:".$friend, $twi_resp->response);
}

$twi_stat = $twitter->get("/account/rate_limit_status.json");
print_r($twi_stat->response);

?>