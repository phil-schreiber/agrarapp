<?php

error_reporting(E_ALL ^ E_NOTICE);
class importEventData {
	var $eventStorageArray = array();
	/**
	 * importEventData::importEvents()
	 * Import-Funktion für den Veranstaltungskalender der BayWa Agrar App
	 *
	 *
	 * @return
	 */
	function importEvents(){

		$starttime = microtime(true);

		//Datei mit Events ermitteln und als Pfad-String übernehmen
		$sourceFile = $this->getSourceFile();


		$this->xmlParse($sourceFile,'Veranstaltung','importEventData:callback');


		$eventsArray = array_map('array_filter', $this->eventStorageArray);
		//$eventsArray = $this->eventStorageArray;

		//Verarbeitung der einzelnen Events
		for($i=0;$i<count($eventsArray);$i++){

			$eventData = $this->parseEventData($eventsArray[$i]);


			//Prüfen, ob eine Veranstaltung bereits bekannt und gespeichert ist.
			//Falls ja, dann Update statt Insert.
			if($this->checkEventExists($eventData)){
								
				$this->updateEventEntry($eventData);
			}else{
				
				$this->createEventEntry($eventData);
			}
		}

		$runtime = microtime(true) - $starttime;
		echo date('d.m.Y - H:i:s',time()). " - ImportEventData: Laufzeit: ". $runtime ." Sekunden\n";
		
	}

	function callback($array){
		
	
		$this->eventStorageArray[] = $array;
	}



	/**
	 * importEventData::parseEventData()
	 * Verarbeitung der einzelnen Event-Datensätze in der XML-Datei.
	 * Aufbau eines Arrays, das die Daten für die nachfolgende Übernahme in die
	 * Datenbank für jedes einzelne Event enthält.
	 *
	 * @param mixed $eventData: Array mit den Rohdaten aus der XML-Datei
	 * @return
	 */
	function parseEventData($eventData){
		
		//Event-Details für Update oder Insert zusammenstellen
		$eventDataArray = array(
			'eventDetails' => array(
				'title' => $eventData['Name'],
				'abstract' => $eventData['Beschreibung'],
				'street' => $eventData['StrasseHausnummer'],
				'zip' => $eventData['Plz'],
				'city' => $eventData['Ort'],
				'address_addition' => $eventData['Adresszusatz'],
				'datetime_start' => $this->parseTimeToTimestamp($eventData['Beginn']),
				'datetime_end' => $this->parseTimeToTimestamp($eventData['Ende']),
				'regions' => count($eventData['Landkreise']['Landkreis']),
				'markdeleted' => $eventData['Loeschkennzeichen'] == 'false' ? 0 : 1,
				'baywaid' => $eventData['Id'],
				'starttime' => $this->parseTimeToTimestamp($eventData['GueltigVon']) == 0 ? 0 : $this->parseTimeToTimestamp($eventData['GueltigVon']),
				'endtime' => $this->parseTimeToTimestamp($eventData['GueltigBis'])
			)
		);

		print_r($eventDataArray);		

		if($eventDataArray['eventDetails']['starttime'] == $eventDataArray['eventDetails']['endtime'] && $eventDataArray['eventDetails']['starttime'] != 0){

			$eventDataArray['eventDetails']['endtime'] = $eventDataArray['eventDetails']['endtime'] + 86395;
		}

		if($eventDataArray['eventDetails']['datetime_start'] == $eventDataArray['eventDetails']['datetime_end']){
	
			$eventDataArray['eventDetails']['datetime_end'] =  $eventDataArray['eventDetails']['datetime_end'] + 86395;
		}

		//print_r($eventDataArray);

		//Wenn die Veranstaltung einer oder mehr Regionen zugeordnet ist
		//Generierung eines separaten Arrays für Zuordnung

		
		if(count($eventData['Landkreise']['Landkreis'])){
			if(array_key_exists('Id',$eventData['Landkreise']['Landkreis'])){
				$eventDataArray['eventRegions'][] = $eventData['Landkreise']['Landkreis']['Id'];
			}else{
				foreach($eventData['Landkreise']['Landkreis'] AS $key => $value){
					$eventDataArray['eventRegions'][] = $value['Id'];
				}
			}
		}else{
			$eventDataArray['eventRegions'] = FALSE;
		}
		
		if(count($eventDataArray['eventRegions']) > 300){
			$eventDataArray['eventRegions'] = FALSE;
			$eventDataArray['eventDetails']['regions'] = 0;
		}
			
		return $eventDataArray;

	}

