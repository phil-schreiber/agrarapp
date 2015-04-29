<?php

error_reporting(E_ALL ^ E_NOTICE);

/**
 * importMarketPricesData
 *
 * @package
 * @author Gregor
 * @copyright Copyright (c) 2014
 * @version $Id$
 * @access public
 */
class importMarketPricesData  {

	/**
	 * importMarketPricesData::importMarketPrices()
	 *
	 * Import der Markpreis-Daten
	 *
	 * @return
	 */
	function importMarketPrices(){
		
	
		//Einlesen der Marktdaten
		$marketData = $this->readMarketData();
		
		//Einlesen der Konfigurationsdaten
		$marketDataLookupArray = $this->getMarketDataDetails();
		
		//Bereinigen aller Marktdaten
		$marketDataFinal = $this->filterMarketData($marketData,$marketDataLookupArray);
		
		
		//Verarbeitung der Marktdaten
		$this->processMarketData($marketDataFinal, $marketDataLookupArray);
	}



	/**
	 * importMarketPricesData::processMarketData()
	 *
	 * Verarbeitung der Marktdaten aus der CSV von VWD und internen Konfigurationen
	 *
	 * @param mixed $dataArray Array mit Markt-Rohdaten
	 * @param mixed $marketDataLookupArray Array mit Konfigurationdaten für die einzelnen Werte
	 * @return
	 */
	function processMarketData($dataArray, $marketDataLookupArray){
		//Verbindung zur Datenbank aufbauen
		$connection = mysql_connect('localhost','goldg','OwO@B@2r') or die ("Verbindungsversuch fehlgeschlagen");
		mysql_select_db('T3_B2CAPPT',$connection);
		mysql_query("SET NAMES 'utf8'");
		mysql_query("SET CHARACTER SET 'utf8'");

		//Durchlauf der einzelnen Werte
		foreach($dataArray AS $key => $value){
			
			//Überprüfung, ob für den aktuellen Zeitstempel bereits in Historien-Wert angelegt wurde.
			$internalIdQuery = 'SELECT uid FROM tx_agrarapp_marketdata WHERE originalid LIKE \''. $value['#vwdcode'] .'\'';
			$internalIdResultRow = mysql_fetch_assoc(mysql_query($internalIdQuery));
			$internalGoodId = $internalIdResultRow['uid'];
			
			
			//Wenn der Wert previousCloseDate gesetzt ist, den Zeitstempel für die Überprüfung auf dieses Datum setzen und den Zyklus auf 0
			//Falls nicht, dann den Zeitstempel auf das Datum des Wertes und Zyklus auf 1
			//Hintergrund: Nicht alle Werte liefern einen vorherigen Schlusskurs und müssen daher auf Basis ihres jeweiligen Tageswertes geprüft werden
			if($value['previousCloseDate']){
				$cycle = 0;
				$checkDate = strtotime($value['previousCloseDate']);
			}else{
				$cycle = 1;
				$checkDate = strtotime($value['date']);
			}
			
			//Wenn kein Schlusskurs gesetzt ist, dafür aber Datum und Einheit, dann ebenfalls Zyklus gleich 0
			if(!$value['previousCloseDate'] && $value['date'] && $value['unit']){
				$checkDate = strtotime($value['date']);;
				$cycle = 0;
			}
			
			$checkDateMod = 0;
			
			if($marketDataLookupArray[$value['#vwdcode']]['timeChange'] != ''){
				
				$checkDateMod = 1;
				
				$timeChangeFactor = $marketDataLookupArray[$value['#vwdcode']]['timeChange'];
				
				if(substr($timeChangeFactor,0,1) === '+'){
					
					$checkDate = $checkDate + substr($timeChangeFactor,1);
					
				}elseif(substr($timeChangeFactor,0,1) === '-'){
					$checkDate = $checkDate - substr($timeChangeFactor,1);
				}else{
					
					$timeChangeArray = explode(':',$timeChangeFactor);
					
					$checkDate = $checkDate - ($checkDate % 86400) + intval($timeChangeArray[0]) * 3600 + intval($timeChangeArray[1]) * 60 + intval($timeChangeArray[1]);
					
				}
				
			}
			
			

			//Aufbau des Detail-Arrays für den aktuellen Marktdaten-Wert
			$detailsArray = array(
				'name' => $marketDataLookupArray[$value['#vwdcode']]['title'],
				'description' => '',
				'unit' =>  $marketDataLookupArray[$value['#vwdcode']]['unit'],
				'isin' => $value['isin'],
				'currency' => strpos($marketDataLookupArray[$value['#vwdcode']]['title'],'AX') ? 'Punkte' : $value['currency'],
				'openprice' => $value['open'],
				'highprice' => $value['high'],
				'lowprice' => $value['low'],
				'currentprice' => $value['last'] != '' ? $value['last'] : $value['open'],
				'changeNet' => $value['changeNet'] != '' ? $value['changeNet'] : 0,
				'changePercent' => $value['changePercent'] != '' ? ($value['changePercent'] * 100)  : 0,
				'settlement' => $value['settlement'],
				'previousClosePrice' => $value['previousClose'],
				'previousCloseDate' => $checkDate,
				'dataGranularity' => $cycle,
				'dataSource' => strpos($value['#vwdcode'], 'AMI') == false ? 'vwd' : 'ami',
				'hasFutureCourseData' => $marketDataLookupArray[$value['#vwdcode']]['futureTitle'] != '' ? 1 : 0,
				'datetime' => $checkDate
			);

			if($checkDateMod == 0){
				//Zeitstempel für Datenbank erzeugen aus den Werten aus der CSV Datei
				$detailsArray['datetime'] = $this->parseTimeToTimestamp($value['date'] .' '. $value['time']);
			}else{
				$detailsArray['datetime'] = $checkDate;
			}

			if($marketDataLookupArray[$value['#vwdcode']]['futureTitle'] != ''){
				$this->importFutureData($marketDataLookupArray[$value['#vwdcode']]['futureTitle'],$internalGoodId,$detailsArray);
			}

			//Aufbau des Basis-Arrays für den aktuellen Wert
			$dataArrayItem = array(
					'tstamp' => time(),
					'crdate' => time(),
					'price' => $value['last'] != '' ? $value['last'] : $value['settlement'],
					'datetime' => $checkDate,
					'originalid' => $value['#vwdcode'],
					'details' => json_encode($detailsArray)
			);

			//Überprüfung, ob für den aktuellen Zeitstempel bereits in Historien-Wert angelegt wurde.
			$historyQuery = 'SELECT uid FROM tx_agrarapp_marketdata_history WHERE originalid LIKE \''. $value['#vwdcode'] .'\' AND datetime = '. $checkDate;
			$historyResult = mysql_query($historyQuery);

			//Wenn der Wert noch nicht in der Historie erfasst wurde, dann entsprechend speichern.
			if(mysql_num_rows($historyResult) == 0){

				$keyArray = array();
				$valueArray = array();
				foreach($dataArrayItem AS $pastKey => $pastValue){
					$keyArray[] = $pastKey;
					$valueArray[] = '\''. mysql_real_escape_string($pastValue)  .'\'';
				}

				$insertQuery = 'INSERT INTO tx_agrarapp_marketdata_history ('. implode(',',$keyArray)  .') VALUES ('. implode(',',$valueArray)  .')';
				mysql_query($insertQuery);

			}else{
				$keyArray = array();
				$keyArray = array();
				$valueArray = array();
				foreach($dataArrayItem AS $pastKey => $pastValue){
					$keyArray[] = $pastKey;
					$valueArray[] = '\''. mysql_real_escape_string($pastValue)  .'\'';
				}
			}
		
			if($checkDateMod == 0){
				//Zeitstempel für Datenbank erzeugen aus den Werten aus der CSV Datei
				$dataArrayItem['datetime'] = $this->parseTimeToTimestamp($value['date'] .' '. $value['time']);
			}
			
			//Reset Update Array
			unset($updateStringArray);
			//Bereinigugn des Update-Arrays
			foreach($dataArrayItem AS $key1 => $value1){
				$updateStringArray[] = $key1.' = \''. mysql_real_escape_string($value1) .'\'';
			}
			//INSERT/Update des Marktwertes in der Tabelle für die aktuellen Marktdaten
			//Wenn VWD-Code bereits bekannt, dann UPDATE, ansonsten Insert
			$insertQueryCurrent = 'INSERT INTO tx_agrarapp_marketdata ('. implode(',',$keyArray)  .') VALUES ('. implode(',',$valueArray)  .') ON DUPLICATE KEY UPDATE '. implode(',',$updateStringArray);

			mysql_query($insertQueryCurrent);

			$this->triggerGoodCoursePush($dataArrayItem['price'],$internalGoodId,$detailsArray);
			
		}

		mysql_close($connection);

	}

