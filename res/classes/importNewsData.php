<?php

error_reporting(E_ALL ^ E_NOTICE);
class importNewsData {

	var $newsStorageArray = array();
	
	function importNews(){
		$starttime = microtime(true);
		//Datei mit News ermitteln und als Pfad-String ühmen
		$sourceFile = $this->getSourceFile();

		$this->xmlParse($sourceFile,'Pflanzenbauempfehlung','importNewsData:callback');
		if(count($this->newsStorageArray)){
			$newsArray =  array_map('array_filter', $this->newsStorageArray);
			//Verarbeitung der einzelnen Events
			
			for($i=0;$i<count($newsArray);$i++){

				$newsData = $this->parseNewsData($newsArray[$i]);
				
				//Prüob eine News bereits bekannt und gespeichert ist.
				//Falls ja, dann Update statt Insert.
				if($this->checkNewsExists($newsData)){
					$this->updateNewsEntry($newsData);
				}else{
					$this->createNewsEntry($newsData);
				}
			
			}
		}
		
		$runtime = microtime(true) - $starttime;
		echo date('d.m.Y - H:i:s',time()). " - ImportNewsData: Laufzeit: ". $runtime ." Sekunden\n";
	}

	function callback($array){
		$this->newsStorageArray[] = $array;
	}

	/**
	 * importNewsData::createNewsEntry()
	 * Speicherung der Daten fü News
	 *
	 * @param mixed $dataArray: Array mit den Daten der News
	 * @return
	 */
	function createNewsEntry($dataArray){
		$connection = mysql_connect('localhost','goldg','OwO@B@2r') or die ("Verbindungsversuch fehlgeschlagen");
                mysql_select_db('T3_B2CAPPT',$connection);
		mysql_query("SET NAMES 'utf8'");
		mysql_query("SET CHARACTER SET 'utf8'");


		$insertArray = $dataArray['newsDetails'];
		$insertArray['tstamp'] = time();
		$insertArray['crdate'] = time();
		$insertArray['hidden'] = 1;
		$insertArray['cultivar'] = count($dataArray['newsCultures']);
		$insertFields = array();
		$insertValues = array();
		foreach($insertArray AS $key => $value){
			if($key != 'shopdata'){
				$insertFields[] = mysql_real_escape_string($key);
				$insertValues[] ='\''. mysql_real_escape_string($value) .'\'';
			}else{
				$insertFields[] = mysql_real_escape_string($key);
                                $insertValues[] ='\''. $value .'\'';
			}
		}
		
		
		$sqlQuery = 'INSERT INTO tx_agrarapp_infos ('. implode(',',$insertFields) .') VALUES ('. implode(',',$insertValues)  .')';

		$query = mysql_query($sqlQuery);
		$newsID = mysql_insert_id();

		if($dataArray['newsRegions']){
			
			foreach($dataArray['newsRegions'] AS $key => $value){
				$insertArrayLookup = array(
					'uid_local' => $newsID,
					'uid_foreign' => $value
				);
				$insertFields = array();
				$insertValues = array();
				foreach($insertArrayLookup AS $key => $value){
					$insertFields[] = mysql_real_escape_string($key);
					$insertValues[] = '\''. mysql_real_escape_string($value) .'\'';
				}
				$sqlQuery = 'INSERT INTO tx_agrarapp_infos_region_mm ('. implode(',',$insertFields) .') VALUES ('. implode(',',$insertValues)  .')';

				$query = mysql_query($sqlQuery);
				
			}
		}
		
		if($dataArray['newsCultures']){

                        foreach($dataArray['newsCultures'] AS $key => $value){
                                $insertArrayLookup = array(
                                        'uid_local' => $newsID,
                                        'uid_foreign' => $value['Id']
                                );
                                $insertFields = array();
                                $insertValues = array();
                                foreach($insertArrayLookup AS $key1 => $value1){
                                        $insertFields[] = mysql_real_escape_string($key1);
                                        $insertValues[] = '\''. mysql_real_escape_string($value1) .'\'';
                                }
                                $sqlQuery = 'INSERT INTO tx_agrarapp_infos_cultivar_mm ('. implode(',',$insertFields) .') VALUES ('. implode(',',$insertValues)  .')';

                                $query = mysql_query($sqlQuery);


                                if(isset($value['Id'])){
                                	$cultivarQuery = 'INSERT INTO tx_agrarapp_cultivar (uid,title,baywaid) VALUES ('. intval($value['Id']) .',\''. mysql_real_escape_string($value['Name']) .'\','. intval($value['Id'] ) .') ON DUPLICATE KEY UPDATE title = \''. mysql_real_escape_string($value['Name']) .'\'';
                                	$query = mysql_query($cultivarQuery);
                                }

                        }
                }

		$activateQuery = 'UPDATE tx_agrarapp_infos SET hidden = 0 WHERE uid = '. $newsID;
		mysql_query($activateQuery);

		mysql_close($connection);

	}


