<?php
	require 'connection.php';
	// New connection
	$twitter = new Twitter();
	// Get mentions
	$mentions = $twitter->getMentions();
	var_dump($mentions);
	$mention_text = substr($mentions[0]->text, strpos($mentions[0]->text, ' ')+1);
	echo '<hr>&larr; ', $mention_text, '<hr>';
	var_dump($twitter->sendAnswer($mentions[0]->id_str, 'ECHO: '. $mention_text));
?>
