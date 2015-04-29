<?php

error_reporting(E_ALL ^ E_NOTICE);


/**
 * importKRLeader
 *
 * @package
 * @author Gregor
 * @copyright Copyright (c) 2014
 * @version $Id$
 * @access public
 */
class importKRLeader {


        private $imageStorage = array();


        /**
         * importKRLeader::importKRLeaderData()
         * Import-Funktion PLZ, Regionen, Standorte und Ansprechpartner
         *
         *
         * @return
         */
        function importKRLeaderData(){

                //Datei mit Events ermitteln und als Pfad-String ühmen
                $sourceFile = $this->getSourceFile();
                //Parsen der Rohdaten
                $this->xmlParse($sourceFile,'KrLeiter','importKRLeader:callback');

                $this->processImageFiles();
        }


        /**
         * importZipData::callback()
         *
         * Callback-Funktion fü XML-Parser. Schreibt die Rohdaten in ein Storage Array.
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
		
		$files = glob('/var/www/t/fileadmin/files/profile_pictures/*'); // get all file names
		foreach($files as $file){ // iterate files
  			if(is_file($file))
		    	unlink($file); // delete file
		}

                $connection = mysql_connect('localhost','goldg','OwO@B@2r') or die ("Verbindungsversuch fehlgeschlagen");
                mysql_select_db('T3_B2CAPPT',$connection);
                mysql_query("SET NAMES 'utf8'");
                mysql_query("SET CHARACTER SET 'utf8'");
                foreach($this->imageStorage AS $key => $value){

                        //Wenn ein Bild fü Ansprechpartner vorhanden ist (ist dann KEIN Array, sondern ein String), dann Verarbeitung.
                        //Ansonsten Rüff auf Default-Bild
                        if(!is_array($value['Bild']) && strlen($value['Bild'])){
                                $data = base64_decode($value['Bild']);
				
				
                                $md5Image = md5($data);
                                if($value['Id']){
                                        $file = '/var/www/t/fileadmin/files/profile_pictures/profile_' . $value['Id'] .'_'. $md5Image . '_l.jpg';
                                        $fileClean = 'fileadmin/files/profile_pictures/profile_' . $value['Id'] .'_'. $md5Image;

                                        if(imagejpeg(imagecreatefromstring($data), $file)){
                                                $this->make_thumb($file,'/var/www/t/fileadmin/files/profile_pictures/profile_' . $value['Id'] .'_'. $md5Image . '_m.jpg',200);
                                                $this->make_thumb($file,'/var/www/t/fileadmin/files/profile_pictures/profile_' . $value['Id'] .'_'. $md5Image . '_s.jpg',100);
                                        };


                                        $updateQuery = 'UPDATE tx_agrarapp_profiles SET picture = "fileadmin/files/profile_pictures/profile_'. $value['Id'] .'_'. $md5Image  .'" WHERE baywaid = '. $value['Id'];

                                        mysql_query($updateQuery);
                                }
                        }else{

                                if($value['Id']){
                                        $file = '/var/www/t/fileadmin/files/profile_pictures/profile_' . $value['Id'] . '_l.jpg';
                                        $fileClean = 'fileadmin/files/profile_pictures/profile_' . $value['Id'];
                                        //unlink($file);
                                        if(imagejpeg(imagecreatefromjpeg('/var/www/t/fileadmin/files/misc/fallback_contact_image.jpg'), $file)){
                                                $this->make_thumb($file,'/var/www/t/fileadmin/files/profile_pictures/profile_' . $value['Id'] . '_l.jpg',400);
                                                $this->make_thumb($file,'/var/www/t/fileadmin/files/profile_pictures/profile_' . $value['Id'] . '_m.jpg',200);
                                                $this->make_thumb($file,'/var/www/t/fileadmin/files/profile_pictures/profile_' . $value['Id'] . '_s.jpg',100);
                                        };
                                }
                        }

                }

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
                //Definition des Pfades und Öfnen des Ordners
                $path = '/home/aptagricheck/files/shp/krleader';
                $dh  = opendir($path);

                //Auslesen der Dateien und Üernahme in ein Array
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

                //Löen aller Dateien, die äer als 7 Tage sind
                foreach ($files as $k=>$filenameItem) {
                        if($k < time() - 604800){
                                unlink($path.'/'.$filenameItem);
                        }
                }
                //

                return $filename;
        }

        /**
         * importZipData::xmlParse()
         *
         * Auslesen und Konvertierung der XML-Daten fü spären Import
         *
         * @param mixed $file   Datei, die verarbeitet werden soll
         * @param mixed $wrapperName XML-TAg, dass die einzelnen Elemente umschließ
         * @param mixed $callback Callback-Funktion fü weitere Verarbeitung
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
         * Generierung von Thumbs fü einzelnen Ansprechpartner
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
            imagejpeg($virtual_image, $dest);
        }



}

//Instanzierung der Klasse und Aufruf der Import-Funktion
$import = new importKRLeader;
$import->importKRLeaderData();


?>
