<?php
include "include/header.php";

/*
echo "Your twitter username is ".$_SESSION['userinfo']['screen_name']." and your profile picture is <img src=\"{$_SESSION['userinfo']['profile_image_url']}\">";
*/
?>
    <div id="index-container">
        <div id="title">All My Friends Say</div>
        <div id="index-subtitle">Search your local Twitter network and see what they think about a topic!</div>

        <div id="index-searchdiv">
<?php
if (!$notoken) {
?>
          Your Twitter: &nbsp;<img src="<?php echo $_SESSION['userinfo']['profile_image_url']; ?>" width="20"/>
          <?php echo $_SESSION['userinfo']['screen_name']; ?> [<a href="logout.php">Logout</a>]<br/>

          <input type="text" id="index-searchinput" value="Enter Search Keywords"></input>&nbsp;
          <span style="font-size: 25px">Depth:</span>
            <select id="index-searchdepth" disabled>
              <option value="1">1</option>
              <option value="2">2</option>
          </select>
          <br/>
          <button id="index-searchbutton">Search!
          </button>
          <div id="search-status" style="display:none"><img src="images/ajax-loader.gif"/> <span id="search-status-message"></span></div>
		  <div id="search-error" style="display:none"></div>
<?php
}
else {
?>
          <div style="height: 8px"></div>
          <span style="font-size: 20px">In order to use AllMyFriendsSay, you need to authorize this app with Twitter. Click the button below.</span>
          <button id="index-authbutton" url="<?php echo $twitter->getAuthorizationUrl(); ?>"><img src="images/bird_16_blue.png"/> Authorize with Twitter</button>
        </div>
    </div>
<?php
}
?>
<script type="text/javascript" src="js/index.js"></script>
<?php
include "include/footer.php";
?>
