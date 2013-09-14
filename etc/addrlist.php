<?php

// Sets $addrlist with the contents of addrlist.json as an array
// If that doesn't work out, it returns an error

$addrlist = @json_decode(@file_get_contents('/home/pi/etc/addrlist.json'),true);

if(empty($addrlist)){
  echo "Corrupt addrlist";
  exit;
}

if(!is_array($addrlist)){
  echo "Corrupt addrlist array";
  exit;
}

function writeAddrlist(){
  global $addrlist;
  return file_put_contents('/home/pi/etc/addrlist.json', json_encode($addrlist, JSON_PRETTY_PRINT | JSON_NUMERIC_CHECK));
}

?>