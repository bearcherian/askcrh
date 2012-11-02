<?php
	require 'database.php';
	require 'connection.php';
	// New connection
	$twitter = new Twitter();
	$database = new Database('http://askcrh.indianbear.com', 'gr8bear_askcrh', 'csc8542', 'gr8bear_askcrh');
	// Get mentions
	$mentions = $twitter->getMentions();
	echo 'Mentions: ' , count($mentions) , '<hr>';
	foreach($mentions as $i=>$mention) {
		echo $mention->id_str;
		if(isset($mention->in_reply_to_status_id))
			echo ' (<i>replyto:', $mention->in_reply_to_status_id_str, '</i>)';
		echo '<br><br>&larr; ', $mention->text, ' (' . implode(', ', getHashTags($mention)) . ')<br>';
		// GET TWEET MESSAGE
		list($askcrh, $question) = explode(' ', $mention->text, 2);
		// IF THIS IS A REPLY, IT MAY BE AN ANSWER
		if($mention->in_reply_to_status_id == NULL) {
			echo ' - new question - <br>';
      // TODO: DETERMINE ANSWERER
			// TODO: SAVE QUESTION TO DATABASE
      mysql_query('INSERT INTO questions (tweet_id, asker, question, member) VALUES ("'.$mention->id_str.'","'.$mention->user->screen_name.'","'.$question.'",-1)');
			// ECHO REPLY TO MENTION'S ID (MUST INCLUDE USER NAME)
		} else {
			// TODO: Check reply id against database
			echo ' - answer - <br>';
		}
		echo '&rarr; ', $question, '<br><br>';
		if($i == 0) {
			$reply = $twitter->sendAnswer($mention->id_str, $mention->user->screen_name, 'ECHO: '. $question);
			var_dump($reply);
		} else echo 'Reply not sent (intentionally, not an error)';
		echo '<hr>';
	}
	
	/**
	 * Parse hashtags from a tweet object
	 */
	function getHashTags($tweet) {
		if(count($tweet->entities->hashtags) == 0)
			return array();
		$tags = array();
		foreach($tweet->entities->hashtags as $hashtag) {
			$tags[] = $hashtag->text;
		}
		return $tags;
	}
?>
