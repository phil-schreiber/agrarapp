<?php


$host = "http://dm.vwd.com/miscellaneous/baywa-prices.csv";
$username = "baywa";
$password = "BayWAdm13";

$process = curl_init($host);
curl_setopt($process, CURLOPT_HEADER, 0);
curl_setopt($process, CURLOPT_USERPWD, $username . ":" . $password);
curl_setopt($process, CURLOPT_TIMEOUT, 30);
curl_setopt($process, CURLOPT_RETURNTRANSFER, TRUE);
$result = curl_exec($process);

curl_close($process);

$file = '/var/www/t/fileadmin/files/baywaprices_pull.csv';
file_put_contents($file, $result);


?>
