<?php
require_once('DBConnect.php');

class Members {
	private $db;
	private $members; //$members[id]

	public function __construct() {
		$this->$db = new DBConnect();
		$results = $this->$db->query('SELECT * FROM members');
		$num = mysql_num_rows($results);
		for ( $i = 0; $i < $num; $num++) {
			$this->$members[ mysql_result($results,$i,"id") ] = mysql_result($results, $i, "handle");
		}
	}

	public function getMembers() {
		return $this->$members;
	}

	public function addMember($id, $handle) {
		
		//Check if this ID already exists
		$mm = $this->$db->query('SELECT * from members where id = "' . $id '"');
		if (mysql_num_rows($mm) > 0) return false;
		
		//Add this member to DB and to the members array
		$amq = $this->$db->query('INSERT into members(id,handle) VALUES ("' . $id . '","' . $handle . '")');
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
