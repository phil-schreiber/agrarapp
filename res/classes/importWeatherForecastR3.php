<?php

error_reporting(E_ALL ^ E_NOTICE);

/**
 * importWeatherForecast
 *
 * @package
 * @author Gregor
 * @copyright Copyright (c) 2014
 * @version $Id$
 * @access public
 */
class importWeatherForecast{

	//Definition von Klartext-Werten für Werte in Rohdaten.
	//Wird momentan nicht genutzt, sondern App-intern definiert.
	var $dewFormationValues = array(
		0 => 'keine',
		1 => 'leicht',
		2 => 'mäßig',
		3 => 'stark'
	);

	var $evaporationValues = array(
		0 => 'gering',
		1 => 'mäßig',
		2 => 'hoch'
	);

	var $conditionValues = array(
		'day' => array(
			1 => 'sonnig',
			2 => 'heiter',
			3 => 'wolkig',
			4 => 'stark bewölkt',
			5 => 'bedeckt',
			6 => 'Regenschauer',
			7 => 'Regen',
			8 => 'Gewitter',
			9 => 'Schneeschauer',
			10 => 'Schneefall',
			11 => 'Schneeregen',
			12 => 'Nebel',
			13 => 'in Wolken',
			14 => 'Sprühregen',
			99 => 'keine Daten'
		),
		'night' => array(
			1 => 'klar',
			2 => 'heiter',
			3 => 'wolkig',
			4 => 'stark bewölkt',
			5 => 'bedeckt',
			6 => 'Regenschauer',
			7 => 'Regen',
			8 => 'Gewitter',
			9 => 'Schneeschauer',
			10 => 'Schneefall',
			11 => 'Schneeregen',
			12 => 'Nebel',
			13 => 'in Wolken',
			14 => 'Sprühregen',
			99 => 'keine Daten'
		)
	);


	var $windDirections = array(
		0 => array(
			'long' => 'kein Wind',
			'short' => '-'
		),
		1 => array(
			'long' => 'nordnordost',
			'short' => 'N-N-O'
		),
		2 => array(
			'long' => 'ostnordost',
			'short' => 'O-N-O'
		),
		3 => array(
			'long' => 'ostsüdost',
			'short' => 'O-S-O'
		),
		4 => array(
			'long' => 'südsüdost',
			'short' => 'S-S-O'
		),
		5 => array(
			'long' => 'südsüdwest',
			'short' => 'S-S-W'
		),
		6 => array(
			'long' => 'westsüdwest',
			'short' => 'W-S-W'
		),
		7 => array(
			'long' => 'westnordwest',
			'short' => 'W-N-W'
		),
		8 => array(
			'long' => 'nordnordwest',
			'short' => 'N-N-W'
		),
		27 => array(
			'long' => 'süd',
			'short' => 'S'
		),
		28 => array(
			'long' => 'südwest',
			'short' => 'SW'
		),
		29 => array(
			'long' => 'west',
			'short' => 'W'
		),
		30 => array(
			'long' => 'nordwest',
			'short' => 'NW'
		),
		31 => array(
			'long' => 'nord',
			'short' => 'N'
		),
		32 => array(
			'long' => 'nordost',
			'short' => 'NO'
		),
		33 => array(
			'long' => 'ost',
			'short' => 'O'
		),
		34 => array(
			'long' => 'südost',
			'short' => 'SO'
		),
		99 => array(
			'long' => 'Umlauf',
			'short' => '+'
		)
	);

	/**
	 * importWeatherForecast::prepareData()
	 *
	 * Vorbereitung des Imports. Ãœberprüfung, wann die letzten Import-Durchläufe stattgefunden haben
	 * und ggf. Ausführung des Imports
	 *
	 * MiscMaps wird separat geprüft
	 *
	 * @return
	 */
	function prepareData(){
		$starttime = microtime(true);
		if($this->testWeatherDataFile()){
			$this->importWeatherData();
		}
		if($this->testRainMapFile()){
			$this->importRainMaps();
		}
		$this->importMiscMaps();

		echo date('d.m.Y H:i',time()) .'Laufzeit importWeatherForecastR2:'. (microtime(true) - $starttime) ." Sekunden\n";
	}

