<?php

$ch = curl_init("http://search.twitter.com/search.json?q=@ashcrh");

curl_exec($ch);

curl_close($ch);

?>