	/**
	 * importNewsData::updateNewsEntry()
	 * Aktualisierung der Daten fü News, anschließnd Üernahme in
	 * eine Lookup-Tabelle.
	 *
	 * @param mixed $dataArray: Array mit den Daten der News
	 * @return
	 */
	function updateNewsEntry($dataArray){
		
		$connection = mysql_connect('localhost','goldg','OwO@B@2r') or die ("Verbindungsversuch fehlgeschlagen");
                mysql_select_db('T3_B2CAPPT',$connection);
                mysql_query("SET NAMES 'utf8'");
                mysql_query("SET CHARACTER SET 'utf8'");
		
		$getIdQuery = 'SELECT uid AS uid FROM tx_agrarapp_infos WHERE baywaid = '. intval($dataArray['newsDetails']['baywaid']);
		
		$idResult = mysql_query($getIdQuery);

		$uidResult = mysql_fetch_array($idResult,MYSQL_ASSOC);

		$updateArray = $dataArray['newsDetails'];
		//Timestamp der letzten Aktualisierung ergäen
		$updateArray['tstamp'] = time();
		foreach($updateArray AS $key => $value){
			$updateSqlArray[] = $key .' = \''. $value .'\'';
		}
		
		

		$updateQuery = 'UPDATE tx_agrarapp_infos SET '. implode(',',$updateSqlArray) .' WHERE uid = '.  intval($uidResult['uid']);
		
		mysql_query($updateQuery);
		
		$deleteLookupQuery = 'DELETE FROM tx_agrarapp_infos_region_mm WHERE uid_local = '. intval($uidResult['uid']);
		
		mysql_query($deleteLookupQuery);

		$newsId = intval($uidResult['uid']);
		
		if($dataArray['newsRegions']){
			
			foreach($dataArray['newsRegions'] AS $key => $value){
				$insertArrayLookup = array(
					'uid_local' => $newsId,
					'uid_foreign' => $value
				);

				$insertFields = array();
                                $insertValues = array();
                                foreach($insertArrayLookup AS $key => $value){
                                        $insertFields[] = mysql_real_escape_string($key);
                                        $insertValues[] = '\''. mysql_real_escape_string($value) .'\'';
                                }
                                $sqlQuery = 'INSERT INTO tx_agrarapp_infos_region_mm ('. implode(',',$insertFields) .') VALUES ('. implode(',',$insertValues)  .')';

                                $query = mysql_query($sqlQuery);
				
			}
		}

		$deleteLookupQuery = 'DELETE FROM tx_agrarapp_infos_cultivar_mm WHERE uid_local = '. intval($uidResult['uid']);

                mysql_query($deleteLookupQuery);

		if($dataArray['newsCultures']){
			
                        foreach($dataArray['newsCultures'] AS $key => $value){
                                $insertArrayLookup = array(
                                        'uid_local' => $newsId,
                                        'uid_foreign' => $value['Id']
                                );
                                $insertFields = array();
                                $insertValues = array();
                                foreach($insertArrayLookup AS $key1 => $value1){
                                        $insertFields[] = mysql_real_escape_string($key1);
                                        $insertValues[] = '\''. mysql_real_escape_string($value1) .'\'';
                                }
                                $sqlQuery = 'INSERT INTO tx_agrarapp_infos_cultivar_mm ('. implode(',',$insertFields) .') VALUES ('. implode(',',$insertValues)  .')';
				
				$query = mysql_query($sqlQuery);
				
				
				if(isset($value['Id'])){
                               		$cultivarQuery = 'INSERT INTO tx_agrarapp_cultivar (uid,title,baywaid) VALUES ('. intval($value['Id']) .',\''. mysql_real_escape_string($value['Name']) .'\','. intval($value['Id'] ) .') ON DUPLICATE KEY UPDATE title = \''. mysql_real_escape_string($value['Name']) .'\'';
                              		
                                	$query = mysql_query($cultivarQuery);
				}

                        }
                }

		mysql_close($connection);

	}


