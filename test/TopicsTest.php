<?
require_once('../data/Topics.php');

$topics = new Topics();

echo "Adding topic science for member 1234...";
$topics->addTopicMember("science","1234");

echo "Retrieving list of topics...";
var_dump($topics->getTopics());

echo "Displaying members for topic science...";
var_dump($topics->getMembersForTopic("science"));

echo "Deleting Topics...";
$topics->removeTopicMember("science","1234");
?>
