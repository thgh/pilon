<?php
include('check.inc.php');
$relayed = check($_REQUEST['a']);
$ip = $_SERVER['REMOTE_ADDR'];

if(is_numeric($relayed)&&!empty($_REQUEST['mac'])&&$relayed>60){
  $mac = $_REQUEST['mac'];
  exec('sudo iptables -I internet 1 -t mangle -m mac --mac-source '.$mac.' -j RETURN');
  exec('sudo rmtrack ' . $ip);
}
elseif(is_numeric($relayed)&&$relayed>60){

  $arp = shell_exec("sudo arp -an " . $ip );
  preg_match('/..:..:..:..:..:../',$arp , $matches);
  $mac = @$matches[0];

  exec('sudo iptables -I internet 1 -t mangle -m mac --mac-source '.$mac.' -j RETURN');
  exec('sudo rmtrack ' . $ip);
}
elseif($relayed==0){
  sleep(2);
}

echo $relayed;
?>