	/**
	 * importWeatherForecast::testWeatherDataFile()
	 *
	 * Ãœberprüft, ob die Datei mit den Wetterdaten auf dem Server neuer als der letzte Import-Zyklus ist
	 *
	 *
	 * @return boolean TRUE, wenn neue Datei gefunden wurde
	 */
	function testWeatherDataFile(){
		//zu prüfende Datei
		$sourceFile = '/home/aptagricheck/files/ext/weatherR2/wetter.zip';

		//Datei mit den Infos zum letzten Import-Zyklus auslesen
		$file = '/var/www/t/fileadmin/files3/importWeatherDataFile.txt';
		$lastImportArray = json_decode(file_get_contents($file),1);
		//Wenn die Datei leer ist, dann default-Wert nutzen
		if(!isset($lastImportArray['importTime'])){
			$lastImportArray['importTime'] = 0;
		}
		//Wenn der letzte Zyklus länger als 2 Stunden zurückliegt, dann
		//Aktualisierung der Importstatus-Datei und TRUE als Rückgabe, ansonsten FALSE
		if($lastImportArray['importTime'] < (time() - 7200)){

			$lastImportArray['importTime'] = time();
			file_put_contents($file, json_encode($lastImportArray));


			return true;
		}else{
			return false;
		}
	}


	/**
	 * importWeatherForecast::testRainMapFile()
	 *
	 * Ãœberprüfung, ob die Karten für die Niederschlagsprognose neuer sind als der letzte Import-Zyklus
	 *
	 * @return boolean TRUE, wenn neue Karten vorhanden sind
	 */
	function testRainMapFile(){
		//zu prüfende Datei
		$sourceFile = '/home/aptagricheck/files/ext/weatherR2/wetter_niederschlag_gross.zip';
		//Datei mit den Infos zum letzten Import-Zyklus auslesen
		$file = '/var/www/t/fileadmin/files3/importWeatherRainFile.txt';
		//$lastImportArray = json_decode(file_get_contents($file),1);
		//Wenn die Datei leer ist, dann default-Wert nutzen
		if(!isset($lastImportArray['importTime'])){
			$lastImportArray['importTime'] = 0;
		}
		//Wenn der letzte Zyklus länger als 12 Stunden zurückliegt, dann
		//Aktualisierung der Importstatus-Datei und TRUE als Rückgabe, ansonsten FALSE
		if(filemtime($sourceFile) > $lastImportArray['importTime']){
			$lastImportArray['importTime'] = time();
			file_put_contents($file, json_encode($lastImportArray));
			 echo date('d.m.Y H:i',time()) ."Import Rainmaps R2 anstossen.\n\n";
			return true;
		}else{
			echo date('d.m.Y H:i',time()) ."Import Rainmaps R2 ueberspringen.\n\n";
			return false;
		}
	}


