<?
require_once('../data/Members.php');

$members = new Members();

print_r($members->getMembers());

$members->addMember("1234","fake");
?>

