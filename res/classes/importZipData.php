<?php

error_reporting(E_ALL ^ E_NOTICE);


/**
 * importZipData
 *
 * @package
 * @author Gregor
 * @copyright Copyright (c) 2014
 * @version $Id$
 * @access public
 */
class importZipData {

	private $importCount = 0;
	private $zipStorageArray = array();
	private $regionsZipData = array();
	private $profilesZipData = array();
	private $locationsZipData = array();
	/**
	 * importZipData::importZipData()
	 * Import-Funktion PLZ, Regionen, Standorte und Ansprechpartner
	 *
	 *
	 * @return
	 */
	function importZip(){

		//Datei mit Events ermitteln und als Pfad-String übernehmen
		$sourceFile = $this->getSourceFile();
		//Parsen der Rohdaten
		$this->xmlParse($sourceFile,'Postleitzahl','importZipData:callback');

		//Wenn Daten eingelesen wurden, dann weitere Verarbeitung der Rohdaten
		//Greift nur ganz am Ende noch einmal, wird auch zwischendurch bereits angestoßen.
		//siehe Funktion parseZipData()
		if(count($this->zipStorageArray)){
			$this->parseZipData();
		}
		//Speicherung der gesammelten Daten für Regionen, Profile und Standorte
		$this->storeRegionsData();
		$this->storeProfileData();
		$this->storeLocationsData();

	}


	/**
	 * importZipData::callback()
	 *
	 * Callback-Funktion für den XML-Parser. Schreibt die Rohdaten in ein Storage Array.
	 *
	 * @param mixed $array
	 * @return
	 */
	function callback($array){
		//Zwischenspeicherung der DAten
		$this->zipStorageArray[] = $array;
		//Hochzählen des Import-Counters
		$this->importCount++;
		//Wenn 50 Datensätze eingelesen wurden, dann weitere Verarbeitung und Zurücksetzen des Counters und des Storage Arrays
		if($this->importCount > 50){
			$this->parseZipData();
			$this->importCount = 0;
			$this->zipStorageArray = array();
		}
	}



	/**
	 * importZipData::parseZipData()
	 * Verarbeitung eines Sets an Datensätzen mit PLZ, Regionen, Ansprechpartnern etc.
	 * Aufbau eines Arrays, das die Daten für die nachfolgende Übernahme in die
	 * Datenbank für die verschiedenen Bereiche enthält.
	 *
	 * @param mixed $zipData: Array mit den Rohdaten aus der XML-Datei
	 * @return
	 */
	function parseZipData(){

		//Durchlauf der einzelnen Rohdatensätze im Arrays
		foreach($this->zipStorageArray AS $key => $value){
			//print_r($value);
			//Ermittlung der PLZ
			$zipCode = str_replace('D-','',$value['Name']);
			//Verarbeitung der einzelnen Daten für die betreffende PLZ nach Regionen, Profilen und STandorten
			$this->processRegion($value['Landkreis'],$zipCode);
			$this->processProfile($value['KrLeiter'],$zipCode);
			$this->processLocation($value['Betrieb'],$zipCode);

		}
	}


	/**
	 * importZipData::processRegion()
	 *
	 * Verarbeitung der Regions-Daten im XML-Import.
	 *
	 * @param mixed $regionData Daten für die Region, die einem PLZ-Eintrag im XML zugeordnet sind
	 * @param mixed $zipcode PLZ aus dem aktuellen Eintrag
	 * @return
	 */
	function processRegion($regionData,$zipcode){
		//Wenn die entsprechende ID der Region noch nicht bekannt ist, dann Anlegen eines neuen Eintrags in einem Storage Arrays für die Regionen
		//und Speicherung der zugehörigen PLZ. Falls bereits bekannt, dann Ergänzung der PLZ zur bestehenden Liste
		if(!array_key_exists($regionData['Id'],$this->regionsZipData)){
			$this->regionsZipData[$regionData['Id']] = array(
				'title' => $regionData['Name'],
				'zipcodes' => array(ltrim($zipcode,'0'))
			);
		}else{
			$this->regionsZipData[$regionData['Id']]['zipcodes'][] = ltrim($zipcode,'0');
		}
	}


