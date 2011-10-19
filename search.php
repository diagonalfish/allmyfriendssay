<?php
include "include/header.php";

include "include/autolink.php";

$querywords = preg_split("/\W+/", strtolower($_SESSION['search']['query']));

/*
echo sizeof($_SESSION['search']['tweets']);
print_r($_SESSION['search']['tweets']);
echo "<br/>";

foreach($_SESSION['search']['tweets'] as $tweetArray) {
	$tweet = $tweetArray['tweet'];
	$sentiment = $tweetArray['sentiment'];
	echo "<b>".$tweet['user']['screen_name']."</b>: ".$tweet['text']." <img src='img_sentiment.php?value=".$sentiment."'/><i>(".$sentiment.")</i><br/>";
}

echo "<br/><br/><b>Average Sentiment Score: ".$_SESSION['search']['avgSentiment']."</b>";

*/
?>

<div id="searchpage-container">
	<div id="title">All My Friends Say</div>
	<div style="height: 20px"></div>
	
	<div id="searchpage-avgsentiment">Average Sentiment: <b><?php echo $_SESSION['search']['avgSentiment']; ?></b><br/>
		<img src="img_sentiment.php?value=<?php echo $_SESSION['search']['avgSentiment']; ?>"/>
	</div>
	Your Twitter: <img style="max-width: 20px" src="<?php echo $_SESSION['userinfo']['profile_image_url']; ?>"/>
          <?php echo $_SESSION['userinfo']['screen_name']; ?><br/>
	
	<div id="searchpage-resultsfor">Results for: <b><?php echo $_SESSION['search']['query']; ?></b></div>
	<div style="margin: 10px 0 10px 0; height: 20px"><a href="."><< Search Again</a></div>
	
<?php
foreach($_SESSION['search']['tweets'] as $tweetArray) {
	$tweet = $tweetArray['tweet'];
	$sentiment = $tweetArray['sentiment'];
	
	
	
	//print_r($tweet);
	//break;
?>
	<div class="searchpage-tweet">
		<img style="float: left; margin: 0px 5px 5px 0;" src="<?php echo $tweet['user']['profile_image_url_https']; ?>"/>
		<div style="float: right; display: table-cell; vertical-align: middle;">
			<img src="img_sentiment.php?value=<?php echo $sentiment; ?>"/> <?php echo $sentiment; ?>
		</div>
		<b><span style="color: #5E5E5E"><?php echo $tweet['user']['screen_name'];?></span></b>
		<span style="color: #c0c0c0;"><?php echo $tweet['user']['name']; ?></span>
	
		<div style="margin: 10px 0 5px 0;"><?php echo autolink(highlight_words($tweet['text']));?></div>
		<a target="_blank" style="font-size: 12px; color: #5e5e5e;" 
			href="http://twitter.com/#!/<?php echo $tweet['user']['screen_name']."/status/".$tweet['id_str']; ?>">
		<?php $time = strtotime($tweet['created_at']); echo date("g:i a, F j, Y", $time); ?>
		</a>
		</span>
	</div>
<?php

}
?>
</div>

<?php
include "include/footer.php";

function highlight_words($text) {
	global $querywords;
	foreach ($querywords as $word) {
		$text = highlight($word, $text);
		
		$ind = stripos($text, $word); 
		$len = strlen($word); 
		if($ind !== false){ 
			$text = substr($text, 0, $ind) . "<b>" . substr($text, $ind, $len) . "</b>" . 
				highlight($word, substr($text, $ind + $len)); 
		} 
	}
	return $text;
} 

function highlight($needle, $haystack){ 
    $ind = stripos($haystack, $needle); 
    $len = strlen($needle); 
    if($ind !== false){ 
        return substr($haystack, 0, $ind) . "<b>" . substr($haystack, $ind, $len) . "</b>" . 
            highlight($needle, substr($haystack, $ind + $len)); 
    } else return $haystack; 
} 


?>