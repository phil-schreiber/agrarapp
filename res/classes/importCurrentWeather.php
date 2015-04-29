<?php


class importCurrentWeather{


	function importWeatherData(){

		$starttime = microtime(true);
		$arrStations = array('EDAC', 'EDAH', 'EDDB', 'EDFE', 'EDFMEDGS', 'EDHI', 'EDHL', 'EDJA', 'EDLN', 'EDLP', 'EDLV', 'EDLW', 'EDMO', 'EDNY', 'EDQM', 'EDRZ', 'EDSB', 'EDTD', 'EDVE', 'EDVK', 'EDXW', 'ETAD', 'ETAR', 'ETEB', 'ETGG', 'ETGY', 'ETGZ', 'ETHA', 'ETHB', 'ETHC', 'ETHE', 'ETHL', 'ETHN', 'ETHR', 'ETHS', 'ETIC', 'ETIH', 'ETIK', 'ETMN', 'ETND', 'ETNG', 'ETNH', 'ETNL', 'ETNN', 'ETNS', 'ETNT', 'ETNU', 'ETNW', 'ETOR', 'ETOU', 'ETSA', 'ETSB', 'ETSE', 'ETSH', 'ETSI', 'ETSL', 'ETSN', 'ETUO', 'ETWM', '10004', '10006', '10007', '10008', '10015', '10020', '10022', '10028', '10033', '10035', '10037', '10038', '10042', '10043', '10044', '10046', '10055', '10091', '10093', '10097', '10112', '10113', '10124', '10126', '10129', '10130', '10131', '10136', '10139', '10142', '10146', '10147', '10150', '10152', '10156', '10161', '10162', '10168', '10170', '10172', '10180', '10184', '10193', '10200', '10215', '10224', '10235', '10238', '10246', '10249', '10253', '10261', '10264', '10267', '10268', '10270', '10281', '10282', '10289', '10291', '10305', '10306', '10309', '10315', '10321', '10325', '10334', '10335', '10338', '10343', '10348', '10356', '10359', '10361', '10365', '10368', '10376', '10379', '10381', '10382', '10384', '10385', '10393', '10396', '10400', '10404', '10410', '10418', '10424', '10427', '10433', '10435', '10438', '10439', '10442', '10444', '10449', '10452', '10453', '10454', '10458', '10460', '10466', '10469', '10471', '10474', '10476', '10480', '10488', '10490', '10495', '10496', '10499', '10501', '10502', '10505', '10506', '10513', '10515', '10519', '10526', '10532', '10534', '10540', '10542', '10544', '10548', '10552', '10554', '10557', '10564', '10565', '10567', '10569', '10574', '10577', '10578', '10579', '10582', '10591', '10609', '10613', '10615', '10615', '10616', '10618', '10628', '10635', '10637', '10641', '10646', '10648', '10655', '10658', '10671', '10675', '10685', '10686', '10688', '10704', '10706', '10708', '10724', '10727', '10729', '10731', '10733', '10736', '10738', '10739', '10742', '10743', '10756', '10761', '10763', '10765', '10771', '10776', '10777', '10782', '10788', '10791', '10796', '10803', '10805', '10815', '10818', '10827', '10836', '10837', '10838', '10850', '10852', '10853', '10856', '10857', '10860', '10863', '10865', '10870', '10872', '10875', '10895', '10908', '10929', '10945', '10946', '10948', '10954', '10961', '10962', '10963', '10980', '10982', '62086', '62087', '66023', '89002');

		$url = 'http://baywa.wetter.net/cgi-bin/baywa/wetter_station.pl?ID=';

		$t_start = microtime(TRUE);
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_USERAGENT,'Mozilla/5.0 (Windows NT 6.0; rv:8.0) Gecko/20100101 Firefox/8.0');
		//curl_setopt($ch, CURLOPT_PROXYAUTH, CURLAUTH_NTLM);
		//curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_HTTP);
		//curl_setopt($ch, CURLOPT_PROXY, "proxy-url.com:8080");
		//curl_setopt($ch, CURLOPT_PROXYUSERPWD, "root\username:pass");


		//print "error:" . curl_error($ch) . '<br />';
		//print "output:" . $curlContent . '<br />';

		foreach ($arrStations as $station){

			curl_setopt($ch, CURLOPT_URL, $url.$station);
			$curlContent = curl_exec($ch);

			$weatherObject = simplexml_load_string($curlContent);

			$weatherArray = json_decode(json_encode($weatherObject),true);
			$weatherArray['importTime'] = time() * 1000;
			if($weatherObject->type != 'str'){
					
				$fileContent = json_encode($weatherArray);

				$file = '/var/www/t/fileadmin/files2/currentWeather/'. $weatherArray['id'] .'.txt';

				file_put_contents($file, json_encode($weatherArray));
			}

		}

		curl_close($ch);

		$runtime = microtime(true) - $starttime;
		echo date('d.m.Y - H:i:s',time()). " - ImportCurrentWeather: Laufzeit: ". $runtime ." Sekunden\n";

	}
}
$import = new importCurrentWeather;
$import->importWeatherData();


?>