	/**
	 * importNewsData::parseNewsData()
	 * Verarbeitung der einzelnen News-Datensäe in der XML-Datei.
	 * Aufbau eines Arrays, das die Daten fü nachfolgende Üernahme in die
	 * Datenbank füe einzelne News enthä.
	 *
	 * @param mixed $newsData: Array mit den Rohdaten aus der XML-Datei
	 * @return
	 */
	function parseNewsData($newsData){
		$newsDataArray = array();
		for($i=1;$i<=3;$i++){
			if(isset($newsData['Bild'. $i])){
				$data = base64_decode($newsData['Bild'. $i]);
				$md5Image = md5($data);
				$file = '/var/www/t/fileadmin/files/news_pictures/news_' . $newsData['Id'] .'_'. $md5Image .'_'. $i .'.jpg';
				$fileClean = 'fileadmin/files/news_pictures/news_' . $newsData['Id'] . '_'. $md5Image .'_'.$i;

				if(imagejpeg(imagecreatefromstring($data), $file)){
					$this->make_thumb($file,'/var/www/t/fileadmin/files/news_pictures/news_' . $newsData['Id'] .'_'. $md5Image  . '_'. $i .'_xl.jpg',768);
					$this->make_thumb($file,'/var/www/t/fileadmin/files/news_pictures/news_' . $newsData['Id'] .'_'. $md5Image  . '_'. $i .'_l.jpg',600);
					$this->make_thumb($file,'/var/www/t/fileadmin/files/news_pictures/news_' . $newsData['Id'] .'_'. $md5Image  . '_'. $i .'_m.jpg',480);
					$this->make_thumb($file,'/var/www/t/fileadmin/files/news_pictures/news_' . $newsData['Id'] .'_'. $md5Image  . '_'. $i .'_s.jpg',320);
					$pictureArray['picture'. $i] = $fileClean;
				};
				unlink($file);
				
			}else{
				$pictureArray['picture'. $i] = '';
			}
		}
			
		//News-Details füate oder Insert zusammenstellen
		$newsDataArray['newsDetails'] = array(
				'title' => $newsData['Name'],
				'abstract' => $newsData['Teaser'],
				'bodytext' => $newsData['Textkoerper1'],
				'image' => $pictureArray['picture1'],
				'bodytext2' => $newsData['Textkoerper2'],
				'image2' => $pictureArray['picture2'],
				'bodytext3' => $newsData['Textkoerper3'],
				'image3' => $pictureArray['picture3'],
				'wffinished' => $this->parseTimeToTimestamp($newsData['WorkflowAbgeschlossenAm']),
				'baywaid' => $newsData['Id'],
				'starttime' => $this->parseTimeToTimestamp($newsData['GueltigVon']) == 0 ? time() : $this->parseTimeToTimestamp($newsData['GueltigVon']),
				'endtime' => $this->parseTimeToTimestamp($newsData['GueltigBis']),
				'region' => count($newsData['Landkreise']['Landkreis']),
				'cultivar' => count($newsData['Kulturen']['Kultur'])
			
		);
			
		if(is_array($newsData['ShopLink'])){
			$newsDataArray['newsDetails']['shopdata'] = $this->processShopData($newsData['ShopLink'],$newsData['Name']);
        	}
		
		if($newsDataArray['newsDetails']['starttime'] ==  $newsDataArray['newsDetails']['endtime']){
			$newsDataArray['newsDetails']['endtime'] = $newsDataArray['newsDetails']['endtime'] + 86395;
		}

		
		//Wenn die News einer oder mehr Regionen zugeordnet ist
		//Generierung eines separaten Arrays fürdnung
		if(count($newsData['Landkreise']['Landkreis'])){
			if(isset($newsData['Landkreise']['Landkreis'][0]['Id'])){
				foreach($newsData['Landkreise']['Landkreis'] AS $key => $value){
					if(isset($value['Id'])){
						$newsDataArray['newsRegions'][] = $value['Id'];
					}
				}
			}else{
				$newsDataArray['newsRegions'][] = $newsData['Landkreise']['Landkreis']['Id'];
										
			}
			
		}else{
			$newsDataArray['newsRegions'] = FALSE;
			
		}

		if(count($newsDataArray['newsRegions']) > 300){
			$newsDataArray['newsRegions'] = FALSE;
			$newsDataArray['newsDetails']['region'] = 0;
		}

		if(count($newsData['Kulturen']['Kultur'])){
			if(isset($newsData['Kulturen']['Kultur'][0]['Id'])){
				foreach($newsData['Kulturen']['Kultur'] AS $key => $value){

					if(isset($value['Id'])){
                                		$newsDataArray['newsCultures'][] = $value;
					}
				}
                        }else{
				$newsDataArray['newsCultures'][] = array(
                                	'Id' => $newsData['Kulturen']['Kultur']['Id'],
                                        'Name' => $newsData['Kulturen']['Kultur']['Name']
                                );
                                
			}

                }else{
                    
			$newsDataArray['newsCultures'] = FALSE;
                }
			
		
		return $newsDataArray;

	}

	
	function processShopData($dataArray,$newsTitle){
		
                $shopLinkData = array();

                if($dataArray['Produktbild'] != ''){
                        $data = base64_decode($dataArray['Produktbild']);
                        $md5Image = md5($data);
                        $file = '/var/www/t/fileadmin/files/shop_pictures/shop_'. $md5Image .'.jpg';
                        $fileClean = 'fileadmin/files/shop_pictures/shop_'. $md5Image;

                        if(imagejpeg(imagecreatefromstring($data), $file)){
                                $this->make_thumb($file,'/var/www/t/fileadmin/files/shop_pictures/shop_'. $md5Image  .'_xl.jpg',768);
                                $this->make_thumb($file,'/var/www/t/fileadmin/files/shop_pictures/shop_'. $md5Image  .'_l.jpg',600);
                                $this->make_thumb($file,'/var/www/t/fileadmin/files/shop_pictures/shop_'. $md5Image  .'_m.jpg',480);
                                $this->make_thumb($file,'/var/www/t/fileadmin/files/shop_pictures/shop_'. $md5Image  .'_s.jpg',320);
                                $shopLinkData['picture'] = $fileClean;

                                unlink($file);

                        }else{
                              $shopLinkData['picture'] = false;
                        }

                }
		

		if(!is_array($dataArray['Link']) && preg_match("/\b(http|https):\/\/(|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i", $dataArray['Link'])){
			
			$urlArray = array();
			$urlArray[] = $dataArray['Link'];

			

			if(strpos($dataArray['Link'],'baywa.de') && !strpos($dataArray['Link'],'baywa.de/shop')){
                                $urlArray[] = 'campaign=agri-check/!DEVICE!/'. rawurlencode($newsTitle);
                        }elseif(strpos($dataArray['Link'],'baywa.com')){
                                $urlArray[] = 'campaign=agri-check/!DEVICE!/'. rawurlencode($newsTitle);
                        }elseif(strpos($dataArray['Link'],'baywa.de/shop')){
				$urlArray[] = 'utm_source=!DEVICE!&utm_medium=App&utm_term=Agri-Check&utm_content='. rawurlencode($newsTitle) .'&utm_campaign=App';				
			}elseif(strpos($dataArray['Link'],'planterra-saaten.de')){
				$urlArray[] = 'utm_source=!DEVICE!&utm_medium=App&utm_term=Agri-Check&utm_content='. rawurlencode($newsTitle) .'&utm_campaign=App';
			}else{
				
			}

			if(strpos($dataArray['Link'], '?')){
                                $shopLinkData['url'] = implode('&',$urlArray);
                        }else{
                                $shopLinkData['url'] = implode('?',$urlArray);
                        }


 		}else {
			return false;
 		}
		
		
		if(!is_array($dataArray['BtnText'])){
			$shopLinkData['btntext'] = $dataArray['BtnText'];
		}else{
			return false;
		}
		
	
		if(!is_array($dataArray['Teaser'])){
                        $shopLinkData['teaser'] = $dataArray['Teaser'];
                }else{
                        $shopLinkData['teaser'] = ' ';
			//return false;
                }
		

                return serialize($shopLinkData);
        }
	
	

