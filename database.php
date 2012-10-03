<?php
	class Database {
		private $connect;
		
		/**
		 * Constructor - connects to the database
		 */
		Database($host, $username, $password, $db) {
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
			return mysql_query($query) or die($query.'<br>'.mysql_error());
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
			if(empty($topics)) {
				// RANDOM MEMBER
				$members = $this->query('SELECT DISTINCT(handle) FROM members WHERE id NOT IN (SELECT member FROM questions)'));
			} else {
				$members = $this->query('SELECT DISTINCT(members.handle), COUNT(topics.member) FROM members JOIN topics ON (topics.member = members.id AND (topics.topic = "'.implode('" OR topics.topic = "', $topics).'")) WHERE members.id NOT IN (SELECT member FROM questions)');
			}
			// TODO: Send member with most matches
			$member = mysql_fetch_array($members);
			return $member['handle'];
		}
		
		/**
		 * Save a question to the database to ensure members don't get overloaded
		 * and that the reply can be sent
		 *
		 * @param integer $id       - Twitter ID of the tweet we sent to member
		 * @param string  $asker    - Handle of the asker
		 * @param string  $member   - Handle of the member question is assigned to
		 * @param string  $question - Content of the tweeted question
		 *
		 * @return boolean of success
		 */
		public function saveQuestion($id, $asker, $member, $question) {
			return $this->query('INSERT INTO questions (id, asker, member, content) SELECT '.$id.', "'.$asker.'", '.members.id.', "'.$question.'" FROM members WHERE members.handle = "'.$member.'"');
		}
		
		/**
		 * Remove a question from the database
		 */
		public function removeQuestion() {
		}
		
		/**
		 * Get the handle that a reply needs to be sent to
		 *
		 * @param integer $id - Unique Twitter ID of the tweet this is a reply to
		 * 
		 * @return string asker Twitter handle
		 */
		public function answerTarget($id) {
			$askers = $this->query('SELECT asker FROM questions WHERE id = '.$id);
			$asker = mysql_fetch_array($askers);
			return $asker['asker'];
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