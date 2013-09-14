<?php
// get the user IP address from the query string
$ip = $_REQUEST['ip'];

// this is the path to the arp command used to get user MAC address 
// from it's IP address in linux environment.
$arp = "/usr/sbin/arp";

// execute the arp command to get their mac address
$mac = shell_exec("sudo $arp -an " . $ip);
preg_match('/..:..:..:..:..:../',$mac , $matches);
$mac = @$matches[0];



// if MAC Address couldn't be identified.
if( $mac === NULL) {
  echo "Error: Can't retrieve user's MAC address.";
  exit;
}
print_r($matches);

// Delete it from iptables bypassing rules entry.
while( $chain = shell_exec("sudo iptables -t mangle -L | grep ".strtoupper($mac) ) !== NULL ) {
 echo  exec("sudo iptables -D internet -t mangle -m mac --mac-source ".strtoupper($mac)." -j RETURN");
}
// Why in this while loop?
// Users may have been logged through the portal several times. 
// So they may have chances to have multiple bypassing rules entry in iptables firewall.

// remove their connection track.
echo exec("sudo rmtrack " . $ip); // remove their connection track if any
echo "Kickin' successful.";
?>
