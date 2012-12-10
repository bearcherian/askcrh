<?php
	// Abraham William's OAuth Library
	require '/home/gr8bear/askcrh/library/abraham_twitter.php';

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
		 * Get all replies since our last question
		 *
		 * @return array of twitter objects
		 */
		public function getMentions() {
			// Pull last id from database
			$lq = mysql_query('SELECT MAX(id) as max FROM (SELECT MAX(tweet_id) AS id FROM questions UNION SELECT MAX(tweet_id) AS id FROM answers) AS temp');
			$latest = mysql_fetch_array($lq);
			$latest['max'] = '278024574514372610'; // testing
			echo 'Last tweet: ', $latest['max'], '<br><br>';
			// Get all mentions since last id
			$mentions = $this->query('statuses/mentions',array('since_id'=>$latest['max'],'include_entities'=>true));
			return $mentions;
		}
		
		/**
		 * Send an echo reply back at the tweet's id
		 *
		 * @param string $message - String we're sending along as the message
		 * @param string $reply   - Data for reply : 'handle' and 'id';
		 *
		 * @return array of reply data
		 */
		public function send($message = '', $reply = array()) {
			if($message == '') return false;
			if(empty($reply)) {
				$params = array('status' => $message);
			} else {
				$params = array(
					'status' => '@' . $reply['handle'] . ' ' . $message,
					'in_reply_to_status_id' => $reply['id']
				);
			}
			echo '&rarr; ', $params['status'], '<br><br>';
			//return $this->query('statuses/update', $params, 'POST');
		}
	}
?>
