<?php
  echo '<h1>Pozdrav iz InternetNovina View2</h1>';
  //echo '<br>'.$sha;
  /* if(isset($rezultat)){
    echo '<pre>';
	print_r($rezultat);
	echo 'username:'. $rezultat[0]['username'].'<br>';
	echo 'userid iz sesije:'. $userid.'<br>';
	echo 'usermail iz sesije:'. $usermail;
	echo '</pre>';
  } */
  if(isset($loginerror)){
    echo $loginerror;
	}
	if(isset($mailovi)){
    print_r($mailovi);
	}
	if(isset($fajl)){
    echo $fajl;
	}
	
	if(isset($nemateksta)){
    echo $nemateksta;
	}
	
?>

