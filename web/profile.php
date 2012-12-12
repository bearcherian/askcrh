<?php
/**
 * @file
 * User has successfully authenticated with Twitter. Access tokens saved to session and DB.
 */

/* Load required lib files. */
session_start();
require_once('../library/abraham_twitter.php');
require_once('config.php');
require_once('../data/dbconnection.php');
require_once('../data/Members.php');
require_once('../data/Topics.php');

$members = new Members();
$topics = new Topics();

/* If access tokens are not available redirect to connect page. */
if (empty($_SESSION['access_token']) || empty($_SESSION['access_token']['oauth_token']) || empty($_SESSION['access_token']['oauth_token_secret'])) {
    header('Location: ./clearsessions.php');
}
/* Get user access tokens out of the session. */
$access_token = $_SESSION['access_token'];

/* Create a TwitterOauth object with consumer/user tokens. */
$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $access_token['oauth_token'], $access_token['oauth_token_secret']);

/* If method is set change API call made. Test is called by default. */
$content = $connection->get('account/verify_credentials');

/* Some example calls */
//$connection->get('users/show', array('screen_name' => 'abraham'));
//$connection->post('statuses/update', array('status' => date(DATE_RFC822)));
//$connection->post('statuses/destroy', array('id' => 5437877770));
//$connection->post('friendships/create', array('id' => 9436992));
//$connection->post('friendships/destroy', array('id' => 9436992));

/* Include HTML to display on the page */
if (isset($content->id)) {
	$newMember = $members->addMember($content->id, $content->screen_name);
	$id = $content->id;
	$handle = $content->screen_name;
	$ts = $topics->getTopicsForMember($id);
}

$status = "";

if ($newMember) {
	$status = $handle . " is now a member of CRH";
}
?>
<!DOCTYPE html>
<html>
<head>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<script type="text/javascript">
function addTopic(strTopic) {
	$.ajax({
                type: "POST",
                url: "addTopic.php",
                data: "topic=" + strTopic + "&member=" + <?=$id?>,
                timeout: 5000,
                success: function() {
                	document.location.reload(true);
		},
                error: function() {
                        alert("Unable to add topics at this time");
                }
        });
}

$(document).ready(function() {
	$('#topic_submit').click(function() {
		addTopic($('input#topics').val());
	});
});
</script>
</head>
<body>
<div id="status"><?=$status?></div>
<div id="profile">
<h1>Profile for <?=$handle?></h1>
<h3>topics</h3>
<ul>
<?
	if($ts == null) {
		echo "No topics";
	} else {
		foreach ($ts as $t) {
			echo "<li>" . $t . "</li>";
		}
	}
?>
<ul>
Add Topics: <input type="text" name="topics" id="topics" /><input type="button" id="topic_submit" value="topic">
</div>
<?
echo "<!-- info for debugging";
echo var_dump($content);
echo "//-->"; //include('html.inc');
?>
</body>
