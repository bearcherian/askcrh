<?php
	require '/home/gr8bear/askcrh/data/dbconnection.php';
	require 'twitterConnect.php';
	// New connection
	$twitter = new Twitter();
	$database = new Database('localhost', 'gr8bear_askcrh', 'csc8542', 'gr8bear_askcrh');
	// Get mentions
	$mentions = $twitter->getMentions();
	echo 'Mentions: ' , count($mentions) , '<hr>';
	foreach($mentions as $i=>$mention) {
		echo $mention->id_str;
		if(isset($mention->in_reply_to_status_id))
			echo ' (<i>replyto:', $mention->in_reply_to_status_id_str, '</i>)';
		echo '<br><br>&larr; ', $mention->text, ' (' . implode(', ', getHashTags($mention)) . ')<br>';
		// Get tweet message
		list($askcrh, $tweet_text) = explode(' ', $mention->text, 2);
		// If this is a reply, it may be an answer
		if($mention->in_reply_to_status_id == NULL) {
			echo ' - new question - <br>';
			// Save question to database
      $database->saveQuestion($mention->id_str, $mention->user->screen_name, $tweet_text);
      // TODO: DETERMINE ANSWERER
			$member = $database->getMemberByTopic();
			echo '&rarr; ', $member['handle'], ' (', $member['id'], ')<br>';
			// Send tweet
			$reply = $twitter->sendTweet('@crhallberg (' . $member['handle'] . ') ' . $tweet_text);
			// Save assignment to database
			if(isset($reply->id_str)) {
				$database->saveAssignment($mention->id_str, $member['id'], $reply->id_str);
			}
		} else {
			// TODO: CHECK FOR COMMANDS
			echo ' - answer - <br>';
			// TODO: CHECK REPLY ID AGAINST DATABASE
			// TODO: SAVE ANSWER
      mysql_query('INSERT INTO answers (tweet_id, member, answer) VALUES ("'.$mention->id_str.'","'.$mention->user->screen_name.'","'.$tweet_text.'")');			
		}
		
		echo '&rarr; ', $tweet_text, '<br><br>';
		// Echo reply to mention's id (must include user name)
		$reply = $twitter->sendTweet('ECHO: '. $tweet_text, array('handle'=>$mention->user->screen_name, 'id'=>$mention->id_str));
		// Save assignment
		var_dump($reply);
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
