<?php
require_once('../data/dbconnection.php');

$db = new Database('localhost', 'gr8bear_askcrh', 'csc8542', 'gr8bear_askcrh');
echo "<p>Database connected...<p>\n";

//get most recent tweet id
$recentId = $db->getSinceId();
echo "Recent ID: " .$recentId . "<br />";

//Get most recent question
$questionID = $db->getLastQuestionId();
echo "Last Question: " . $questionID . "<br />";

//get the question
$question = $db->getQuestionById($questionID);
echo "Question: <br />";
echo "<pre>";
var_dump($question);	
echo "</pre>";

//get members by topic
$mid = $db->getMemberByTopic();
echo "Member: <br />\n<pre>";
var_dump($mid);
echo "</pre>";
$db->close;
?>