	/**
	 * importEventData::createEventEntry()
	 * Speicherung der Daten für die Veranstaltung, anschließend Übernahme in
	 * eine Lookup-Tabelle.
	 *
	 * @param mixed $dataArray: Array mit den Daten der Veranstaltung
	 * @return
	 */
	function createEventEntry($dataArray){
		
		$connection = mysql_connect('localhost','goldg','OwO@B@2r') or die ("Verbindungsversuch fehlgeschlagen");
                mysql_select_db('T3_B2CAPPT',$connection);
                mysql_query("SET NAMES 'utf8'");
                mysql_query("SET CHARACTER SET 'utf8'");		

		$insertArray = $dataArray['eventDetails'];
		$insertArray['tstamp'] = time();
		$insertArray['crdate'] = time();
		$insertArray['hidden'] = 1;

		foreach($insertArray AS $key => $value){
                        $insertFields[] = mysql_real_escape_string($key);
                        $insertValues[] ='\''. mysql_real_escape_string($value) .'\'';
                }


                $sqlQuery = 'INSERT INTO tx_agrarapp_events ('. implode(',',$insertFields) .') VALUES ('. implode(',',$insertValues)  .')';

                $query = mysql_query($sqlQuery);
                $eventID = mysql_insert_id();

		if($dataArray['eventRegions']){

			foreach($dataArray['eventRegions'] AS $key => $value){
                                $insertArrayLookup = array(
                                        'uid_local' => $eventID,
                                        'uid_foreign' => $value
                                );
                                $insertFields = array();
                                $insertValues = array();
                                foreach($insertArrayLookup AS $key => $value){
                                        $insertFields[] = mysql_real_escape_string($key);
                                        $insertValues[] = '\''. mysql_real_escape_string($value) .'\'';
                                }
                                $sqlQuery = 'INSERT INTO tx_agrarapp_events_regions_mm ('. implode(',',$insertFields) .') VALUES ('. implode(',',$insertValues)  .')';

                                $query = mysql_query($sqlQuery);

                        }

		}

		$sqlQueryActivate = 'UPDATE tx_agrarapp_events SET hidden = 0 WHERE uid = '. $eventID;
		mysql_query($sqlQueryActivate);

		mysql_close($connection);

	}


	/**
	 * importEventData::updateEventEntry()
	 * Aktualisierung der Daten für die Veranstaltung, anschließend Übernahme in
	 * eine Lookup-Tabelle.
	 *
	 * @param mixed $dataArray: Array mit den Daten der Veranstaltung
	 * @return
	 */
	function updateEventEntry($dataArray){
		//UID der zu aktualisierenden Veranstaltung laden
		$connection = mysql_connect('localhost','goldg','OwO@B@2r') or die ("Verbindungsversuch fehlgeschlagen");
                mysql_select_db('T3_B2CAPPT',$connection);
                mysql_query("SET NAMES 'utf8'");
                mysql_query("SET CHARACTER SET 'utf8'");
		
		$selectQuery = 'SELECT uid FROM tx_agrarapp_events WHERE baywaid = '. intval($dataArray['eventDetails']['baywaid']);
		
		$result = mysql_query($selectQuery);

		$uidResult = mysql_fetch_array($result,MYSQL_ASSOC);

		$eventID = intval($uidResult['uid']);
		
		$updateArray = $dataArray['eventDetails'];
		//Timestamp der letzten Aktualisierung ergänzen
		$updateArray['tstamp'] = time();

		foreach($updateArray AS $key => $value){
                        $updateSqlArray[] = $key .' = \''. mysql_real_escape_string($value) .'\'';
                }

                $updateQuery = 'UPDATE tx_agrarapp_events SET '. implode(',',$updateSqlArray) .' WHERE uid = '. $eventID;
	
                mysql_query($updateQuery);

                $deleteLookupQuery = 'DELETE FROM tx_agrarapp_events_regions_mm WHERE uid_local = '. $eventID;

                mysql_query($deleteLookupQuery);
			
		if($dataArray['eventRegions']){
			foreach($dataArray['eventRegions'] AS $key => $value){
                                $insertArrayLookup = array(
                                        'uid_local' => $eventID,
                                        'uid_foreign' => $value
                                );
                                $insertFields = array();
                                $insertValues = array();
                                foreach($insertArrayLookup AS $key => $value){
                                        $insertFields[] = mysql_real_escape_string($key);
                                        $insertValues[] = '\''. mysql_real_escape_string($value) .'\'';
                                }
                                $sqlQuery = 'INSERT INTO tx_agrarapp_events_regions_mm ('. implode(',',$insertFields) .') VALUES ('. implode(',',$insertValues)  .')';
				
                                $query = mysql_query($sqlQuery);

                        }
		
		}

		mysql_close($connection);

	}




