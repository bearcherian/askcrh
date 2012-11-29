<?php
	class MentionFactory {
		public function create($tweet) {
			$base = new Mention($tweet);
			if($base->isReply() || in_array('answer', $base->getHashTags())) {
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
			$this->tweet = $tweet;
		}
		
		public function isReply() {
			return $tw
		}
		
		public function getHashTags() {
			if(count($this->tweet->entities->hashtags) == 0) return array();
			$tags = array();
			foreach($this->tweet->entities->hastags as $hashtag) {
				$tags[] = $hashtag->text;
			}
			return $tags;
		}
	}
	class Question extends Mention {
		public function __construct($tweet) {
		}
	}

	class Answer extends Mention {
		public function __construct($tweet) {
		}
	}
?>
