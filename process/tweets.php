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
		echo '<br><br>&larr; ', $mention->text, ' (' . implode(', ', getHashTags($mention)) . ')<br>&larr; ', $mention->user->screen_name,'<br>';
		// Get tweet message
		list($askcrh, $tweet_text) = explode(' ', $mention->text, 2);
		// TODO: CHECK FOR COMMANDS
		// If this is a reply, it may be an answer
		if($mention->in_reply_to_status_id == NULL) {
			echo ' - new question - <br>';
			// Save question to database
      $database->saveQuestion($mention->id_str, $mention->user->screen_name, $tweet_text);
      // TODO: DETERMINE ANSWERER
			$member = $database->getMemberByTopic();
			echo '&rarr; ', $member['handle'], ' (', $member['id'], ')<br>';
			// Send tweet
			$reply = $twitter->send('@crhallberg (' . $member['handle'] . ') ' . $tweet_text);
			// Save assignment to database
			if(isset($reply->id_str)) {
				$database->saveAssignment($mention->id_str, $member['id'], $reply->id_str);
			}		
			// Send validating reply to mention's id (must include user name)
			$reply = $twitter->send('Your question has been received and sent to a member! Expect an answer soon.', array('handle'=>$mention->user->screen_name, 'id'=>$mention->id_str));
			// Save assignment
			var_dump($reply);
		} else {
			echo ' - answer - <br>';
			// Check reply id against database
			$question = $database->getQuestionById($mention->in_reply_to_status_id_str);
			// Save answer
      $database->saveAnswer($mention->id_str, $question['member'], $tweet_text);
			// Send answer to asker
			$answer = $twitter->send($tweet_text, array('handle'=>$question['asker'],'id'=>$question['question_id']));
			var_dump($answer);
		}
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
