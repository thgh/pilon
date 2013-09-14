<?

$ip  = $_SERVER['REMOTE_ADDR'];
$arp = shell_exec("sudo arp -an " . $ip );
preg_match('/..:..:..:..:..:../',$arp , $matches);
$mac = @$matches[0];

if(empty($mac)){
  echo 'MAC error';
  exit;
}

?>