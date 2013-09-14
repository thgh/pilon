<?php

require_once 'addrlist.php';
require_once 'ipmac.php';

// Start pessimistic
$ready = false;
$addrbind = 'Cannot allocate payment address';

foreach($addrlist as $index => $data) {
  if(empty($data['mac']) || $data['mac'] == $mac){
    $addrlist[$index]['mac'] = $mac;
    $addrlist[$index]['ip'] = $ip;

    if(writeAddrlist()){
      $addrbind = $data['addr'];
      $ready = true;
    }
    else{
      $addrbind = 'Addrlist write error';
    }
    break;
  }
}

?>