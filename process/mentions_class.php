<?php
	class MentionFactory {
		public function create($tweet) {
			$base = new Mention($tweet);
			if($base->getReplyId() !== false
			|| ($tweet->source == 'txt' && in_array('answer', $base->getHashTags()))) { // hashtag from text
				return new Answer($tweet);
			} else {
				return new Question($tweet);
			}
			return $base;
		}
	}
	
	class Mention {
		public $tweet;
		public function __construct($tweet) {
			list(,$text) = explode(' ', $tweet->text, 2);
			$hashes = array();
			foreach($tweet->entities->hashtags as $hashtag) {
				$hashes[] = $hashtag->text;
			}
			$this->tweet = array(
				'text' => $text,
				'id' => $tweet->id_str,
				'sender' => array(
					'handle' => $tweet->user->screen_name,
					'id' => $tweet->user->id_str
				),
				'reply_to_id' => $tweet->in_reply_to_status_id || false,
				'hashtags' => $hashes,
				'source' => $tweet->source
			);
		}
		
		public function getReplyId() {
			return $this->tweet->reply_to_id;
		}
		
		public function getHashTags() {
			return $this->tweet->hashtags;
		}
		
		public function getCommands() {
			$commands = array();
			preg_match_all('/![^ ]+/', $this->tweet->text, $commands);
			return array_map(function($op){return strToUpper(substr($op, 1));}, $commands[0]);
		}
	}
	
	class Question extends Mention {		
		public function save($db) {
			//$db->saveQuestion($this->tweet->id_str, $asker, $question
		}
	}

	class Answer extends Mention {		
		public function save($db) {
			//$db->saveAnswer($tweet_id, $member_id, $answer)
		}
	}
?>
