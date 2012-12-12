<?php

require_once('DBConnect.php');

class Topics {

	private $topics;

	public function __construct(){
		$this->loadTopics();
	}

	private function loadTopics() {
		$this->topics = array();
		$results = mysql_query('SELECT DISTINCT topic FROM topics');
		$num = mysql_num_rows($results);
		for ($i = 0; $i < $num; $i++) {
			$this->topics[$i] = mysql_result($results,$i,"topic");
		}
	}

	public function getTopics() {
		return $this->topics;
	}

	public function addTopicMember ($topic, $member) {
		$results = mysql_query('SELECT * FROM topics WHERE topic = "' . $topic . '" AND member = "' . $member . '"');
		if (mysql_num_rows($results) > 0 ) return true;
		
		mysql_query('INSERT INTO topics (topic, member) VALUES ("' . $topic . '", "' . $member . '")');
		array_push($this->topics,$topic);
	}

	public function removeTopicMember ($topic, $member) {
		mysql_query('DELETE FROM topics WHERE topic = "' . $topic . '" AND member = "' . $member . '"');
		$this->loadTopics();
	}

	public function removeAllMemberTopics ($member) {
		mysql_query('DELETE FROM topics WHERE member = "' . $member . '"');
		$this->loadTopics();

	}

	public function getMembersForTopic($topic) {
		$memberIds = array();
		$results = mysql_query('SELECT DISTINCT member FROM topics WHERE topic = "' . $topic . '"');
		$num = mysql_num_rows($results);
		for ($i = 0; $i < $num; $i++) {
			$memberIds[$i] = mysql_result($results,$i,"member");
		}
		return $memberIds;
	}
	
	public function getTopicsForMember($id) {
		$results = mysql_query('SELECT topic FROM topics WHERE member = "' . $id . '"');
		$num = mysql_num_rows($results);
		for ($i = 0; $i < $num; $i++) {
			$mtop[$i] = mysql_result($results,$i,"topic");
		}
		return $mtop;
	}
}

?>
