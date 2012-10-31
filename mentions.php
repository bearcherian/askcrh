<?php
function my_streaming_callback($data, $length, $metrics){
	echo $data.PHP_EOL;
return file_exists(dirname(__FILE__) . '/STOP');
}

require("/library/tmhOAuth/tmhOAuth.php");
require("/library/tmhOAuth/tmhUtilities.php");
$tmhOAuth = new tmhOAuth();

$method = "https://api.twitter.com/1.1/statuses/mentions_timeline.json";
$params = array(

);
$tmhOAuth->streaming_request('GET',$metho, $params, 'my_streaming_callback', false);

tmhUtilities::pr($tmhOAuth);

?>
