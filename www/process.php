<html> <pre> <?php
print_r($_REQUEST);
if( isset( $_REQUEST['ip'] ) && isset ( $_REQUEST['mac'] ) ) {
   $ip = $_REQUEST['ip'];
   $mac = $_REQUEST['mac'];
   exec("sudo iptables -I internet 1 -t mangle -m mac --mac-source $mac -j RETURN");
   exec("sudo rmtrack " . $ip);
   
   echo "User logged in.";
   exit;

} else {
   echo "Access Denied"; 
   exit;
}
?>
