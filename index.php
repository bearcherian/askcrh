<?php
	// Abraham William's OAuth Library
	require 'library/abraham_twitter.php';
	// App registered through @askcrh
	$consumer_key    = 'v2uncgaHPPWupobjcemw';
	$consumer_secret = 'Gu9fhLCeKKJdxHRBzZnfrYu1mD8FJF2aibacqybuA8';
	$access_token    = '817731343-CrSPHS0vBMCZ13oHIyImEu5fIwbiypuW85UIow1Y';
	$access_secret   = 'QCPI1xNChDE9jHmWREOgBoBvaykkrWvdiwyZe4pHw';
	// Create connection
	$twitter = new TwitterOAuth($consumer_key,$consumer_secret,$access_token,$access_secret);
	// Get all replies since our last question
	// TODO: Get id of last question
	$last_id = 261242929593589760;
	$mentions = $twitter->get(
		$twitter->host . 'statuses/mentions.json',
		array(
			'since_id'  => $last_id,
			'trim_user' => true
		)
	);
	var_dump($mentions);
	$mention_text = substr($mentions[0]->text, strpos($mentions[0]->text, ' '));
	echo '<hr>';
	// Send an echo reply back at the tweet's id
	$reply = $twitter->post(
		$twitter->host . 'statuses/update.json',
		array(
			'status' => 'ECHO: '. $mention_text,
			'in_reply_to_status_id' => $mentions[0]->id
		)
	);
	var_dump($reply);
?>
