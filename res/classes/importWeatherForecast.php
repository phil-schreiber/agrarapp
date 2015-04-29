<?php

error_reporting(E_ALL ^ E_NOTICE);
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
		$starttime = microtime(true);		
		
		if($this->testWeatherDataFile()){
			$this->importWeatherData();
		}
		if($this->testRainMapFile()){
			$this->importRainMaps();
		}
		$this->importMiscMaps();
		
		echo date('d.m.Y H:i',time()) .'Laufzeit importWeatherForecast:'. (microtime(true) - $starttime) ." Sekunden\n";
	}

	function testWeatherDataFile(){	
		$sourceFile = '/home/aptagricheck/files/ext/weather/wetter.zip';

		$file = '/var/www/t/fileadmin/files/importWeatherDataFile.txt';

		$lastImportArray = json_decode(file_get_contents($file),1);		
		print_r($lastImportArray);	
		if(!isset($lastImportArray['importTime'])){
			$lastImportArray['importTime'] = 0;
		}		

		if($lastImportArray['importTime'] < (time() - 7200)){
			$lastImportArray['importTime'] = time();
			file_put_contents($file, json_encode($lastImportArray));
		
			return true;
		}else{
				
			return false;
		}
	}

	
	function testRainMapFile(){
		$sourceFile = '/home/aptagricheck/files/ext/weather/wetter_niederschlag_gross.zip';

		$file = '/var/www/t/fileadmin/files/importWeatherRainFile.txt';

		$lastImportArray = json_decode(file_get_contents($file),1);

		if(!isset($lastImportArray['importTime'])){
			$lastImportArray['importTime'] = 0;
		}

		if($lastImportArray['importTime'] < (time() - 43200)){
			$lastImportArray['importTime'] = time();
			file_put_contents($file, json_encode($lastImportArray));
			return true;
		}else{
			return false;
		}

	}


	function importMiscMaps(){
		$folderPath = '/home/aptagricheck/files/ext/weather';
		$dh  = opendir($folderPath);

		//Alle Dateien im Verzeichnis auslesen und in ein Array packen
		while (false !== ($filename = readdir($dh))) {
			$fileDetails = pathinfo($folderPath.'/'.$filename);
			if(is_file($folderPath .'/'. $filename) && ($fileDetails['extension'] == 'jpg' || $fileDetails['extension'] == 'gif')){
				$files[] = $filename;
			}
		}
		//addEuropeMap
		//$files[] = 'Europa-600-Wolken.jpg';

		foreach($files AS $key => $value){
			$fileDetails = pathinfo($value);
			$fileArray = explode('_',$fileDetails['filename']);
			$filesNew[preg_replace("/[^a-z]+/", "", $fileArray[0])][] = $value;
		}

			
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
			'sateur' => 'EUROPE'
                );

		$mapArray = array(
			'radar' => 'RADAR',
			'tboden' => 'TEMP',
			'feuchte' => 'HUMIDITY',
			'satde' => 'SAT_GER',
			'sateur' => 'SAT_EU'
		);
		
		$importArrayCycle = array(
			'radar' => 870,
			'tboden' => 10800,
			'feuchte' => 10800,
			'satde' => 10800,
			'sateur' => 10800
		);

			
			
		foreach($filesNew AS $key => $value){
			

			$mapType = $mapArray[$key];
						
			if(!is_dir('/var/www/t/fileadmin/files/weather/maps/')){
				mkdir('/var/www/t/fileadmin/files/weather/maps/', 0750, true);
				chown('/var/www/t/filea/dmin/files/weather/maps/','www-data');
			}

			if(!is_dir('/var/www/t/fileadmin/files/weather/maps/'. $mapType  .'/')){
				mkdir('/var/www/t/fileadmin/files/weather/maps/'. $mapType  .'/' , 0750, true);
				chown('/var/www/t/fileadmin/files/weather/maps/'. $mapType  .'/','www-data');
			}
			
			$file = '/var/www/t/fileadmin/files/importWeatherMaps'. $key  .'.txt';
			
			$lastImportArray = json_decode(file_get_contents($file),1);

			if(!isset($lastImportArray['importTime'])){
				$lastImportArray['importTime'] = 0;
			}
				
			
			
			if(($lastImportArray['importTime'] + $importArrayCycle[$key])  < time()){
				$lastImportArray['importTime'] = time();
				
				file_put_contents($file, json_encode($lastImportArray));

			}else{
				continue;
			}
			
			$i = 0;
			foreach($value AS $key1 => $value1){
				$fileData = pathinfo($value1);
				$fileArray = array_reverse(explode('_', $fileData['filename']));
				
				if($fileArray[0] == 1){
					unset($fileArray[0]);
					$fileArray = array_values($fileArray);
				}	
							
				$imageRegion = preg_replace("/[^a-z]+/", "", $fileArray[0]);
			 								
				if(($imageRegion == '' OR $imageRegion == 'g') && $mapType != 'SAT_EU'){
					$imageRegionFinal = $regionArray['g'];
				}elseif($mapType == 'SAT_EU'){
					$imageRegionFinal = 'EUROPE';
				}else{
					$imageRegionFinal = $regionArray[$imageRegion];
				}
						
							
				if(!is_dir('/var/www/t/fileadmin/files/weather/maps/'. $mapType .'/'. $imageRegionFinal .'/')){
					mkdir('/var/www/t/fileadmin/files/weather/maps/'. $mapType  .'/'. $imageRegionFinal .'/', 0750, true);
					chown('/var/www/t/fileadmin/files/weather/maps/'. $mapType  .'/'. $imageRegionFinal .'/','www-data');
				}

				

				$filePath = '/home/aptagricheck/files/ext/weather/'. $value1;
			
				
				$destinationFolder = '/var/www/t/fileadmin/files/weather/maps/'. $mapType .'/'. $imageRegionFinal;
						
				$dh  = opendir($destinationFolder);

				//Alle Dateien im Verzeichnis auslesen und in ein Array packen
				while (false !== ($filename = readdir($dh))) {
					if(is_file($destinationFolder.'/'.$filename)){
						$filesDelete[filemtime($destinationFolder.'/'.$filename)] = $filename;
					}
				}
				
				//Datei suchen, die mit dem letzten Ã„derungsdatum des Verzeichnis ünstimmt
				foreach ($filesDelete as $k=>$filenameItem) {
                        		
					if($k < time() - 1209600){
                                		
						unlink($destinationFolder.'/'.$filenameItem);
					}

				}


				if($mapType == 'SAT_GER' || $mapType == 'SAT_EU'){
					$fixedWidth = 0;
				}else{			
					$fixedWidth = 1;
				}
				$microtime = substr((microtime(true) * 10000),0,-1);				
				$this->make_thumb($filePath,$destinationFolder .'/picture_'. $microtime .'_l.jpg',480,$fixedWidth);
				$this->make_thumb($filePath,$destinationFolder .'/picture_'. $microtime .'_m.jpg',230,$fixedWidth);
				$this->make_thumb($filePath,$destinationFolder .'/picture_'. $microtime .'_s.jpg',115,$fixedWidth);

				//unlink($fileTempName);
				
			}
		}

		
	}

	function importWeatherData(){
		$path = '/home/aptagricheck/files/ext/weather/wetter.zip';
		$zip = new ZipArchive;
		$res = $zip->open($path);

		if ($res === TRUE) {
			$zip->extractTo('/var/www/t/fileadmin/files/import_weather/');
			$zip->close();
			$this->importForecast();
		}
                	
	}

	function importRainMaps(){
		
		$path = '/home/aptagricheck/files/ext/weather/wetter_niederschlag_gross.zip';
		$zip = new ZipArchive;
		$res = $zip->open($path);

		if ($res === TRUE) {
			$zip->extractTo('/var/www/t/fileadmin/files/import_rainmaps/');
			$zip->close();
			$this->importRainMapFiles();
		}


	}
	
	function importRainMapFiles(){
		$folderPath = '/var/www/t/fileadmin/files/import_rainmaps';
		$dh  = opendir($folderPath);

		//Alle Dateien im Verzeichnis auslesen und in ein Array packen
		while (false !== ($filename = readdir($dh))) {

			if(is_file($folderPath .'/'. $filename)){
				$files[] = $filename;
			}

		}
		sort($files);
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

			if(!is_dir('/var/www/t/fileadmin/files/weather/maps/')){
				mkdir('/var/www/t/fileadmin/files/weather/maps/', 0750, true);
				chown('/var/www/t/fileadmin/files/weather/maps/','www-data');
			}

			if(!is_dir('/var/www/t/fileadmin/files/weather/maps/RAIN/')){
				mkdir('/var/www/t/fileadmin/files/weather/maps/RAIN/' , 0750, true);
				chown('/var/www/t/fileadmin/files/weather/maps/RAIN/','www-data');
			}

			if(!is_dir('/var/www/t/fileadmin/files/weather/maps/RAIN/'. $mapArray[$imageRegion].'/')){
				mkdir('/var/www/t/fileadmin/files/weather/maps/RAIN/'. $mapArray[$imageRegion] .'/', 0750, true);
				chown('/var/www/t/fileadmin/files/weather/maps/RAIN/'. $mapArray[$imageRegion] .'/','www-data');
			}
			$i=0;


			$dh  = opendir('/var/www/t/fileadmin/files/weather/maps/RAIN/'. $mapArray[$imageRegion]);
			$destinationFolder = '/var/www/t/fileadmin/files/weather/maps/RAIN/'. $mapArray[$imageRegion];
			while (false !== ($filename = readdir($dh))) {
				if(is_file($destinationFolder.'/'.$filename)){
					if(filemtime($destinationFolder.'/'.$filename) < (time() - 3600)){
						unlink($destinationFolder.'/'.$filename);
					}
				}
			}

			foreach($imageFiles AS $key => $value){

				$filePath = '/var/www/t/fileadmin/files/import_rainmaps/'. $value;
						
				$destinationFolder = '/var/www/t/fileadmin/files/weather/maps/RAIN/'. $mapArray[$imageRegion];

				/*
				   $dh  = opendir($destinationFolder);

				   //Alle Dateien im Verzeichnis auslesen und in ein Array packen
				   while (false !== ($filename = readdir($dh))) {
				   if(is_file($destinationFolder.'/'.$filename)){
				   $filesDelete[filemtime($destinationFolder.'/'.$filename)] = $filename;
				   }
				   }

				   //Datei suchen, die mit dem letzten Ã„derungsdatum des Verzeichnis ünstimmt
				   foreach ($filesDelete as $k=>$filenameItem) {

				   if($k < time() - 1209600){
                                                
				   unlink($destinationFolder.'/'.$filenameItem);
				   }

				   }
				*/

				$microtime = substr((microtime(true) * 10000),0,-1);
				$this->make_thumb($filePath,$destinationFolder .'/picture_'. $microtime .'_l.jpg',480,1);
				$this->make_thumb($filePath,$destinationFolder .'/picture_'. $microtime .'_m.jpg',230,1);
				$this->make_thumb($filePath,$destinationFolder .'/picture_'. $microtime .'_s.jpg',115,1);

				//unlink($fileTempName);
			}
		}

		
	}

	
	/**
	 * importEventData::importEvents()
	 * Import-Funktion für den Veranstaltungskalender der BayWa Agrar App
	 *
	 *
	 * @return
	 */
	function importForecast(){

		

		//Datei mit Events ermitteln und als Pfad-String übernehmen
		$sourceFile = '/var/www/t/fileadmin/files/import_weather/stadtprognose_plz_d.xml';
		
		$this->xmlParse($sourceFile,'stadt');
	
	}

	function callback($array){

		$this->parseWeatherData($array);

		$this->dataCount++;
	}

	function parseWeatherData($array){

		if($array['plz'] == 81925){
			//print_r($array[date][3]);
		}
		$weatherDataArray = array(
			'cityId' => $array['@attributes']['id'],
			'cityName' => $array['name'],
			'zipCode' => $array['plz']
		);

		$dateCounter = 0;
		
		
		foreach($array['date'] AS $key => $value){
					
			$dateTime = DateTime::createFromFormat('Ymd', $value['@attributes']['value']);
			$ts = substr(($dateTime->getTimestamp() * 10000),0,-1);

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
				'conditionNightId' => $value['wz_tag']	
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

		$file = '/var/www/t/fileadmin/files/forecast/'. $array['plz'] .'.txt';
		

		file_put_contents($file, json_encode($weatherDataArray));


		for($i=1;$i<=3;$i++){

			$stationArray[] = $array['station_'.$i];

		}

		$file = '/var/www/t/fileadmin/files/weatherlookup/'. $array['plz'] .'.txt';

		file_put_contents($file, json_encode($stationArray));


	}




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

$import = new importWeatherForecast;
$import->prepareData();


?>