	/**
	 * importWeatherForecast::importMiscMaps()
	 *
	 * Import der allgemeinen Karten wie Bodenfeuchte etc.
	 *
	 *
	 * @return void
	 */
	function importMiscMaps(){
		//Definition des Pfades mit den Karten und Ã–ffnen des Verzeichnis
		$folderPath = '/home/aptagricheck/files/ext/weatherR2';
		$dh  = opendir($folderPath);

		//Alle Dateien im Verzeichnis auslesen und in ein Array packen
		while (false !== ($filename = readdir($dh))) {
			$fileDetails = pathinfo($folderPath.'/'.$filename);
			if(is_file($folderPath .'/'. $filename) && ($fileDetails['extension'] == 'jpg' || $fileDetails['extension'] == 'gif')){
				$files[] = $filename;
			}
		}



		//Verarbeitung der einzelnen Dateien, um sie für die anschließende Aufgliederung nach Regionen und Kartentypen verarbeitbar zu machen
		foreach($files AS $key => $value){
			$fileDetails = pathinfo($value);
			$fileArray = explode('_',$fileDetails['filename']);
			$filesNew[preg_replace("/[^a-z]+/", "", $fileArray[0])][] = $value;
		}


		//Defnition der Regionen für die einzelnen Karten auf Basis von Teilen der Roh-Dateinahmen der Karten
		//Erforderlich für ein Einheitliches Mapping nach den Service-Anforderungen
		$regionArray = array(
			'g' => 'GERMANY',
			'tboden' => 'GERMANY',
			'feuchte' => 'GERMANY',
			'mitte' => 'CENTRAL',
	        'nordost' => 'NE',
	        'nordwest' => 'NW',
	        'ost' => 'E',
	        'suedost' => 'SE',
	        'suedwest' => 'SW',
	        'west' => 'W',
			'sateurOT' => 'EUROPE'
    	);

		//Definition der Karten-Typen auf BAsis der Dateinamen der Karten
		$mapArray = array(
			'radar' => 'RADAR',
			'tboden' => 'TEMP',
			'feuchte' => 'HUMIDITY',
			'satde' => 'SAT_GER',
			'sateur' => 'SAT_EU'
		);

		//Definition der Import-Zyklen für die einzelnen Karten-Typen
		$importArrayCycle = array(
			'radar' => 900,
			'tboden' => 10800,
			'feuchte' => 10800,
			'satde' => 10800,
			'sateur' => 10800
		);


		//Durchlauf der einzelnen gefundenen Wetterkarten
		foreach($filesNew AS $key => $value){


			//Ermittlung des Kartentyps für das aktuelle Bild
			$mapType = $mapArray[$key];
			//Sollte das Karten-Verzeichnis nicht bestehen, dann anlegen und für Server schreibbar machen
			if(!is_dir('/var/www/t/fileadmin/files3/weather/maps/')){
				mkdir('/var/www/t/fileadmin/files3/weather/maps/', 0750, true);
				chown('/var/www/t/fileadmin/files3/weather/maps/','www-data');
			}
			//Sollte das Verzeichnis für den Kartentyp nicht bestehen, dann anlegen und für Server schreibbar machen
			if(!is_dir('/var/www/t/fileadmin/files3/weather/maps/'. $mapType  .'/')){
				mkdir('/var/www/t/fileadmin/files3/weather/maps/'. $mapType  .'/' , 0750, true);
				chown('/var/www/t/fileadmin/files3/weather/maps/'. $mapType  .'/','www-data');
			}

			//Ãœberprüfung, wann der letzte Import-Zyklus für diesen Kartentyp stattgefunden hat
			$file = '/var/www/t/fileadmin/files3/importWeatherMaps'. $key  .'.txt';
			$lastImportArray = json_decode(file_get_contents($file),1);
			//Wenn noch kein Import stattgefunden hat, dann Default-Wert als Grundlage
			if(!isset($lastImportArray['importTime'])){
				$lastImportArray['importTime'] = 0;
			}

			if($key == 'radar'){

				$fileTime = filemtime('/home/aptagricheck/files/ext/weatherR2/radarOT_330.jpg');


				if($lastImportArray['importTime'] < $fileTime){
					$lastImportArray['importTime'] = $fileTime;
					echo "radar R2 import angestoßen um".  date('d.m.Y h:i',time()). "\n";
					file_put_contents($file, json_encode($lastImportArray));
				}else{

					continue;
				}


			}else{
			//Wenn der letzte Import-Zyklus mehr als der für den Kartentyp definierte Zeitraum zurückliegt,
			//dann aktualisierung des Importstatus in der Datei und verarbeitung, ansonsten überspringen der aktuellen Karte
			if(($lastImportArray['importTime'] + $importArrayCycle[$key])  < time()){
				$lastImportArray['importTime'] = time();

				file_put_contents($file, json_encode($lastImportArray));
			}else{
				continue;
			}
			}

			//Reset der Zähler-Variable für die Karten
			$i = 0;
			//Durchlauf der einzelnen Karten für einen Kartentyp
			foreach($value AS $key1 => $value1){
				//Ermittlung der Details für die aktuelle Karte
				$fileData = pathinfo($value1);
				$fileArray = array_reverse(explode('_', $fileData['filename']));

				if($fileArray[0] == 1){
					unset($fileArray[0]);
					$fileArray = array_values($fileArray);
				}
				//Ermittlung der REgion für die Karte
				$imageRegion = preg_replace("/[^a-z]+/", "", $fileArray[0]);

				//Unterscheidung von verschiedenen Kartentypen. Erforderlich, da die Struktur der Namensgebung für jede
				//Karten-Art anders ist
				if(($imageRegion == '' OR $imageRegion == 'g') && $mapType != 'SAT_EU'){
					$imageRegionFinal = $regionArray['g'];
				}elseif($mapType == 'SAT_EU'){
					$imageRegionFinal = 'EUROPE';
				}else{
					$imageRegionFinal = $regionArray[$imageRegion];
				}

				//Ãœberprüfung, ob das Verzeichnis für diesen Kartentyp und die aktuelle Region schon besteht.
				//Falls nicht, dann anlegen und für Server schreibbar machen
				if(!is_dir('/var/www/t/fileadmin/files3/weather/maps/'. $mapType .'/'. $imageRegionFinal .'/')){
					mkdir('/var/www/t/fileadmin/files3/weather/maps/'. $mapType  .'/'. $imageRegionFinal .'/', 0750, true);
					chown('/var/www/t/fileadmin/files3/weather/maps/'. $mapType  .'/'. $imageRegionFinal .'/','www-data');
				}

				//Definition des Pfades zu der zu importierenden Datei
				$filePath = '/home/aptagricheck/files/ext/weatherR2/'. $value1;

				//Definition und öffnen des Zielordners
				$destinationFolder = '/var/www/t/fileadmin/files3/weather/maps/'. $mapType .'/'. $imageRegionFinal;


				$dh  = opendir($destinationFolder);

				//Alle Dateien im Verzeichnis auslesen und in ein Array packen
				while (false !== ($filename = readdir($dh))) {
					if(is_file($destinationFolder.'/'.$filename)){
						$filesDelete[filemtime($destinationFolder.'/'.$filename)] = $filename;
					}
				}

				//Alle Dateien löschen, die älter als 14 Tage sind
				foreach ($filesDelete as $k=>$filenameItem) {
					if($k < time() - 1209600){
						unlink($destinationFolder.'/'.$filenameItem);
					}
				}

				//Falls die Sat-Karten verarbeitet werden, dann Aktivierung des Fixed Width Parameters, da diese Karten ein anderes Format aufweisen
				if($mapType == 'SAT_GER' || $mapType == 'SAT_EU'){
					$fixedWidth = 0;
				}else{
					$fixedWidth = 1;
				}
				//Microtime-Zeitstempel, um die einzelnen Dateien unterscheiden zu können im Dateinamen.
				$microtime = substr((microtime(true) * 10000),0,-1);
				//Generierung der verschiedenen Bild-Varianten
				$this->make_thumb($filePath,$destinationFolder .'/picture_'. $microtime .'_l.jpg',480,$fixedWidth);
				$this->make_thumb($filePath,$destinationFolder .'/picture_'. $microtime .'_m.jpg',230,$fixedWidth);
				$this->make_thumb($filePath,$destinationFolder .'/picture_'. $microtime .'_s.jpg',115,$fixedWidth);
			}
		}
	}

