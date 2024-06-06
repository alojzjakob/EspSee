<?php

include('../wp-blog-header.php');

header("HTTP/1.1 200 OK");

global $EspSee;

$EspSee->receive_heartbeat();

echo 'OK';
