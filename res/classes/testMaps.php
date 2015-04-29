<?php


$url = 'https://mservice.baywa.com/t/background/0200/?criteria=%7b%22params%22:%7b%22device%22:%22smartphone%22%7d%7d';

$t_start = microtime(TRUE);
$ch = curl_init();

curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_USERAGENT,'Mozilla/5.0 (Windows NT 6.0; rv:8.0) Gecko/20100101 Firefox/8.0');


$loops = 1000; 


for($i=0;$i < $loops;$i++){

	curl_setopt($ch, CURLOPT_URL, $url);
	$curlContent = curl_exec($ch);
	
	$weatherObject = json_decode($curlContent,1);
	
	$requestTime = substr($weatherObject['requestDate'],0,10);
	
	$resultString = $requestTime ."\t";

	$resultString .= $weatherObject['pictureRef'] ."\n";

	$file = '/var/www/t/typo3conf/ext/agrarapp/res/classes/logs/background.csv';
	file_put_contents($file, $resultString ,FILE_APPEND); 	
	
	echo $i ."\n";
	
	sleep(0.1);

}

curl_close($ch);
                
?>
