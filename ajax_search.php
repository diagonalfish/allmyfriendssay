<?php

/* This is where we process the twitter data we loaded. Yay! */

/* TF-IDF code largely borrowed from
	http://phpir.com/simple-search-the-vector-space-model */

include "include/twitter_access.php";
include "include/redis.php";
include "include/sentiment.php";

$query = $_REQUEST['query'];

$_SESSION['search']['query'] = $query;

//Current user's ID
$my_id = $_SESSION['userinfo']['id_str'];

//Load followed user IDs
$friends = redis_get("friends:".$my_id);

if (!$friends) { echo "0";  exit; } //oops

$tweets = array();

foreach ($friends as $friend) {
	$friend_tweets = redis_get("tweets:".$friend);
	if (!$friend_tweets) continue; //don't have these, skip this friend

	//Mash all the tweet arrays together.  Phew.
	$tweets = array_merge($tweets, $friend_tweets);
}

$index = genIndex($tweets);

//Search!
$matchTweets = doSearch($index);

if (sizeof($matchTweets) == 0) {
	echo "0";
	exit;
}

$sentimentTotal = 0;
$nonzeroTweets = 0;

$searchTweets = array();

foreach(array_keys($matchTweets) as $mt) {
	$tweet = $tweets[$mt];
	$sentiment = sentiment($tweet['text']);
	if ($sentiment != 0) {
		$sentimentTotal += $sentiment;
		$nonzeroTweets++;
	}
	
	$searchTweets[] = array('tweet' => $tweet, 'sentiment' => number_format($sentiment, 2));
}

$avgSentiment = number_format($sentimentTotal/$nonzeroTweets, 2);

$_SESSION['search']['tweets'] = $searchTweets;
$_SESSION['search']['avgSentiment'] = $avgSentiment;

echo "1";

//echo "<br/><br/><b>Average Sentiment Score: ".$sentimentTotal/$nonzeroTweets."</b>";

function genIndex($tweets) {
	global $query;
	
	$aquery = preg_split("/\W+/", strtolower($query));
	
    $dictionary = array();
    $docCount = array();

    foreach($tweets as $tweetID => $tweet) {
            $terms = preg_split("/\W+/", strtolower($tweet['text']));
            $docCount[$tweetID] = count($terms);

            foreach($terms as $term) {
					if (!in_array($term, $aquery)) continue;
                    if(!isset($dictionary[$term])) {
                            $dictionary[$term] = array('df' => 0, 'postings' => array());
                    }
                    if(!isset($dictionary[$term]['postings'][$tweetID])) {
                            $dictionary[$term]['df']++;
                            $dictionary[$term]['postings'][$tweetID] = array('tf' => 0);
                    }

                    $dictionary[$term]['postings'][$tweetID]['tf']++;
            }
    }

    return array('docCount' => $docCount, 'dictionary' => $dictionary);
}

function doSearch($index) {
	global $query;

	$aquery = preg_split("/\W+/", strtolower($query));
	$matchDocs = array();
	$docCount = count($index['docCount']);

	foreach($aquery as $qterm) {
	        $entry = $index['dictionary'][$qterm];
			if (!$entry) continue;
	        foreach($entry['postings'] as $docID => $posting) {
	                $matchDocs[$docID] +=
	                                $posting['tf'] *
	                                log($docCount + 1 / $entry['df'] + 1, 2);
	        }
	}

	// length normalise
	foreach($matchDocs as $docID => $score) {
	        $matchDocs[$docID] = $score/$index['docCount'][$docID];
	}

	arsort($matchDocs); // high to low

	return $matchDocs;
}

?>