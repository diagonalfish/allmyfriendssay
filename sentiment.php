<?php

//Load word sentiment database
$sentiment_db = unserialize(file_get_contents("sentiment.db"));

function sentiment($input) {
	global $sentiment_db;

	$words = preg_split("/\W+/", strtolower($input));

	$sum = 0;
	foreach($words as $word) {
		if (array_key_exists($word, $sentiment_db)) {
			$sum += $sentiment_db[$word];
		}
	}
	return floatval($sum)/sqrt(sizeof($words));
}

?>