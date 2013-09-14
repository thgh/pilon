<?php

// Returns the relayed percentage of the latest transaction of an address
// No double spend detection
function check($a=false){

  if(empty($_REQUEST['a']))
    return 'x - Input not accepted';

  $a = preg_replace("/[^a-zA-Z0-9]+/", "", $a);

  $aGet = file_get_contents('http://blockchain.info/address/'.$a.'?format=json');

  if(empty($aGet))
    return 'a1 - Request failed';

  $aArr = json_decode($aGet,true);

  if(empty($aArr))
    return 'a2 - No json returned';

  if(!is_array($aArr))
    return 'a3 - No array returned';

  if(empty($aArr['txs']))
    return '0';
    //return 'a - No transactions found';

  if(empty($aArr['txs'][0]['hash']))
    return 'a4 - Akward error';

  $t = $aArr['txs'][0]['hash'];

  $tGet = file_get_contents('http://blockchain.info/inv/'.$t.'?format=json');

  if(empty($tGet))
    return 't1 - Request failed';

  $tArr = json_decode($tGet,true);

  if(empty($tArr))
    return 't2 - No json returned';

  if(!is_array($tArr))
    return 't3 - No array returned';

  if(empty($tArr['relayed_percent']))
    return 't4 - Relayed percent not found';

  return $tArr['relayed_percent'];
}
?>