	/**
	 * importWeatherForecast::importWeatherData()
	 *
	 * Entpacken der gelieferte Prognosedaten für das Wetter und Aufruf der Verarbeitungsfunktion
	 *
	 * @return
	 */
	function importWeatherData(){
		$path = '/home/aptagricheck/files/ext/weatherR2/wetter.zip';
		$zip = new ZipArchive;
		$res = $zip->open($path);

		if ($res === TRUE) {

			$zip->extractTo('/var/www/t/fileadmin/files3/import_weather/');
			$zip->close();
			$this->importForecast();
		}
	}


	/**
	 * importWeatherForecast::importRainMaps()
	 *
	 * Entpacken der aktuellen Niederschlags-Karten und Start des Import-Prozesses
	 *
	 * @return
	 */
	function importRainMaps(){

		$path = '/home/aptagricheck/files/ext/weatherR2/wetter_niederschlag_gross.zip';
		$zip = new ZipArchive;
		$res = $zip->open($path);

		if ($res === TRUE) {

			$zip->extractTo('/var/www/t/fileadmin/files3/import_rainmaps/');
			$zip->close();
			$this->importRainMapFiles();
		}


	}

	/**
	 * importWeatherForecast::importRainMapFiles()
	 *
	 * Verarbeitung der einzelnen Karten für die Niederschlagsprognose und Erzeugung der einzelnen Bilder
	 *
	 * @return
	 */
	function importRainMapFiles(){

		//Definition und Ã–ffnen des Pfades mit den Karten
		$folderPath = '/var/www/t/fileadmin/files3/import_rainmaps';
		$dh  = opendir($folderPath);

		//Alle Dateien im Verzeichnis auslesen und in ein Array packen
		while (false !== ($filename = readdir($dh))) {
			if(is_file($folderPath .'/'. $filename)){
				$files[] = $filename;
			}
		}
		//Aufsteigende Sortierung der Karten
		sort($files);
		//Verarbeitung der einzelnen Dateien. Explode des Dateinamens, um auf Basis der einzelnen Elemente
		//prüfen zu können, um welche Karte und welche Region es sich handelt.
		foreach($files AS $key => $value){
			$fileArray = array_reverse(explode('_', basename($value,'.jpg')));
			$filesNew[preg_replace("/[^a-z]+/", "", $fileArray[0])][] = $value;
		}


		foreach($filesNew AS $imageRegion => $imageFiles){

			$mapArray = array(
				'xde' => 'GERMANY',
				'mitte' => 'CENTRAL',
				'nordost' => 'NE',
				'nordwest' => 'NW',
				'ost' => 'E',
				'suedost' => 'SE',
				'suedwest' => 'SW',
				'west' => 'W'
			);

			//Ãœberprüfung, ob die erforderlichen Verzeichnisse auf dem Server bestehen.
			//Falls nicht, dann anlegen und für den Webserver schreibbar machen
			if(!is_dir('/var/www/t/fileadmin/files3/weather/maps/')){
				mkdir('/var/www/t/fileadmin/files3/weather/maps/', 0750, true);
				chown('/var/www/t/fileadmin/files3/weather/maps/','www-data');
			}
			if(!is_dir('/var/www/t/fileadmin/files3/weather/maps/RAIN/')){
				mkdir('/var/www/t/fileadmin/files3/weather/maps/RAIN/' , 0750, true);
				chown('/var/www/t/fileadmin/files3/weather/maps/RAIN/','www-data');
			}
			if(!is_dir('/var/www/t/fileadmin/files3/weather/maps/RAIN/'. $mapArray[$imageRegion].'/')){
				mkdir('/var/www/t/fileadmin/files3/weather/maps/RAIN/'. $mapArray[$imageRegion] .'/', 0750, true);
				chown('/var/www/t/fileadmin/files3/weather/maps/RAIN/'. $mapArray[$imageRegion] .'/','www-data');
			}

			//Reset des Counters
			$i=0;
			//Ã–ffnen des Verzeichnis für die aktuelle Karten-Region
			$dh  = opendir('/var/www/t/fileadmin/files3/weather/maps/RAIN/'. $mapArray[$imageRegion]);
			//Definition des Zielordners für die nachfolgende Bereinigungsfunktion
			$destinationFolder = '/var/www/t/fileadmin/files3/weather/maps/RAIN/'. $mapArray[$imageRegion];
			while (false !== ($filename = readdir($dh))) {
				if(is_file($destinationFolder.'/'.$filename)){
					//Löschen aller Bilder, die älter als eine Stunden sind
					if(filemtime($destinationFolder.'/'.$filename) < (time() - 3600)){
						unlink($destinationFolder.'/'.$filename);
					}
				}
			}

			//Durchlauf der einzelnen Bilder für die aktuell gewählte Karte und Region
			foreach($imageFiles AS $key => $value){
				//Definition der Quelldateo
				$filePath = '/var/www/t/fileadmin/files3/import_rainmaps/'. $value;
				//Definition des Zielordners
				$destinationFolder = '/var/www/t/fileadmin/files3/weather/maps/RAIN/'. $mapArray[$imageRegion];

				//Erstellung Microtime-Zeitstempel
				$microtime = substr((microtime(true) * 10000),0,-1);
				//Generierung der einzelnen Bild-Varianten und Speicherung im Dateisystem
				$this->make_thumb($filePath,$destinationFolder .'/picture_'. $microtime .'_l.jpg',480,1);
				$this->make_thumb($filePath,$destinationFolder .'/picture_'. $microtime .'_m.jpg',230,1);
				$this->make_thumb($filePath,$destinationFolder .'/picture_'. $microtime .'_s.jpg',115,1);

			}
		}
	}
	/**
	 * importWeatherForecast::importForecast()
	 *
	 * Anstoß des Import-Vorgangs für die Wetterprognose
	 *
	 * @return
	 */
	function importForecast(){
		echo "importForecast";
		//Definition des Pfades zur zu importierenden Datei
		$sourceFile = '/var/www/t/fileadmin/files3/import_weather/stadtprognose_plz_d.xml';
		//Parsen der Datei
		$this->xmlParse($sourceFile,'stadt');

	}


