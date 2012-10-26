<?php
	// Abraham William's OAuth Library
	require 'library/abraham_twitter.php';

	class Twitter {
		/**
		 * Connection object via Abraham's TwitterOAuth library
		 */
		protected $connection;
		
		/**
		 * Constructor
		 */
		public function __construct() {
			// App registered through @askcrh
			$consumer_key    = 'v2uncgaHPPWupobjcemw';
			$consumer_secret = 'Gu9fhLCeKKJdxHRBzZnfrYu1mD8FJF2aibacqybuA8';
			$access_token    = '817731343-CrSPHS0vBMCZ13oHIyImEu5fIwbiypuW85UIow1Y';
			$access_secret   = 'QCPI1xNChDE9jHmWREOgBoBvaykkrWvdiwyZe4pHw';
			// Create connection
			$this->connection = new TwitterOAuth($consumer_key,$consumer_secret,$access_token,$access_secret);
		}
		
		/**
		 * Get mentions since the last mention
		 *
		 * @return array of twitter objects
		 */
		public function getMentions() {
			// Get all replies since our last question
			// TODO: Get id of last question from DB
			$last_id = '261242929593589760'; // Has to be a string
			$mentions = $this->connection->get(
				$this->connection->host . 'statuses/mentions.json',
				array(
					'since_id'  => $last_id,
					'trim_user' => true
				)
			);
			return $mentions;
		}
		
		/**
		 * Send an echo reply back at the tweet's id
		 *
		 * @param string $question_id - unique ID of the tweet we're replying to
		 * @param string $answer      - String we're sending along as the answer
		 *
		 * @return boolean of success
		 */
		public function sendAnswer($id = '', $answer = '') {
			if($id == '' || $answer == '') return false;
			echo '&rarr; ', $answer, '<hr>';
			$reply = $this->connection->post(
				$this->connection->host . 'statuses/update.json',
				array(
					'status' => $answer,
					'in_reply_to_status_id' => $id
				)
			);
			return $reply; // TODO: Make boolean
		}
	}
?>