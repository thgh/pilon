<?php

require_once 'addrbind.php';

$r['mac'] = $mac;
$r['ip'] = $ip;
$r['ready'] = $ready;
$r['btcaddr'] = $addrbind;
header('Content-type: application/json');
echo json_encode($r);
?>