	/**
	 * importZipData::processProfile()
	 *
	 * Verarbeitung der Profil-Informationen, die der aktuellen PLZ zugeordnet sind
	 *
	 * @param mixed $profileData Profilinformationen
	 * @param mixed $zipcode PLZ aus dem aktuellen Element
	 * @return
	 */
	function processProfile($profileData,$zipcode){
		
		//Wenn der Ansprechpartner noch nicht bekannt ist, dann anlegen eines neuen Eintrags mit den entsprechenden Daten
		//Falls bereits bekannt, dann Ergänzung der PLZ in der Zuordnungsliste
		if(!array_key_exists($profileData['Id'],$this->profilesZipData)){
			
			

			$this->profilesZipData[$profileData['Id']] = array(
				
				'name' => $profileData['Name'],
				'phone' => is_array($profileData['Telefon']) ? '' : $profileData['Telefon'],
				'mobile' => is_array($profileData['Mobiltelefon']) ? '' : $profileData['Mobiltelefon'],
				'email' => is_array($profileData['Email']) ? '' : $profileData['Email'],
				'image' => $profileData['Bild'],
				'zipcodes' => array(ltrim($zipcode,'0'))
			);
		}else{
			$this->profilesZipData[$profileData['Id']]['zipcodes'][] = ltrim($zipcode,'0');
		}

		
	}

	/**
	 * importZipData::processLocation()
	 *
	 * Verarbeitung der Standort-Informationen, die der aktuellen PLZ zugeordnet sind
	 *
	 * @param mixed $locationData Standortdaten
	 * @param mixed $zipcode PLZ aus dem aktuellen Element
	 * @return
	 */
	function processLocation($locationData,$zipcode){
		//Wenn der Standort noch nicht bekannt ist, dann anlegen eines neuen Eintrags mit den entsprechenden Daten
		//Falls bereits bekannt, dann Ergänzung der PLZ in der Zuordnungsliste
		if(!array_key_exists($locationData['Id'],$this->locationsZipData)){
			$this->locationsZipData[$locationData['Id']] = array(
				'location' => is_array($locationData['Name']) ? '' : $locationData['Name'],
				'division' => is_array($locationData['Sparte']) ? '' : $locationData['Sparte'],
				'street' => is_array($locationData['StrasseHausnummer']) ? '' : $locationData['StrasseHausnummer'],
				'zip' => is_array($locationData['Plz']) ? '' : $locationData['Plz'],
				'city' => is_array($locationData['Ort']) ? '' : $locationData['Ort'],
				'phone' => is_array($locationData['Telefon']) ? '' : $locationData['Telefon'],
				'fax' => is_array($locationData['Telefax']) ? '' : $locationData['Telefax'],
				'email' => is_array($locationData['Email']) ? '' : $locationData['Email'] ,
				'zipcodes' => array(ltrim($zipcode,'0'))
			);
		}else{
			$this->locationsZipData[$locationData['Id']]['zipcodes'][] = ltrim($zipcode,'0');
		}
	}



	/**
	 * importZipData::storeRegionsData()
	 *
	 * Speicherung der Regions-Daten in der Datenbank
	 *
	 * @return
	 */
	function storeRegionsData(){
		//Aufbau Verbindung zur Datenbank
		$connection = mysql_connect('localhost','goldg','OwO@B@2r') or die ("Verbindungsversuch fehlgeschlagen");
        mysql_select_db('T3_B2CAPPT',$connection);
        mysql_query("SET NAMES 'utf8'");
        mysql_query("SET CHARACTER SET 'utf8'");

		//Durchlauf der einzelnen Regionen, die während der Verarbeitung der XML-Daten generiert wurden
		foreach($this->regionsZipData AS $key => $value){

			//Aufbau BAsis-Array für den Import-Satz
			$dataArray = array(
				'tstamp' => time(),
				'title' => $value['title'],
				'zipcodes' => count($value['zipcodes']),
				'baywaid' => $key,
				'uid' => $key
			);

			//Durchlauf der einzelnen Daten und Vorbereitung für den Import
			$keyArray = array();
			$valueArray = array();
			$updateStringArray = array();
			foreach($dataArray AS $key1 => $value1){
				$keyArray[] = $key1;
				$valueArray[] = '\''. $value1 .'\'';
				$updateStringArray[] = $key1.'= \''. mysql_real_escape_string($value1).'\'';
			}
			//Insert/Update der Regions-Daten
			$insertQuery = 'INSERT INTO tx_agrarapp_regions ('. implode(',',$keyArray)  .') VALUES ('. implode(',',$valueArray) .') ON DUPLICATE KEY UPDATE '. implode(',',$updateStringArray);
			mysql_query($insertQuery);
			//Löschen der bestehenden Lookup-Daten für diese Region in Bezug auf Postleitzahlen
			$deleteQuery = 'DELETE FROM tx_agrarapp_regions_zipcodes_mm WHERE uid_local = '. intval($key);
			mysql_query($deleteQuery);

			//Neuaufbau des Lookups für die Zuordnung von PLZ zur aktuellen Region
			foreach($value['zipcodes'] AS $key2 => $value2){
				$insertQueryMM = 'INSERT INTO tx_agrarapp_regions_zipcodes_mm (uid_local,uid_foreign) VALUES ('. intval($key) .','. intval($value2) .')';
				mysql_query($insertQueryMM);
			}
		}
		mysql_close($connection);
	}