	/**
	 * importMarketPricesData::importFutureData()
	 *
	 * Import-Funktion für die Future-Werte für einzelne Marktdaten. Verarbeitet die Basis-CSV und importiert die Daten in die Datenbank.
	 *
	 * @param mixed $goodName	Bezeichnung des jeweiligen Wertes, für den Futures eingelesen werden sollen
	 * @param int $interalID	Interne ID des jeweiligen Marktwerts in der Datenbank
	 * @param mixed $goodDetails	Array mit den Detailinformationen zu dem Marktwert wie Titel, Einheiten etc.
	 * @return void
	 */
	function importFutureData($goodName,$internalId,$goodDetails){
		
		//Auslesen der Future-Daten aus der CSV-Datei und Aufbau eines Arrays für die Verarbeitung
		$futureData = $this->readFutureData($goodName);
		
		$futurePushPrices = array();
		
		//Durchlauf der einzelnen Array-Datensätze
		foreach($futureData AS $key => $value){
			
			if($value['settlement'] == ''){
				continue;
			}

			//Zerlegung des Codes für den jeweiligen Wert. Setzt sich zusammen aus VWD-Code und einem Zeitstempel
			$explodedId = explode('.',$value['#vwdcode']);
			
			//Reset des Basis-Arrays für die Future-Werte
			$baseArray = array();
				
			//Aufbau des VWD-Codes zur Synchronisation mit den übrigen Marktdaten auf Basis der ersten beiden Werte
			$baseArray['originalid_short'] = $explodedId[0] .'.'. $explodedId[1];

			//Einfügen der ID des Futures-Wertes
			$baseArray['originalid'] = $value['#vwdcode'];
			
			//Auslesen des Zeitstempels des aktuellen Future-Wertes
			//Format ist z.B. 912, wobei "9" für das Jahr und "12" für den Monat steht.
			$rawTime = $explodedId[2];
			
			//Auslesen der Jahreszahl und des Monats aus dem Zeitstempel
			$rawTimeYear = substr($rawTime,0,1);
			$rawTimeMonth = ltrim(substr($rawTime,1,2),'0');
			
			//Ermittlung der aktuellen Werte für Jahrzehnt, Jahr und Monat
			$rawCurrentDecade = substr(date('y',time()),0,1);
			$rawCurrentYear = substr(date('y',time()),1,1);
			$rawCurrentMonth = date('n',time());
	
			//Wenn das Jahr im Zeitstempel kleiner ist als das aktuelle Jahr, dann verweist der Wert auf das Jahr im nächsten Jahrzehnt.
			//Das Jahr für dementsprechend auf die nächste Dekade datiert. Ansonsten Aufbau des Jahrs im aktuellen Jahrzehnt.
			//Das Skript funktioniert momentan nur bis zum Jahr 2099!!! Aber irgendwie macht eine Jahrhunderprüfung etc. an der Stelle momentan keinen Sinn.
			if($rawTimeYear < $rawCurrentYear){
				$timestampYear = "20". $rawCurrentDecade + 1 .''. $rawTimeYear;
			}else{
				$timestampYear = "20". $rawCurrentDecade .''. $rawTimeYear;
			}
			
			
			$timestampFuture = mktime(12,1,1,$rawTimeMonth,1,$timestampYear);
			
			$detailsArray = array();
			foreach($value AS $key1 => $value1){
				$key1 = str_replace('#','',$key1);

				$detailsArray[$key1] = $value1;

				if($value1 == ''){
					$detailsArray[$key1] = 0.00;
				}

			}
			
			;
			
			$detailsArray['changePercent'] = $detailsArray['changePercent'] * 100;
						
			//Ergänzen der ermittelten Detailwerte und des Zeitstempels
			$baseArray['datetime'] = $timestampFuture;
			$baseArray['details'] = json_encode($detailsArray);
			$baseArray['price'] = $value['settlement'];
			$baseArray['tstamp'] = time();
			$baseArray['crdate'] = time();
			
			
			$futurePushPrices[$timestampFuture] = $value['settlement'];
			
			
			
			
			unset($keyArray);
			unset($valueArray);
			foreach($baseArray AS $dbKey => $dbValue){
				$keyArray[] = $dbKey;
				$valueArray[] = '\''. mysql_real_escape_string($dbValue)  .'\'';
			}
			//Reset Update Array
			unset($updateStringArray);
			foreach($baseArray  AS $dbKey => $dbValue){
				$updateStringArray[] = $dbKey.' = \''. mysql_real_escape_string($dbValue) .'\'';
			}

			$insertQueryCurrent = 'INSERT INTO tx_agrarapp_futures ('. implode(',',$keyArray)  .') VALUES ('. implode(',',$valueArray)  .') ON DUPLICATE KEY UPDATE '. implode(',',$updateStringArray);
			mysql_query($insertQueryCurrent);
			
		}
		
		$this->triggerFutureCoursePush($futurePushPrices,$internalId,$goodDetails);

	}


