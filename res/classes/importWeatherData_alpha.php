<?php
header('Content-Type: text/html; charset=UTF-8');

class importWeatherForecast{

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

	function prepareData(){

                $path = '/home/appagricheck/files/ext/weather/wetter.zip';
                $zip = new ZipArchive;
                $res = $zip->open($path);

                if ($res === TRUE) {
                        $zip->extractTo('/var/www/cms/fileadmin/files/import_weather/');
                        $zip->close();
                        $this->importForecast();
                } else {
                        echo 'doh!';
                }


        }	

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

		$this->xmlParse($sourceFile,'stadt');

		echo "<br /> Zeit: ". (microtime(true) - $starttime);
	}

	function callback($array){

		$this->parseWeatherData($array);

		$this->dataCount++;
	}

	function parseWeatherData($array){

		if($array['plz'] == 50997){
			t3lib_div::debug($array);
		}

		$weatherDataArray = array(
			'cityId' => $array['@attributes']['id'],
			'cityName' => $array['name'],
			'zipCode' => $array['plz']
		);

		$dateCounter = 0;

		foreach($array['date'] AS $key => $value){

			$dateTime = DateTime::createFromFormat('Ymd', $value['@attributes']['value']);
			$ts = $dateTime->getTimestamp();

			$weatherDataArray['dates'][$dateCounter] = array(
				'date' => $ts,
				'tempMax' => $value['tmax'],
				'tempMin' => $value['tmin'],
				'dewFormationId' => $value['taubildung'],
				'dewFormation' => $this->dewFormationValues[$value['taubildung']],
				'evaporationId' => $value['verdunstung'],
				'evaporation' => $this->evaporationValues[$value['verdunstung']],
				'frost' => $value['frost'],
				'sunrise' => $value['sa'],
				'sunset' => $value['su']
			);

			for($i=1;$i<=8;$i++){

				if($i <=2 || $i > 7){
					$conditionsArray = $this->conditionValues['night'];
				}else{
					$conditionsArray = $this->conditionValues['day'];
				}

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
					'rainfall' => $value['ns_mm_'. $i]

				);
			}

			$dateCounter++;
		}

		if($array['plz'] == 50997){
			t3lib_div::debug($weatherDataArray);
		}

		$file = 'fileadmin/files/forecast/'. $array['plz'] .'.txt';

		file_put_contents($file, json_encode($weatherDataArray));


		for($i=1;$i<=3;$i++){

			$stationArray[] = $array['station_'.$i];

		}

		$file = 'fileadmin/files/weatherlookup/'. $array['plz'] .'.txt';

		file_put_contents($file, json_encode($stationArray));


	}


	/**
	 * importNewsData::getSourceFile()
	 * Ermittelt die zuletzt hochgeladene Datei im Verzeichnis und übernimmt sie
	 * als Quelle für den Import-Prozess
	 *
	 * WICHTIG: Pfad für die News ist hartkodiert!!
	 *
	 *
	 * @return $filename Absolute Pfad zur aktuellen Datei
	 */
	function getSourceFile(){

		$path = 'fileadmin/files/weatherforecast';
		$lastChange = filemtime($path);
		$dh  = opendir($path);

		//Alle Dateien im Verzeichnis auslesen und in ein Array packen
		while (false !== ($filename = readdir($dh))) {
			$files[] = $filename;
		}

		//Datei suchen, die mit dem letzten Änderungsdatum des Verzeichnis übereinstimmt
		foreach ($files as $k=>$filenameItem) {
			//Löscht Dateien, die älter als 7 Tage sind
			//Übernimmt aktuelle Datei für den Import-Vorgang
			if(filemtime($path.'/'.$filenameItem) < time() - 604800){
				//unlink($path.'/'.$filenameItem);
			}elseif(filemtime($path.'/'.$filenameItem) == $lastChange) {
				$filename = $path.'/'.$filenameItem;
			}elseif($filenameItem == 'stadtprognose_plz_d.xml'){
				$filename = $path.'/'.$filenameItem;
			}
		}

		$filename = $path .'/stadtprognose_plz_d.xml';

		return $filename;
	}



	function xmlParse($file,$wrapperName,$callback,$limit=NULL){
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



}

$import = new importEventData;
$import->importEvents();


?>