	/**
	 * importNewsData::checkNewsExists()
	 * Üerprü ob eine News bereits mit einem vorherigen Export
	 * geliefert und in die DB ümmen wurde
	 *
	 * @param boolean: True, wenn News bereits existiert
	 * @return
	 */
	function checkNewsExists($dataArray){
		//Suche nach News mit der gleichen internen ID
		$connection = mysql_connect('localhost','goldg','OwO@B@2r') or die ("Verbindungsversuch fehlgeschlagen");
		mysql_select_db('T3_B2CAPPT',$connection);
		$sqlQuery = 'SELECT * FROM tx_agrarapp_infos WHERE baywaid = '. intval($dataArray['newsDetails']['baywaid']);
		$query = mysql_query($sqlQuery);
		

		if(mysql_num_rows($query) > 0){
			
			mysql_close($connection);
			return TRUE;
		}else{
			mysql_close($connection);
			return FALSE;
		}

	}

	/**
	 * importNewsData::getSourceFile()
	 * Ermittelt die zuletzt hochgeladene Datei im Verzeichnis und ümmt sie
	 * als Quelle fü Import-Prozess
	 *
	 * WICHTIG: Pfad fü News ist hartkodiert!!
	 *
	 *
	 * @return $filename Absolute Pfad zur aktuellen Datei
	 */
	function getSourceFile(){

		$path = '/home/aptagricheck/files/shp/news';
		$dh  = opendir($path);
		
		//Alle Dateien im Verzeichnis auslesen und in ein Array packen
		while (false !== ($filename = readdir($dh))) {
			if(is_file($path.'/'.$filename)){
				$files[filemtime($path.'/'.$filename)] = $filename;
			}
		}


		krsort($files);

		$importFile = array_slice($files,0,1);

		//Datei suchen, die mit dem letzten Äderungsdatum des Verzeichnis ünstimmt
		foreach ($files as $k=>$filenameItem) {

			
			if($k < time() - 604800){
				unlink($path.'/'.$filenameItem);
			}
		
		}
		//return '/var/www/t/newsfile.xml';
		$filePath =  $path .'/'. reset($importFile);
		//$filePath = $path .'/test.xml';	
		return $filePath;
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


	function parseTimeToTimestamp($timeString){

		return strtotime($timeString);

	}

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

$import = new importNewsData;
$import->importNews();

?>