	/**
	 * importEventData::checkEventExists()
	 * Überprüfung, ob eine Veranstaltung bereits mit einem vorherigen Export
	 * geliefert und in die DB übernommen wurde
	 *
	 * @param boolean: True, wenn Veranstaltung bereits existiert
	 * @return
	 */
	function checkEventExists($dataArray){
		//Suche nach Veranstaltung mit der gleichen internen ID
		
		$connection = mysql_connect('localhost','goldg','OwO@B@2r') or die ("Verbindungsversuch fehlgeschlagen");
                mysql_select_db('T3_B2CAPPT',$connection);
                mysql_query("SET NAMES 'utf8'");
                mysql_query("SET CHARACTER SET 'utf8'");
		
		$selectQuery = 'SELECT uid FROM tx_agrarapp_events WHERE baywaid = '. intval($dataArray['eventDetails']['baywaid']);
			
		$result = mysql_query($selectQuery);
	        
		if(mysql_num_rows($result) > 0){
			mysql_close($connection);
			return TRUE;
		}else{
			mysql_close($connection);
			return FALSE;
		}
	 }



	/**
	 * importEventData::getSourceFile()
	 * Ermittelt die zuletzt hochgeladene Datei im Verzeichnis und übernimmt sie
	 * als Quelle für den Import-Prozess
	 *
	 * WICHTIG: Pfad für die Events ist hartkodiert!!
	 *
	 *
	 * @return $filename Absolute Pfad zur aktuellen Datei
	 */

	function getSourceFile(){

		$path = '/home/aptagricheck/files/shp/events';
		$dh  = opendir($path);

		
		while (false !== ($filename = readdir($dh))) {
			if(is_file($path.'/'.$filename)){
				$files[filemtime($path.'/'.$filename)] = $filename;
			}
		}
		
		krsort($files);
		
		$latestFile = array_slice($files,0,1);
		$filename = $path .'/'.reset($latestFile);
		
		foreach ($files as $k=>$filenameItem) {
			
			if($k < time() - 604800){
				unlink($path.'/'.$filenameItem);
			}
			
		}
			
		return $filename;
		//return '/home/aptagricheck/files/shp/events/veranstaltungskalender_20131119.xml';
	}

	function parseTimeToTimestamp($timeString){
		
		if(is_array($timeString)){
			return 0;
		}

		
		return strtotime($timeString);

	}

	function xmlParse($file,$wrapperName,$callback,$limit=NULL){
		$xml = new XMLReader();
		if(!$xml->open($file)){
			die("Failed to open input file.");
		}
		$n=0;
		$x=0;
		while($xml->read()){
			if($xml->nodeType==XMLReader::ELEMENT && $xml->name == $wrapperName){
				$doc = new DOMDocument('1.0', 'UTF-8');
				$xmlText = simplexml_import_dom($doc->importNode($xml->expand(),true));
				$xmlData = json_decode(json_encode((array) $xmlText), 1);
				if($limit==NULL || $x<$limit){
					if($this->callback($xmlData)){
						$x++;
					}
					unset($xmlData);
				}
				$n++;
			}
		}
		$xml->close();
	}



}

$import = new importEventData;
$import->importEvents();


?>
