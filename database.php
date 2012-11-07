<?php
	class Database {
		private $connect;
		
		/**
		 * Constructor - connects to the database
		 */
		public function __construct($host, $username, $password, $db) {
			$connect = mysql_connect($host, $username, $password);
			mysql_select_db($db);
		}
		
		/**
		 * Perform query
		 *
		 * Consistent error handling
		 *
		 * @param string $query - the MYSQL query to be run
		 *
		 * @return MYSQL_Array
		 */
		private function query($query) {
			$query = mysql_query($query) or die($query.'<br>'.mysql_error());
			return $query;
		}
		
		/**
		 * Get a member to ask a question to by topic
		 * 
		 * Empty topic array returns next available member
		 *
		 * @param array $topics - List of topics to match
		 *
		 * @return string - member handle
		 */
		public function getMemberByTopic($topics = array()) {
			$aq = array();
			if(true || empty($topics)) {
				// Member least recently sent a question
				$aq = $this->query('SELECT handle,MIN(id) AS id FROM members WHERE id NOT IN (SELECT member AS id FROM assignments)');
				$test = mysql_fetch_array($aq);
				mysql_data_seek($aq, 0);
				if(mysql_num_rows($aq) == 0 || $test['handle'] == NULL) {
					$aq = $this->query('SELECT DISTINCT members.handle as handle, assignments.member as id FROM members JOIN assignments ON members.id = assignments.member ORDER BY assignments.sent_id ASC');
				}
			} else {
				$members = $this->query('SELECT DISTINCT(members.handle), COUNT(topics.member) FROM members JOIN topics ON (topics.member = members.id AND (topics.topic = "'.implode('" OR topics.topic = "', $topics).'")) WHERE members.id NOT IN (SELECT member FROM questions)');
			}
			// TODO: Send member with most matches
			$member = mysql_fetch_array($aq);
			return $member;
		}
		
		/**
		 * Save a question to the database to ensure members don't get overloaded
		 * and that the reply can be sent
		 *
		 * @param integer $tweet_id - Twitter ID of the mention
		 * @param integer $asker    - Twitter handle of the asker
		 * @param string  $question - Content of the tweeted question
		 *
		 * @return boolean of success
		 */
		public function saveQuestion($tweet_id, $asker, $question) {
			return $this->query('INSERT INTO questions (tweet_id, asker, question) VALUES ("'.$tweet_id.'", "'.$asker.'", "'.$question.'")');
		}
		
		/**
		 * Save a question to the database to ensure members don't get overloaded
		 * and that the reply can be sent
		 *
		 * @param integer $tweet_id  - Tweet ID of the question
		 * @param integer $member_id - ID of the member question has been assigned to
		 * @param string  $sent_id   - Tweet ID of the tweet sent to the member
		 *
		 * @return boolean of success
		 */
		public function saveAssignment($tweet_id, $member_id, $sent_id) {
			return $this->query('INSERT INTO assignments (question_id, member, sent_id) VALUES ("'.$tweet_id.'", '.$member_id.', "'.$sent_id.'")');
		}
		
		/**
		 * Return the id of the most recent question
		 *
		 * return string of id
		 */
		public function getSinceId() {
			$tq = $this->query('SELECT MAX(tweet_id) FROM questions');
			$tweet = mysql_fetch_row($tq);
			return $tweet['tweet_id'];
		}
		
		/**
		 * Remove a question from the database
		 */
		public function removeQuestion() {
		}
		
		/**
		 * Save a member with his specialty topics if they're not already in the database
		 *
		 * @param string $
		 *
		 * @return boolean of success
		 */
		public function addMember($handle, $topics = array()) {
			$exists = $this->query('SELECT id FROM members WHERE handle="'.$handle.'"');
			if(mysql_num_rows($exists) > 0) return false;
			$this->query('INSERT INTO members(handle) VALUES ("'.$handle.'")');
			foreach($topics as $topic) {
				$this->query('INSERT INTO members(handle) VALUES ("'.$handle.'")');
			}
			return true;
		}
		
		/**
		 * Remove a member (and their topics) from the database
		 *
		 * @param string $handle - of member to be removed
		 *
		 * @return boolean of success
		 */
		public function removeMember($handle) {
			$id = $this->query('SELECT id FROM members WHERE handle = "'.$handle.'"');
			$id = mysql_fetch_array($id);
			return $this->query('DELETE FROM members WHERE handle = "'.$handle.'"')
				&& $this->query('DELETE FROM topics WHERE member = '.$id['id']);
		}
		
		public function close() {
			mysql_close($connect);
		}
	}
?>