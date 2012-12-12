<?
require_once('../data/Members.php');

echo "Creating Members object...<br />";
$members = new Members();

echo "Members: <br /><pre>";
print_r($members->getMembers());
echo "</pre><br />";

echo "Adding member \"fake\" with id 1234...<br />";
$members->addMember("1234","fake");

echo "Members: <br /><pre>";
print_r($members->getMembers());
echo "</pre><br />";

echo "Removing member 1234...<br />";
$members->removeMember("1234");

echo "Members: <br /><pre>";
print_r($members->getMembers());
echo "</pre><br />";

?>