	/**
	 * importWeatherForecast::parseWeatherData()
	 *
	 * Verarbeitung der Prognosedaten für jede einzelne PLZ im Wetter-XML und Speicherung der Daten im Filesystem.
	 *
	 * @param mixed $array Daten für eine PLZ aus dem Wetter-XML
	 * @return
	 */
	function parseWeatherData($array){

		//Basis-Daten zu dieser PLZ
		$weatherDataArray = array(
			'cityId' => $array['@attributes']['id'],
			'cityName' => $array['name'],
			'zipCode' => $array['plz']
		);

		//Reset des Counters für die Prognose-Daten
		$dateCounter = 0;
		//Durchlauf der einzelnen Tage, für die Prognosen vorliegen
		foreach($array['date'] AS $key => $value){

			//Erstellung Zeitstempel auf Basis des gelieferten Datums
			$dateTime = DateTime::createFromFormat('Ymd', $value['@attributes']['value']);
			$ts = substr(($dateTime->getTimestamp() * 10000),0,-1);

			//Aufbau des Wetterdaten-Arrays für jeden Tag
			$weatherDataArray['dates'][$dateCounter] = array(
				'importTime' => time() * 1000,
				'date' => $ts,
				'tempMax' => $value['tmax'],
				'tempMin' => $value['tmin'],
				'dewFormationId' => $value['taubildung'],
				'dewFormation' => $this->dewFormationValues[$value['taubildung']],
				'evaporationId' => $value['verdunstung'],
				'evaporation' => $this->evaporationValues[$value['verdunstung']],
				'frost' => $value['frost'],
				'sunrise' => $value['sa'],
				'sunset' => $value['su'],
				'conditionDayId' => $value['wz_tag'],
				'conditionNightId' => $value['wz_tag'],
				'moonrise' => $value['ma'],
				'moonset' => $value['mu'],
				'dusk' => $value['de'],
				'dawn' => $value['da'],
				'moonPhase' => $value['mp']
			);

			//Durchlauf der Prognosedaten pro Tag für die einzelnen Perioden (3-Stunden-Rhythmus)
			for($i=1;$i<=8;$i++){
				//Unterscheidung, ob Tag oder NAcht, wird momementan nicht genutzt
				if($i <=2 || $i > 7){
					$conditionsArray = $this->conditionValues['night'];
				}else{
					$conditionsArray = $this->conditionValues['day'];
				}
				//Aufbau des Arrays für die jeweilige Periode
				$weatherDataArray['dates'][$dateCounter]['periods'][] = array(
					'id' => $i,
					'fullHour' => $value['z_'. $i],
					'airTemperature' => $value['t_'. $i],
					'bt' => $value['bt_'. $i],
					'conditionId' => $value['wz_'. $i],
					'condition' => $conditionsArray[$value['wz_'. $i]],
					'meanWindSpeed' => $value['wg_kmh_'. $i],
					'maxWindSpeed' => $value['wb_kmh_'. $i],
					'windDirectionId' => $value['wr_'. $i],
					'windDirectionLong' => $this->windDirections[$value['wr_'. $i]]['long'],
					'windDirectionShort' =>$this->windDirections[$value['wr_'. $i]]['short'],
					'chanceOfRain' => $value['nw_'. $i],
					'relativeHumidity' => $value['rf_'. $i],
					'rainfall' => $value['ns_mm_'. $i],
					'zeroDegreeLevel' => $value['t0_'. $i],
					'snowLine' => $value['sg_'. $i],
					'snowfallAmount' => $value['smax_'. $i]
				);
			}

			$dateCounter++;
		}

		//Definition des Dateinamens für die Prognose-Daten
		$file = '/var/www/t/fileadmin/files3/forecast/'. $array['plz'] .'.txt';
		//Schreiben der Daten für diese PLZ
		file_put_contents($file, json_encode($weatherDataArray));

		//Aufbau und Schreiben, welche Stationen dieser PLZ bei den Prognosedaten zugeordnet sind
		for($i=1;$i<=3;$i++){
			$stationArray[] = $array['station_'.$i];
		}
		$file = '/var/www/t/fileadmin/files3/weatherlookup/'. $array['plz'] .'.txt';
		file_put_contents($file, json_encode($stationArray));


	}