	/**
	 * importZipData::storeProfileData()
	 *
	 * Speicherung der Profil-Daten der Ansprechpartner
	 *
	 * @return
	 */
	function storeProfileData(){
		$connection = mysql_connect('localhost','goldg','OwO@B@2r') or die ("Verbindungsversuch fehlgeschlagen");
        mysql_select_db('T3_B2CAPPT',$connection);
        mysql_query("SET NAMES 'utf8'");
        mysql_query("SET CHARACTER SET 'utf8'");


		foreach($this->profilesZipData AS $key => $value){

			//Aufbau des Basis-Arrays
			$dataArray = array(
				'tstamp' => time(),
				'name' => $value['name'],
				'phone' => $value['phone'],
				'mobile' => $value['mobile'],
				'email' => $value['email'],
				'zip' => count($value['zipcodes']),
				'baywaid' => $key,
				'uid' => $key
			);
			
			//Definition des Pfades und Öffnen des Ordners
			$path = '/var/www/t/fileadmin/files/profile_pictures';
			$dh  = opendir($path);			

			$filenameNew = NULL;

			//Auslesen der Dateien und Üernahme in ein Array
			while (false !== ($filename = readdir($dh))) {
				if(is_file($path.'/'.$filename)){
					$explodedFile = explode('_',$filename);
					if(strlen($explodedFile[2]) > 5 && $explodedFile[1] == $key){
						$filenameNew = $explodedFile[0] .'_'. $explodedFile[1] .'_'. $explodedFile[2];
					}elseif(strlen($explodedFile[2]) == 5 && $explodedFile[1] == $key){
						$filenameNew = $explodedFile[0] .'_'. $explodedFile[1];
					}
				}
			}


			$dataArray['picture'] = 'fileadmin/files/profile_pictures/'. $filenameNew;

			
			

			//Vorbereitung der Daten für den Import
			$keyArray = array();
			$valueArray = array();
			$updateStringArray = array();
			foreach($dataArray AS $key1 => $value1){
				$keyArray[] = $key1;
				$valueArray[] = !is_array($value1) ? '\''. mysql_real_escape_string($value1) .'\'' : '';
				$updateStringArray[] = !is_array($value1) ? $key1.'= \''. mysql_real_escape_string($value1) .'\'' : $key1 .' = \'\'';
			}

			//Insert/Update der Ansprechpartner-Daten in der Datenbank
			$insertQuery = 'INSERT INTO tx_agrarapp_profiles ('. implode(',',$keyArray)  .') VALUES ('. implode(',',$valueArray)  .') ON DUPLICATE KEY UPDATE '. implode(',',$updateStringArray);
			mysql_query($insertQuery);

			//Löschen der Lookup-Daten für den aktuellen Ansprechpartner in Bezug auf PLZ
			$deleteQuery = 'DELETE FROM tx_agrarapp_profiles_zip_mm WHERE uid_local = '.  intval($key);
			mysql_query($deleteQuery);

			//Neuaufbau der Lookup-Daten für die PLZ, die dem aktuellen Ansprechpartner zugeordnet sind
			foreach($value['zipcodes'] AS $key2 => $value2){
				$insertArrayMM = array(
					'uid_local' => $key,
					'uid_foreign' => $value2
				);
				$insertQueryMM = 'INSERT INTO tx_agrarapp_profiles_zip_mm (uid_local,uid_foreign) VALUES ('. intval($key) .','. intval($value2) .')';
				mysql_query($insertQueryMM);
			}
		}

		mysql_close($connection);
	}

