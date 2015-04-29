<?php

$lookupFile = '/var/www/t/fileadmin/files/marketdata_lookup.txt';

$lookupData = json_decode(file_get_contents($lookupFile),1);

foreach($lookupData AS $key => $value){

	if(isset($value['futureTitle'])){
		
		$host = "http://dm.vwd.com/miscellaneous/baywa-test-period.csv?symbol=". $value['futureTitle'] .'.';
		$username = "baywa";
		$password = "BayWAdm13";

		$process = curl_init($host);
		curl_setopt($process, CURLOPT_HEADER, 0);
		curl_setopt($process, CURLOPT_USERPWD, $username . ":" . $password);
		curl_setopt($process, CURLOPT_TIMEOUT, 30);
		curl_setopt($process, CURLOPT_RETURNTRANSFER, TRUE);
		$result = curl_exec($process);
		curl_close($process);

		$file = '/var/www/t/fileadmin/files/futures/baywa-'. $value['futureTitle'] .'.csv';
		file_put_contents($file, $result);

	}

	


}


?>
