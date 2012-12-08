<?php
	class MentionFactory {
		public function create($tweet) {
			$base = new Mention($tweet);
			if($base->getReplyId() || ($base->source == 'txt' && in_array('answer', $base->hashtags))) { 
				return new Answer($tweet);
			} else {
				return new Question($tweet);
			}
			return $base;
		}
	}
	
	class Mention {
		public $id, $text, $sender, $reply_to_id, $hashtags, $source;
		public function __construct($tweet) {
			$this->id = $tweet->id_str;
			$this->reply_to_id = $tweet->in_reply_to_status_id_str ?: false;
			$this->source = $tweet->source;
			$this->sender = array(
				'handle' => $tweet->user->screen_name,
				'id' => $tweet->user->id_str
			);
			list(,$this->text) = explode(' ', $tweet->text, 2);
			$this->hashtags = array();
			foreach($tweet->entities->hashtags as $hashtag) {
				$this->hashtags[] = $hashtag->text;
			}
		}
		
		public function getReplyId() {
			return $this->reply_to_id;
		}
		
		public function getHashTags() {
			return $this->hashtags;
		}
		
		public function getCommands() {
			$commands = array();
			preg_match_all('/![^ ]+/', $this->text, $commands);
			return array_map(function($op){return strToUpper(substr($op, 1));}, $commands[0]);
		}
	}
	
	class Question extends Mention {		
		public function save($db) {
			$db->saveQuestion($this->id, $this->sender['handle'], $this->text);
		}
		
		public function confirm($twitter) {
			return $twitter->send(
				'Your question has been received and sent to a member! Expect an answer soon.',
				array('handle'=>$this->sender['handle'], 'id'=>$this->id)
			);
		}
	}

	class Answer extends Mention {		
		public function save($db) {
			$db->saveAnswer($this->id, $this->sender['handle'], $this->text);
		}
	}
?>