	/**
	 * importMarketPricesData::parseTimeToTimestamp()
	 *
	 * Umwandlung von Zeitstring in UNIX Timestamp
	 *
	 * @param mixed $string Zeit-String
	 * @return
	 */
	function parseTimeToTimestamp($string){

		return strtotime($string);

	}


	/**
	 * importMarketPricesData::getSourceFile()
	 *
	 * Abruf des Dateinamens für die einzulesende CSV-DAtei
	 *
	 * @return
	 */
	function getSourceFile(){

		$filename = '/home/aptagricheck/files/ext/market/baywa-prices.csv';
		//copy($filename,'/var/www/t/fileadmin/files/backupMarket/baywa-prices'. date('Ymd',time()) .'.csv');
		
		
		//$fileTime =  filemtime('/var/www/t/fileadmin/custommarket.csv');
		//$compareTime =  time() - 3600;
		//if($fileTime > $compareTime){
		//	
		//	$filename = '/var/www/t/fileadmin/custommarket.csv';
		//}
		
		$filename = '/var/www/t/fileadmin/files/baywaprices_pull.csv';		

		return $filename;
	}

	/**
	 * importMarketPricesData::readMarketData()
	 *
	 * Einlesen der Marktdaten aus der bereitgestellten CSV Datei und Umwandlung in ein Array
	 *
	 * @return array $marketDataArray Array mit aktuellen Marktdaten
	 */
	function readMarketData(){

		$filename = $this->getSourceFile();

		if (($handle = fopen($filename, "r")) !== FALSE) {
			// read the column headers in an array.
			$head = fgetcsv($handle, 1000, ";");

			// read the actual data.
			while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {

				if(strpos($data[2],'Europ')){
					$data[2] = 'EUR Europäischer Euro (USD)';
				}

				// create a new array with the elements in $head as keys
				// and elements in array $data as values.
				if(count($head) == count($data)){
					$marketDataArray[] = array_combine($head,$data);
				}

			}
			// done using the file..close it,
			fclose($handle);
		}

	

		return $marketDataArray;

	}


