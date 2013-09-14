<html><pre><?php

require_once 'addrgen.php';
require_once 'master.php';
require_once 'addrlist.php';

$max = -1;
foreach($addrlist as $data) {
  $max = $max>$data['i'] ? $max : $data['i'];
}

if(!empty($_REQUEST['a'])){
  $max++;
  $addrlist[$max]['i'] = $max;
  $addrlist[$max]['addr'] = addr_from_mpk($mpk, $max);
  $addrlist[$max]['time'] = time();
  writeAddrlist();
}

foreach($addrlist as $index => $data) {
  echo "\n\n ".$index." \n\n";
  print_r($data);
}
?>