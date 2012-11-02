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
			$access_token    = '817731343-1xrD8maWqbnrsT3s0t27uQEpeko7lE3uAyG7B0kI';
			$access_secret   = 'eBT5PxIxWOI5RhW0eAtgPWaNeqTlHYHfgCatxGyfc';	
			// Create connection
			$this->connection = new TwitterOAuth($consumer_key,$consumer_secret,$access_token,$access_secret);
		}
		
		/**
		 *
		 */
		public function query($service = '', $params = array(), $type = 'get', $format = 'json') {
			if($service == '') return 'Error: no url specified';
			$type = strtolower($type);
			if($type == 'get') {
				return $this->connection->get(
					$this->connection->host . $service . '.' . $format,
					$params
				);
			} elseif($type == 'post') {
				return $this->connection->post(
					$this->connection->host . $service . '.' . $format,
					$params
				);
			}
		}
		
		/**
		 * Get mentions since the last mention
		 *
		 * @return array of twitter objects
		 */
		public function getMentions() {
			// Get all replies since our last question
			// TODO: PULL LAST ID FROM DATABASE
			$lq = mysql_query('SELECT MAX(tweet_id) FROM questions');
			$latest = mysql_fetch_array($lq);
			$latest['tweet_id'] = "261242929593589760";
			$mentions = $this->query('statuses/mentions',array('since_id'=>$latest['tweet_id'],'include_entities'=>true));
			return $mentions;
		}
		
		/**
		 * Send an echo reply back at the tweet's id
		 *
		 * @param string $question_id - unique ID of the tweet we're replying to
		 * @param string $answer      - String we're sending along as the answer
		 *
		 * @return array of reply data
		 */
		public function sendAnswer($id = '', $handle = '', $answer = '') {
			if($id == '' || $handle == '' || $answer == '') return false;
			return $this->query('statuses/update',
				array(
					'status' => '@' . $handle . ' ' . $answer,
					'in_reply_to_status_id' => $id
				), 'POST');
		}
	}
?>