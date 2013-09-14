<?php

// Sets $mpk with the contents of master.pub
// If that doesn't work out, it uses my master public key

$mpk = @file_get_contents('/home/pi/etc/master.pub');

if(empty($mpk)){
  $mpk = '88384b8bde23b3ce4fc5707509149798eabcac1b51928753a6e54d0cfee4a973139b1e9f6fdd011077d49f46ce0e9247e3cfbb3382c38bd107fbbb8d7e9baa41';
}

?>