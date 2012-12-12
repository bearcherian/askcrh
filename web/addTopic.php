<?
require_once('../data/Topics.php');
$topics = new Topics();

$tops = explode(",",$_POST['topic']);

foreach ($tops as $t) {
	$topics->addTopicMember($t,$_POST['member']);
}

	

?>
