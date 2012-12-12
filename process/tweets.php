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
	for($i=count($mentions);$i-- > 0;) {
		// Create an object from the factory
		$mention = $factory->create($mentions[$i]);
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
						$twitter->send(
							'Welcome to the Hub! IMPORTANT: Please reply to answers with an #answer hashtag! KTHNXBYE! Send !HELP for more.',
							array('handle'=>$mention->sender->handle, 'id'=>$mention->id)
						);
						break;
					case 'TOPIC':
					case 'TOPICS':
						// update member topics
						$twitter->send(
							"Topics updated! You're so smart! ;)",
							array('handle'=>$mention->sender->handle, 'id'=>$mention->id)
						);
						break;
					case 'DELETE':
					case 'REMOVE':
						// remove topics
						break;
					case 'SKIP':
						// reassign question
					case 'SPAM':
						// delete old assignment
						break;
					case 'UNJOIN':
					case 'QUIT':
						// remove member
						$twitter->removeMember($mention->sender->id);
						$twitter->send(
							"Sorry to see you go! It's been fun!",
							array('handle'=>$mention->sender->handle, 'id'=>$mention->id)
						);
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
				if($question == false) {
					// Match text message
					$question = $database->pendingQuestions($mention->sender);
					if($question == false) {
						$mention = new Question($tweet);
					}
				}
				if($question != false) {
					// Save answer
					$mention->save($database);
					echo 'SAVED!';
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
					$mention->text,
					array('id'=>$mention->id, 'handle'=>$member['handle'])
				);
				// save deligation
				//if(!isset($reply->id_str)) {
					//$twitter->send('Sorry! There was an error! Please re-send your question.', array($mention->sender));
					// delete assignment
				//} else {
					$database->saveAssignment($mention->id, $member['id'], $reply->id_str);
				//}
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
