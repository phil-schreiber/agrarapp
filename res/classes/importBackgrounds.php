<?php

error_reporting(E_ALL ^ E_NOTICE);


/**
 * importBackground
 *
 * @package
 * @author Gregor
 * @copyright Copyright (c) 2014
 * @version $Id$
 * @access public
 */
class importBackground {


	private $imageStorage = array();


	/**
	 * importBackground::importBackgroundData()
	 * Import-Funktion für die Hintergrundbilder
	 *
	 *
	 * @return
	 */
	function importBackgroundData(){

		//Datei mit Bildern ermitteln und als Pfad-String übernehmen
		$sourceFile = $this->getSourceFile();
		//Parsen der Rohdaten
		$this->xmlParse($sourceFile,'Hintergrundbild','importBackground:callback');

		$this->processImageFiles();
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
		$this->imageStorage[] = $array;

	}



	/**
	 * importZipData::storeProfileData()
	 *
	 * Speicherung der Profil-Daten der Ansprechpartner
	 *
	 * @return
	 */
	function processImageFiles(){
		
		if(!is_dir('/var/www/t/fileadmin/files/backgrounds/')){
			mkdir('/var/www/t/fileadmin/files/backgrounds/', 0750, true);
			chown('/var/www/t/fileadmin/files/backgrounds/','www-data');
		}
		
		if(!is_dir('/var/www/t/fileadmin/files/backgrounds/base/')){
			mkdir('/var/www/t/fileadmin/files/backgrounds/base/', 0750, true);
			chown('/var/www/t/fileadmin/files/backgrounds/base/','www-data');
		}
		
		
		
		foreach($this->imageStorage AS $key => $value){

			$data = base64_decode($value['Bild']);
			$md5Image = md5($data);
			
			$file = '/var/www/t/fileadmin/files/backgrounds/base/'. strtolower($value['Device']) .'.jpg';
			$fileClean = 'fileadmin/files/backgrounds/background_' . $value['Id'] .'_'. $md5Image;
			
			if(!is_dir('/var/www/t/fileadmin/files/backgrounds/'. strtolower($value['Device']) .'/')){
				mkdir('/var/www/t/fileadmin/files/backgrounds/'. strtolower($value['Device']) .'/', 0750, true);
				chown('/var/www/t/fileadmin/files/backgrounds/'. strtolower($value['Device']) .'/','www-data');
			}
			
			
			$path = '/var/www/t/fileadmin/files/backgrounds/'. strtolower($value['Device']);
			$dh  = opendir($path);
		
			$files= array();
		
			//Auslesen der Dateien und Übernahme in ein Array
			while (false !== ($filename = readdir($dh))) {
				if(is_file($path.'/'.$filename)){
					$files[filemtime($path.'/'.$filename)] = $filename;
				}
			}
		
			//Löschen aller Dateien, die älter als 7 Tage sind
			foreach ($files as $k=>$filenameItem) {
				if($k < time() - 604800){
					unlink($path.'/'.$filenameItem);
				}
			}
			

			if(imagejpeg(imagecreatefromstring($data), $file) && $value['Device'] == 'Smartphone'){

				$this->make_thumb($file,'/var/www/t/fileadmin/files/backgrounds/'. strtolower($value['Device']) .'/background_' . $value['Id'] .'_'. $md5Image . '_l.jpg',960);
				$this->make_thumb($file,'/var/www/t/fileadmin/files/backgrounds/'. strtolower($value['Device']) .'/background_' . $value['Id'] .'_'. $md5Image . '_m.jpg',480);
				$this->make_thumb($file,'/var/www/t/fileadmin/files/backgrounds/'. strtolower($value['Device']) .'/background_' . $value['Id'] .'_'. $md5Image . '_s.jpg',240);
			}else{
				$this->make_thumb($file,'/var/www/t/fileadmin/files/backgrounds/'. strtolower($value['Device']) .'/background_' . $value['Id'] .'_'. $md5Image . '_l.jpg',3840);
				$this->make_thumb($file,'/var/www/t/fileadmin/files/backgrounds/'. strtolower($value['Device']) .'/background_' . $value['Id'] .'_'. $md5Image . '_m.jpg',1920);
				$this->make_thumb($file,'/var/www/t/fileadmin/files/backgrounds/'. strtolower($value['Device']) .'/background_' . $value['Id'] .'_'. $md5Image . '_s.jpg',960);
			}
		}
	}


	/**
	 * importBackground::getSourceFile()
	 *
	 * Ermittlung der neuesten Datei mit Hintergrundbildern
	 *
	 * @return Pfad zur Datei, die importiert werden soll
	 */
	function getSourceFile(){
		
		
		//Definition des Pfades und Öffnen des Ordners
		$path = '/home/aptagricheck/files/shp/backgrounds';
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

	/**
	 * importZipData::make_thumb()
	 *
	 * Generierung von Thumbs für die einzelnen Ansprechpartner
	 *
	 * @param mixed $src
	 * @param mixed $dest
	 * @param mixed $desired_width
	 * @return
	 */
	function make_thumb($src, $dest, $desired_width) {

		/* read the source image */
		$source_image = imagecreatefromjpeg($src);
		$width = imagesx($source_image);
		$height = imagesy($source_image);

		/* find the "desired height" of this thumbnail, relative to the desired width  */
		$desired_height = floor($height * ($desired_width / $width));

		/* create a new, "virtual" image */
		$virtual_image = imagecreatetruecolor($desired_width, $desired_height);

		/* copy source image at a resized size */
		imagecopyresampled($virtual_image, $source_image, 0, 0, 0, 0, $desired_width, $desired_height, $width, $height);

		/* create the physical thumbnail image to its destination */
	    imagejpeg($virtual_image, $dest,85);
	}



}

//Instanzierung der Klasse und Aufruf der Import-Funktion
$import = new importBackground;
$import->importBackgroundData();


?>