	/**
	 * importWeatherForecast::xmlParse()
	 *
	 * Parsen der Datei mit den Wetterdaten
	 *
	 * @param mixed $file	Pfad zur Datei, die importiert werden soll
	 * @param mixed $wrapperName XML-Tag, das die ELemente umschließt
	 * @param mixed $callback Callback-Funktion für jeden Eintrag
	 * @param mixed $limit Limit, nicht genutzt
	 * @return
	 */
	function xmlParse($file,$wrapperName,$callback=NULL,$limit=NULL){

		$xml = new XMLReader();
		if(!$xml->open($file)){
			die("Failed to open input file.");
		}

		$n=0;
		$x=0;
		while($xml->read()){
			if($xml->nodeType == XMLReader::ELEMENT && $xml->name == $wrapperName){
				$doc = new DOMDocument("1.0", "UTF-8");
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

	/**
	 * importWeatherForecast::callback()
	 *
	 * Callback-Funktion für den Parser. Ruft für jedes ausgelesene Element aus der XML-Datei das
	 * Import-Skript auf
	 *
	 * @param mixed $array
	 * @return
	 */
	function callback($array){

		$this->parseWeatherData($array);

		$this->dataCount++;
	}



	/**
	 * importWeatherForecast::make_thumb()
	 *
	 * Generierung von Thumbs für die aktuelle Karte in verschiedenen Größen
	 *
	 * @param mixed $src	Bild, das verarbeitete werden soll
	 * @param mixed $dest	Zielpfad für die Datei
	 * @param mixed $desired_width gewünschte Weite
	 * @param mixed $fixedHeight	Feste Höhe
	 * @return
	 */
	function make_thumb($src, $dest, $desired_width, $fixedHeight) {

		$fileDetails = pathinfo($src);
		if($fileDetails['extension'] == 'jpg'){
			$source_image = imagecreatefromjpeg($src);
		}elseif($fileDetails['extension'] == 'gif'){
			$source_image = imagecreatefromgif($src);
		}elseif($fileDetails['extension'] == 'png'){
			$source_image = imagecreatefrompng($src);
		}
		$width = imagesx($source_image);
		$height = imagesy($source_image);
		if($fixedHeight){
			$desired_height = round(($desired_width * 1.375),0);
		}else{
			/* find the "desired height" of this thumbnail, relative to the desired width  */
			$desired_height = floor($height * ($desired_width / $width));
		}

		/* create a new, "virtual" image */
		$virtual_image = imagecreatetruecolor($desired_width, $desired_height);

		/* copy source image at a resized size */
		imagecopyresampled($virtual_image, $source_image, 0, 0, 0, 0, $desired_width, $desired_height, $width, $height);

		/* create the physical thumbnail image to its destination */
		imagejpeg($virtual_image, $dest);
		chown($dest,'www-data');
	}



}

//Instanzierung der Import-Klasse für Wetter und Aufruf des Import-Triggers
$import = new importWeatherForecast;
$import->prepareData();


?>

