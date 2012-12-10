<?php
	require '/home/gr8bear/askcrh/data/dbconnection.php';
	require 'twitterConnect.php';
	require 'mentions_class.php';
	
	// New connection
	$twitter = new Twitter();
	$database = new Database('localhost', 'gr8bear_askcrh', 'csc8542', 'gr8bear_askcrh');
	// Get mentions
	$mentions = $twitter->getMentions();
	$factory = new MentionFactory();
	echo 'Mentions: ' , count($mentions) , '<hr>';
	// Foreach mention
	foreach($mentions as $i=>$tweet) {
		// Create an object from the factory
		$mention = $factory->create($tweet);
		// Check for commands
		$commands = $mention->getCommands();
		if(!empty($commands)) {
			// Handle commands
			foreach($commands as $command) {
				switch($command) {
					case 'JOIN':
					case 'SIGNUP':
					case 'REGISTER':
						// add member
						$twitter->addMember($mention->sender->id, $mention->sender->handle);
						break;
					case 'TOPIC':
					case 'TOPICS':
						// update member topics
						break;
					case 'DELETE':
					case 'REMOVE':
						// remove topics
						break;
					case 'SKIP':
						// reassign question
						break;
					case 'UNJOIN':
					case 'QUIT':
						// remove member
						$twitter->removeMember($mention->sender->id);
						break;
					default:
						// Send help command
						$twitter->send(
							'ASKCRH COMMANDS: !JOIN - become a member, !TOPICS - set member topics, !SKIP - skip question, !QUIT - leave the hub',
							array('handle'=>$mention->sender->handle, 'id'=>$mention->id)
						);
				}
			}
			var_dump($commands);
		} else {
			if(get_class($mention) == 'Answer') {
				echo ' - answer - <br>';
				// Check reply id against database
				$question = $database->getQuestionById($mention->reply_to_id);
				var_dump($question);
				if($question == false) {
					// TODO: Check for unanswered question in database
					$mention = new Question($tweet);
				} else {
					// Save answer
					$mention->save($database);
					// Send answer to asker
					$answer = $twitter->send($mention->text, array('handle'=>$question['asker'], 'id'=>$question['question_id']));
				}
			}
			if(get_class($mention) == 'Question') {			// Answers
				echo ' - new question - <br>';
				$mention->save($database);
				$mention->confirm($twitter);
				// get member
				$member = $database->getMemberByTopic($mention->hashtags);
				// deligate question as reply
				$reply = $twitter->send(
					'(' . $member['handle'] . ') ' . $mention->text,
					array('id'=>$mention->id, 'handle'=>$mention->sender->handle)
				);
				var_dump($reply);
				// save deligation
				$database->saveAssignment($mention->id, $member['id'], $reply->id_str);
			}
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