	/**
	 * importMarketPricesData::readFutureData()
	 *
	 * Einlesen der Future-Daten aus der bereitgestellten CSV Datei und Umwandlung in ein Array
	 *
	 * @return array $marketDataArray Array mit Future-Marktdaten
	 */
	function readFutureData($goodName){

		$filename = '/var/www/t/fileadmin/files/futures/baywa-'. $goodName .'.csv';

		if (($handle = fopen($filename, "r")) !== FALSE) {
			// read the column headers in an array.
			$head = fgetcsv($handle, 1000, ";");

			// read the actual data.
			while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {

				// create a new array with the elements in $head as keys
				// and elements in array $data as values.
				if(count($head) == count($data)){
					$marketDataArray[] = array_combine($head,$data);
				}

			}
			// done using the file..close it,
			fclose($handle);
		}

		
		
		return $marketDataArray;

	}
	
	/**
	 * importMarketPricesData::getMarketDataDetails()
	 *
	 * Einlesen der Konfigurationsdaten für die Marktdaten. Z.B. abweichende Titel etc.
	 *
	 * @return array $marketDataLookupArray Array mit Konfigurationsdaten
	 */
	function getMarketDataDetails(){
		$marketDataLookup = file_get_contents('/var/www/t/fileadmin/files/marketdata_lookup.txt');
		$marketDataLookupArray = json_decode($marketDataLookup,1);
		
		return $marketDataLookupArray;
	}
	
	
	/**
	 * importMarketPricesData::filterMarketData()
	 *
	 * Bereinigung der Marktdaten um Werte, die NICHT konfiguriert sind. Verhindert verwaiste Einträge in der App ohne Zuordnung zu einer Kategorie
	 *
	 * @return array $marketDataLookupArray Array mit Konfigurationsdaten
	 */
	function filterMarketData($marketData,$lookupData){
		
		foreach($marketData AS $key => $value){
			if(!isset($lookupData[$value['#vwdcode']])){
				unset($marketData[$key]);
			}
		}
		
		return $marketData;
		
	}
	
