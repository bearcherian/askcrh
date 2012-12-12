<?
require_once('../data/Topics.php');

echo "Creating Topics object...<br />";
$topics = new Topics();

echo "Topics:<pre>";
var_dump($topics->getTopics());
echo "</pre><br />";

echo "Adding topic science for member 1234...<br />";
$topics->addTopicMember("science","1234");

echo "Topics:<pre>";
var_dump($topics->getTopics());
echo "</pre><br />";

echo "Displaying members for topic science...<br /><pre>";
var_dump($topics->getMembersForTopic("science"));
echo "</pre><br/>";

echo "Deleting Topic science for member 1234...<br />";
$topics->removeTopicMember("science","1234");

echo "Topics:<pre>";
var_dump($topics->getTopics());
echo "</pre><br />";

?>
