<?php

require_once('DBConnect.php');

class Topics {

	private $db;
	private $topics;

	public function __construct(){
		$db = new DBConnect();
		$this->loadTopics();
	}

	private function loadTopics() {
		$topics = null;
		$results = $db->query('SELECT DISTINCT topic FROM topics');
		$num = mysql_num_rows($results);
		for ($i = 0; $i < $num; $i++) {
			$topics[$i] = mysql_result($results,$i,"topic");
		}
		echo "...topics loaded...";
	}

	public function getTopics() {
		return $this->$topics;
	}

	public function addTopicMember ($topic, $member) {
		$results = $this->$db->query('SELECT * FROM topics WHERE topic = "' . $topic . '" AND member = "' . $member . '"');
		if (mysql_num_rows(results) > 0 ) return true;
		
		$this->$db->query('INSERT INTO topics (topic, member) VALUES ("' . $topic . '", "' . $member . '")');
		array_push($this->$topics,$topic);
	}

	public function removeTopicMember ($topic, $member) {
		$this->$db->query('DELETE FROM topics WHERE topic = "' . $topic . '" AND member = "' . $member . '"');
		$this->loadTopics();
	}

	public function removeAllMemberTopics ($member) {
		$this->$db->query('DELETE FROM topics WHERE member = "' . $member . '"');
		$this->loadTopics();

	}

	public function getMembersForTopic($topic) {
		$memberIds = null;
		$results = $this->$db->query('SELECT DISTINCT member FROM topics WHERE topic = "' . $topic . '"');
		$num = $mysql_num_rows($results);
		for ($i = 0; $i < $num; $i++) {
			$memberIds[$i] = mysql_result($results,$i,"member");
		}
		return $memberIds;
	}
}

?>