	function triggerGoodCoursePush($price,$internalId,$goodDetails){
		
		$this->processGoodCoursePush($price,$internalId,$goodDetails);
		
		$this->cleanUpGoodCoursePush($price,$internalId);
		
	}
	
	function processGoodCoursePush($price,$internalId,$goodDetails){

		
		
		//Query Aufbauen
		//Parameter:
		//limitvalue:	Preis des Guts
		//limitboder: 0 = Untergrenze, 1 = Obergrenze
		//limittyp: 0 = regulärer Kurs, 1 = Future
		//limittime: Zeitstempel des Timeout-Beginns
		
		
		$query = 'SELECT s.uid,s.deviceid,s.limittype,s.limitborder,s.limitvalue,d.ostype,d.token,d.appversion FROM tx_agrarapp_subscriptions AS s LEFT JOIN tx_agrarapp_devices AS d ON (d.deviceid = s.deviceid) WHERE s.hidden = 0 AND subtype = 3 AND ((limitid = '. $internalId .' AND limitvalue > '. $price .' AND limitborder = 0 AND limittype = 0 AND limittime = 0) OR (limitid = '. $internalId .' AND limitvalue < '. $price .' AND limitborder = 1 AND limittype = 0 AND limittime = 0))';
		
		

		
		$result = mysql_query($query);
		
		if($result){
			
			while($row = mysql_fetch_assoc($result)) {
					
				if($row['limitborder'] == 0){
					$pushText = 'Der aktuelle Kurs für \''. $goodDetails['name'] .'\' hat Ihr Limit von '. number_format($row['limitvalue'],2,',','.') .' '. $goodDetails['currency'] .' unterschritten.';
				}else{
					$pushText = 'Der aktuelle Kurs für \''. $goodDetails['name'] .'\' hat Ihr Limit von '. number_format($row['limitvalue'],2,',','.') .' '. $goodDetails['currency'] .' überschritten.';
				}
				$uidArray[] = $row['uid'];
										
				$this->generatePushMessage($internalId,'course',$row['ostype'],$row['appversion'],$row['token'],$pushText);
				
			}
			if(count($uidArray)){
				$updateTimeoutsQuery = 'UPDATE tx_agrarapp_subscriptions SET limittime = '. time() .' WHERE uid IN ('. implode(',',$uidArray) .')';
		
				mysql_query($updateTimeoutsQuery);
			}
		}
	}
	
	
	