	/**
	 * importZipData::storeLocationsData()
	 *
	 * Speicherung der Standort-Daten in der Datenbank
	 *
	 * @return
	 */
	function storeLocationsData(){
		//Aufbau der Verbindung zur Datenbank
		$connection = mysql_connect('localhost','goldg','OwO@B@2r') or die ("Verbindungsversuch fehlgeschlagen");
        mysql_select_db('T3_B2CAPPT',$connection);
        mysql_query("SET NAMES 'utf8'");
        mysql_query("SET CHARACTER SET 'utf8'");
		$locationsArray = array();
		//Durchlauf der einzelnen Standorte, die während des Imports generiert wurden
		foreach($this->locationsZipData AS $key => $value){
			//Aufbau des Basis-Arrays
			$locationsArray[] = $key;

			$dataArray = array(
				'location' => $value['location'],
				'division' => $value['division'],
				'street' => $value['street'],
				'zip' => $value['zip'],
				'city' => $value['city'],
				'phone' => $value['phone'],
				'fax' => $value['fax'],
				'email' => $value['email'],
				'zipcodes' => count($value['zipcodes']),
				'baywaid' => $key,
				'uid' => $key
			);

			//Vorbereitung für den import
			$keyArray = array();
            $valueArray = array();
            $updateStringArray = array();
            foreach($dataArray AS $key1 => $value1){
                    $keyArray[] = $key1;
                    $valueArray[] = !is_array($value1) ? '\''. mysql_real_escape_string($value1) .'\'' : '';
                    $updateStringArray[] = !is_array($value1) ? $key1.'= \''. mysql_real_escape_string($value1) .'\'' : $key1 .' = \'\'';
            }
			//Insert/Update der Standort-Daten in der Datenbank
            $insertQuery = 'INSERT INTO tx_agrarapp_locations ('. implode(',',$keyArray)  .') VALUES ('. implode(',',$valueArray)  .') ON DUPLICATE KEY UPDATE '. implode(',',$updateStringArray);
            mysql_query($insertQuery);

			//Löschen der Lookup-Daten bezüglich der PLZ für den aktuellen Standort
            $deleteQuery = 'DELETE FROM tx_agrarapp_locations_zipcodes_mm WHERE uid_local = '.  intval($key);
            mysql_query($deleteQuery);

			//Neuaufbau der Lookup-Daten bezüglich der PLZ für den aktuellen Standort
            foreach($value['zipcodes'] AS $key2 => $value2){
				$insertArrayMM = array(
                    'uid_local' => $key,
                    'uid_foreign' => $value2
            	);
            	$insertQueryMM = 'INSERT INTO tx_agrarapp_locations_zipcodes_mm (uid_local,uid_foreign) VALUES ('. intval($key) .','. intval($value2) .')';
            	mysql_query($insertQueryMM);
            }
		}

		$implodeString = implode(',',$locationsArray);
		
		$deleteOldLocationsQuery = 'DELETE FROM tx_agrarapp_locations WHERE uid NOT IN ('. $implodeString  .')';
		mysql_query($deleteOldLocationsQuery);
		
		$deleteOldLookupsQuery = 'DELETE FROM tx_agrarapp_locations_zipcodes_mm WHERE uid_local NOT IN ('. $implodeString  .')';
		mysql_query($dleteOldLookupsQuery);


		mysql_close($connection);
	}



	/**
	 * importZipData::getSourceFile()
	 *
	 * Ermittlung der neuesten Datei mit PLZ-Daten und Bereitstellung des Dateipfads
	 *
	 * @return Pfad zur Datei, die importiert werden soll
	 */
	function getSourceFile(){
		//Definition des Pfades und Öffnen des Ordners
		$path = '/home/aptagricheck/files/shp/zipcodes';
		$dh  = opendir($path);

		//Auslesen der Dateien und Übernahme in ein Array
		while (false !== ($filename = readdir($dh))) {
			if(is_file($path.'/'.$filename)){
				$files[filemtime($path.'/'.$filename)] = $filename;
			}
		}
		//Absteigende Sortierung nach Zeitstempel
		krsort($files);
		//Entnahme der neuesten Datei und Aufbau des Pfades zu dieser Datei
		$latestFile = array_slice($files,0,1);
		$filename = $path .'/'.reset($latestFile);

		//Löschen aller Dateien, die älter als 7 Tage sind
		foreach ($files as $k=>$filenameItem) {
			if($k < time() - 604800){
			   	unlink($path.'/'.$filenameItem);
			}
		}
		//Rückgabe
		return $filename;
	}

	/**
	 * importZipData::xmlParse()
	 *
	 * Auslesen und Konvertierung der XML-Daten für den späteren Import
	 *
	 * @param mixed $file	Datei, die verarbeitet werden soll
	 * @param mixed $wrapperName XML-TAg, dass die einzelnen Elemente umschließt
	 * @param mixed $callback Callback-Funktion für die weitere Verarbeitung
	 * @param mixed $limit Limit, momentan nicht genutzt
	 * @return
	 */
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
				$dataArray = json_decode(json_encode((array) $xmlText), 1);
				if($limit==NULL || $x<$limit){
					if($this->callback($dataArray)){
						$x++;
					}
				}
				$n++;
			}
		}
		$xml->close();
	}


}

//Instanzierung der Klasse und Aufruf der Import-Funktion
$import = new importZipData;
$import->importZip();


?>

