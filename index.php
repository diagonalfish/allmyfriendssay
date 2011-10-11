<?php
include "header.php";

/*
echo "Your twitter username is ".$_SESSION['userinfo']['screen_name']." and your profile picture is <img src=\"{$_SESSION['userinfo']['profile_image_url']}\">";
*/
?>
    <div id="index-container">
        <div id="index-title">All My Friends Say</div>
        <div id="index-subtitle">Search your local Twitter network and see what they think about a topic!</div>

        <div id="index-searchdiv">
<?php
if (!$notoken) {
  if (!isset($_SESSION['userinfo'])) {
    $twitterInfo = $twitterObj->get_accountVerify_credentials();
    $_SESSION['userinfo'] = $twitterInfo->response;
  }
?>
          Your Twitter: &nbsp;<img src="<?php echo $_SESSION['userinfo']['profile_image_url']; ?>" width="20"/>
          <?php echo $_SESSION['userinfo']['screen_name']; ?><br/>

          <input type="text" id="index-searchinput" value="Enter Search Keywords"></input>&nbsp;
          <span style="font-size: 25px">Depth:</span>
            <select id="index-searchdepth">
              <option value="1">1</option>
              <option value="2">2</option>
          </select>
          <br/>
          <button id="index-searchbutton">Search!
          </button>
<?php
}
else {
?>
          <span style="font-size: 20px">In order to use AllMyFriendsSay, you need to authorize this app with Twitter. Click the button below.</span>
          <button id="index-authbutton" url="<?php echo $twitterObj->getAuthorizationUrl(); ?>"><img src="images/bird_16_blue.png"/> Authorize with Twitter</button>
        </div>
    </div>

<script type="text/javascript" src="index.js"></script>
<?php
}
include "footer.php";
?>