	function cleanUpGoodCoursePush($price,$internalId){
				
		$query = 'UPDATE tx_agrarapp_subscriptions SET limittime = 0 WHERE subtype = 3 AND ((limitid = '. $internalId .' AND limitvalue < '. $price .' AND limitborder = 0 AND limittype = 0 AND limittime < UNIX_TIMESTAMP(DATE_SUB(NOW(), INTERVAL 30 MINUTE))) OR (limitid = '. $internalId .' AND limitvalue > '. $price .' AND limitborder = 1 AND limittype = 0 AND limittime < UNIX_TIMESTAMP(DATE_SUB(NOW(), INTERVAL 30 MINUTE))))';
			
		mysql_query($query);
		
	}
	
	function generatePushMessage($id,$alertType,$ostype,$version,$token,$text){
		
		
		if($ostype == 'IOS'){
			$type = '0';
		}else{
			$type = '1';
		}

		if($alertType == 'course'){
			$alertValue = 1;
		}else{
			$alertValue = 2;
		}
		$text = addslashes($text);
		$payload = '{
			"notification" : {
				"alert" : "'. $text .'",
				"pushType" : "marketalert",
				"pushId" : "'. $id .'"
			},
			"device_type" : "'. $type .'",
			"audience" : [{"device_id" : "'. $token  .'"}]
		}';
		
		
		
		$entryArray = array(
			'crdate' => time(),
			'ostype' => $type,
			'applicationid' => 'l5VUiO5jSxagn0vicaX6cw',
			'pushtype' => 3,
			'pushdate' => 0,
			'pushid' => $id,
			'expiry' => 0,
			'versionnumber'=> $version,
			'zipcode' => 0,
			'marketalerttype' => $alertValue,
			'payload' => $payload
		);
		
				


		foreach($entryArray AS $dbKey => $dbValue){
			$keyArray[] = $dbKey;
			$valueArray[] = '\''. $dbValue .'\'';
		}
		
		
		$insertQuery = 'INSERT INTO tx_dfpushpoll_log ('. implode(',',$keyArray)  .') VALUES ('. implode(',',$valueArray)  .')';
				
