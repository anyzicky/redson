<?php

include 'Redson.php';

$redis = new \Redson\Redis();
$p = $redis->power();
//$redis->set('who','me');
//$redis->get('who');
//$set = $redis->set('food', 'gamburgers');
//$food = $redis->get('food');
//echo "I'am eat tasty ".$food;
$arKeys = $redis->keys('*');
var_dump($arKeys);
?>