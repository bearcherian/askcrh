<?php
require_once('DBConnect.php');

class Members {
	private $db;
	private $members; //$members[id]

	public function __construct() {
		$db = new DBConnect();
		$db->connect();
		$results = $db->query('SELECT * FROM members');
		$num = mysql_num_rows($results);
		echo "Member results: " . $num;
		for ( $i = 0; $i < $num; $i++) {
			$id = mysql_result($results,$i,"id");
			$handle = mysql_result($results, $i, "handle"); 
			$members[$id] = $handle;		}
	}

	public function getMembers() {
		return $members;
	}

	public function addMember($id, $handle) {
		
		//Check if this ID already exists
		$db->connect();
		$mm = $db->query('SELECT * from members where id = "' . $id . '"');
		if (mysql_num_rows($mm) > 0) return false;
		
		//Add this member to DB and to the members array
		$amq = $this->db->query('INSERT into members(id,handle) VALUES ("' . $id . '","' . $handle . '")');
		$this->$members[$id] = $handle;

	}

	public function getHandle($id) {
		return $this->$members[$id];
	}

	public function getId($handle){
		return array_search($this->$members, $handle);
	}

	public function removeMember($id) {
		$this->$db->query('DELETE FROM members WHERE id = "' . $id . '"');
		unset($this->$members[$id]);
	}
}
?>