		mysql_query($insertQuery);
				
	}
	
	function triggerFutureCoursePush($pricesArray,$internalId,$goodDetails){
		$this->processFutureCoursePush($pricesArray,$internalId,$goodDetails);
	
		$this->cleanUpFutureCoursePush($pricesArray,$internalId);
	}
	
	
	function processFutureCoursePush($pricesArray,$internalId,$goodDetails){
		
		//Query Aufbauen
		//Parameter:
		//limitvalue:	Preis des Guts
		//limitboder: 0 = Untergrenze, 1 = Obergrenze
		//limittype: 0 = regulärer Kurs, 1 = Future
		//limittime: Zeitstempel des Timeout-Beginns
		
			
		$query = 'SELECT s.uid,s.deviceid,s.limittype,s.limitborder,s.limitvalue,d.ostype,d.token,d.appversion FROM tx_agrarapp_subscriptions AS s LEFT JOIN tx_agrarapp_devices AS d ON (d.deviceid = s.deviceid) WHERE s.hidden = 0 AND subtype = 3 AND ((limitid = '. $internalId .' AND limitvalue > '. min($pricesArray) .' AND limitborder = 0 AND limittype = 1 AND limittime = 0) OR (limitid = '. $internalId .' AND limitvalue < '. max($pricesArray) .' AND limitborder = 1 AND limittype = 1 AND limittime = 0))';
		
		$result = mysql_query($query);
		
		$monthArray = array(
			1 => 'Jan',
			2 => 'Feb',
			3 => 'Mär',
			4 => 'Apr',
			5 => 'Mai',
			6 => 'Jun',
			7 => 'Jul',
			8 => 'Aug',
			9 => 'Sep',
			10 => 'Okt',
			11 => 'Nov',
			12 => 'Dez'
		);
	
		if($result){
			
			while($row = mysql_fetch_assoc($result)) {
								
				if($row['limitborder'] == 0){
					$limit = $row['limitvalue'];
					$minArray = array_filter(
					$pricesArray,
					function ($value) use($limit) {
						return ($value < $limit);
					}
					);
								
					
					$limitCount = count($minArray);
					
					$mins = array_keys($pricesArray, min($pricesArray));

					$dateString  = $monthArray[date('n',$mins[0])] .' '. date('y',$mins[0]);
										
					if($limitCount == 1){
						$pushText = 'Der Future-Kurs '. $dateString .' für \''. $goodDetails['name'] .'\' hat Ihr Limit von '. number_format($row['limitvalue'],2,',','.') .' '. $goodDetails['currency'] .' unterschritten.';
					}else{
												
						$pushText = 'Die Future-Kurse '. $dateString .' u.a. für \''. $goodDetails['name'] .'\' haben Ihr Limit von '. number_format($row['limitvalue'],2,',','.') .' '. $goodDetails['currency'] .' unterschritten.';
						
					}

				
					
					
				}else{
					
					$limit = $row['limitvalue'];
					$maxArray = array_filter(
					$pricesArray,
					function ($value) use($limit) {
						return ($value > $limit);
					}
					);
					
					
					$limitCount = count($maxArray);
					
					$maxs = array_keys($pricesArray, max($pricesArray));
					
					$dateString  = $monthArray[date('n',$maxs[0])] .' '. date('y',$maxs[0]);

					if($limitCount == 1){
						$pushText = 'Der Future-Kurs '. $dateString .' für \''. $goodDetails['name'] .'\' hat Ihr Limit von '. number_format($row['limitvalue'],2,',','.') .' '. $goodDetails['currency'] .' überschritten.';
					}else{
						$pushText = 'Die Future-Kurse '. $dateString .' u.a. für \''. $goodDetails['name'] .'\' haben Ihr Limit von '. number_format($row['limitvalue'],2,',','.') .' '. $goodDetails['currency'] .' überschritten.';
						
					}

			
									
				}
				$uidArray[] = $row['uid'];
				
							
				$this->generatePushMessage($internalId,'future',$row['ostype'],$row['appversion'],$row['token'],$pushText);

								
			}
			if(count($uidArray)){
				$updateTimeoutsQuery = 'UPDATE tx_agrarapp_subscriptions SET limittime = '. time() .' WHERE uid IN ('. implode(',',$uidArray) .')';
		
				mysql_query($updateTimeoutsQuery);
			}
		}
	}
	
	function cleanUpFutureCoursePush($price,$internalId){
								
		$query = 'UPDATE tx_agrarapp_subscriptions SET limittime = 0 WHERE subtype = 3 AND ((limitid = '. $internalId .' AND limitvalue < '. min($price) .' AND limitborder = 0 AND limittype = 1 AND limittime < UNIX_TIMESTAMP(DATE_SUB(NOW(), INTERVAL 30 MINUTE))) OR (limitid = '. $internalId .' AND limitvalue > '. max($price) .' AND limitborder = 1 AND limittype = 1 AND limittime < UNIX_TIMESTAMP(DATE_SUB(NOW(), INTERVAL 30  MINUTE))))';
	
		mysql_query($query);
		
	}
	


}
//Instanzierung der Import-Klasse und Aufruf der ImportFunktion
$import = new importMarketPricesData;
$import->importMarketPrices();

?>






