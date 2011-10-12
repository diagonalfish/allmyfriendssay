<?php

//Create redis db connection
$redis = new Redis();
$redis->connect('127.0.0.1');

function redis_get($key) {
	global $redis;
	$result = $redis->get($key);
	if (!$result) return 0;
	return unserialize($result);
}

function redis_set($key, $obj) {
	global $redis;
	$redis->setex($key, 3600, serialize($obj));
}

?>