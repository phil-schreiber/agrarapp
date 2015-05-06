<?php

/**
 * Copyright notice
 *
 *   (c) 2013 Gregor Gold (denkfabrik groupcom GmbH) <gregor.gold@denkfabrik-group.com>
 *   All rights reserved
 *
 *   This script is part of the TYPO3 project. The TYPO3 project is
 *   free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 *   The GNU General Public License can be found at
 *   http://www.gnu.org/copyleft/gpl.html.
 *
 *   This script is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *   GNU General Public License for more details.
 *
 *   This copyright notice MUST APPEAR in all copies of the script!
 */
error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
header('Content-Type: text/html; charset=UTF-8');

require_once(PATH_tslib . 'class.tslib_pibase.php');
error_reporting(E_ALL ^ E_NOTICE);
/**
 * Plugin 'BayWa Agrar App' for the 'agrarapp' extension.
 *
 * @author Gregor Gold (denkfabrik groupcom GmbH) <gregor.gold@denkfabrik-group.com>
 * @package TYPO3
 * @subpackage tx_agrarapp
 */
/**
 * tx_agrarapp_pi1
 *
 * @package
 * @author Gregor
 * @copyright Copyright (c) 2014
 * @version $Id$
 * @access public
 */
class tx_agrarapp_pi1 extends tslib_pibase {
	public $prefixId = 'tx_agrarapp_pi1'; // Same as class name
	public $scriptRelPath = 'pi1/class.tx_agrarapp_pi1.php'; // Path to this script relative to the extension dir.
	public $extKey = 'agrarapp'; // The extension key.
	public $pi_checkCHash = true;

	/**
	 * The main method of the Plugin.
	 *
	 * @param string $content The Plugin content
	 * @param array $conf The Plugin configuration
	 * @return string The content that is displayed on the website
	 */
	public function main($content, array $conf)
	{
	
		

		$this->conf = $conf;
		$this->pi_setPiVarDefaults();
		$this->pi_loadLL();
		// Abfrage der Request-Methode
		$requestMethod = $_SERVER['REQUEST_METHOD'];
		$selectedService = trim($_GET['method']);


		$requestArray['method'] = $requestMethod;
		$requestArray['url'] = $_SERVER['REQUEST_URI'];
		$requestArray['params']['GET'] = $_GET;
		$requestArray['params']['POST'] = $_POST;

		$fileContent = json_encode($requestArray);

		//$file = '/var/www/t/typo3conf/ext/agrarapp/pi1/requestlog.txt';

		//file_put_contents($file, date('d.m.Y h:i:s',time()) .': '. $_SERVER['QUERY_STRING'] ."\n",FILE_APPEND);
		//file_put_contents($file, date('d.m.Y h:i:s',time()) .': '.  $fileContent ."\n\n\n",FILE_APPEND);

		// Unterstützte Services für GET Aufrufe
		$validModesGet = array(
			0 => 'newsheader',
		    1 => 'newscontent',
		    2 => 'eventheader',
		    3 => 'eventcontent',
		    4 => 'findcontact',
		    5 => 'findweather',
		    6 => 'locationinfo',
		    7 => 'marketdata',
		    8 => 'goodcourse',
		    9 => 'weathermaps',
		    10 => 'admindeviceregistration',
		    11 => 'getdashboardservice',
		    12 => 'background',
		    13 => 'weatherwarning',
			14 => 'findofferheaders',
			15 => 'getoffercontentbyid',
			16 => 'getplantbyid'
		);
		// Unterstütze Services für POST Aufrufe
		$validModesPost = array(
			0 => 'newsheader',
		    1 => 'getdashboardservice'
		);
		// Unterstützte Services für PUT Aufrufe
		$validModesPut = array(
			0 => 'subscribenews',
		    	1 => 'subscribeevents',
		    	2 => 'subscribeweatherwarnings',
			3 => 'subscribemarketalert',
			11 => 'admindeviceregistration'
		);
		// Je nach Aufruf-Methode und abhängig davon, ob der abgerufene Service in der
		// Liste der zulässigen Services ist, wird die entsprechende Funktion aufgerufen
		// Alle Funktionen liefern ein PHP-Array zurück, dass vor Rückgabe an die App in JSON umgewandelt wird
		if ($requestMethod == 'GET' && in_array($selectedService, $validModesGet)) {
			switch ($selectedService) {
				case 'newsheader':
					$starttime = microtime(1);
					$resultArray = $this->deliverNewsHeader();

					if ($_GET['debug'] == 1) {
						t3lib_div::debug($resultArray);
					}

					echo json_encode($resultArray);
					die();
					break;
				case 'newscontent':
					$starttime = microtime(1);
					$resultArray = $this->deliverNewsContent();
					$difference = number_format((microtime(1) - $starttime),6,',','.');
                                        $var = file_put_contents('/var/www/t/fileadmin/tests/test10.csv',$difference."\n",FILE_APPEND);
					if ($_GET['debug'] == 1) {
						t3lib_div::debug($resultArray);
					}

					echo json_encode($resultArray);
					die();
					break;
				case 'eventheader':
					$starttime = microtime(1);
					$resultArray = $this->deliverEventHeader();
					$difference = number_format((microtime(1) - $starttime),6,',','.');
					//echo $difference;
                                        $var = file_put_contents('/var/www/t/fileadmin/tests/test14.csv',$difference."\n",FILE_APPEND);
					if ($_GET['debug'] == 1) {
						t3lib_div::debug($resultArray);
					}

					echo json_encode($resultArray);
					die();
					break;
				case 'eventcontent':
					$starttime = microtime(1);
					$resultArray = $this->deliverEventContent();
					$difference = number_format((microtime(1) - $starttime),6,',','.');
                                        $var = file_put_contents('/var/www/t/fileadmin/tests/test16.csv',$difference."\n",FILE_APPEND);


					if ($_GET['debug'] == 1) {
						t3lib_div::debug($resultArray);
					}

					echo json_encode($resultArray);
					die();
					break;
				case 'findcontact':
					$resultArray = $this->deliverContactContent();

					if ($_GET['debug'] == 1) {
						t3lib_div::debug($resultArray);
					}

					echo json_encode($resultArray);
					die();
					break;
				case 'findweather':
					$starttime = microtime(1);
					$resultArray = $this->deliverWeatherData();
					$difference = number_format((microtime(1) - $starttime),6,',','.');
                                        $var = file_put_contents('/var/www/t/fileadmin/tests/test20b.csv',$difference."\n",FILE_APPEND);

					if ($_GET['debug'] == 1) {
						t3lib_div::debug($resultArray);
					}

					echo json_encode($resultArray);
					die();
					break;
				case 'locationinfo':
					$resultArray = $this->deliverLocationInfo();

					if ($_GET['debug'] == 1) {
						t3lib_div::debug($resultArray);
					}

					echo json_encode($resultArray);
					die();
					break;
				case 'marketdata':
					$starttime = microtime(1);
					$resultArray = $this->deliverMarketData();
					$difference = number_format((microtime(1) - $starttime),6,',','.');
                                        $var = file_put_contents('/var/www/t/fileadmin/tests/test24.csv',$difference."\n",FILE_APPEND);
					if ($_GET['debug'] == 1) {
						t3lib_div::debug($resultArray);
					}

					echo json_encode($resultArray);
					die();
					break;
				case 'goodcourse':
					$starttime = microtime(1);
					$resultArray = $this->deliverGoodCourse();
					$difference = number_format((microtime(1) - $starttime),6,',','.');
                                        $var = file_put_contents('/var/www/t/fileadmin/tests/test27.csv',$difference."\n",FILE_APPEND);
					if ($_GET['debug'] == 1) {
						t3lib_div::debug($resultArray);
					}

					echo json_encode($resultArray);
					die();
					break;
				case 'admindeviceregistration':
					$resultArray = $this->processRegistrationRequest();

					if ($_GET['debug'] == 1) {
						t3lib_div::debug($resultArray);
					}

					echo json_encode($resultArray);
					die();
					break;
				case 'weathermaps':
					$starttime = microtime(1);
					$resultArray = $this->deliverWeatherMaps();
					if ($_GET['debug'] == 1) {
						t3lib_div::debug($resultArray);
					}

					echo json_encode($resultArray);
					die();
					break;
				case 'getdashboardservice':
					$resultArray = $this->deliverDashboard();

					if ($_GET['debug'] == 1) {
						t3lib_div::debug($resultArray);
					}

					echo json_encode($resultArray);
					die();
					break;
				case 'background':
					$resultArray = $this->deliverBackgroundImage();

					if ($_GET['debug'] == 1) {
						t3lib_div::debug($resultArray);
					}

					echo json_encode($resultArray);
					die();
					break;
				 case 'weatherwarning':
                                        $resultArray = $this->deliverWeatherWarnings();

                                        if ($_GET['debug'] == 1) {
                                                t3lib_div::debug($resultArray);
                                        }

                                        echo json_encode($resultArray);
                                        die();
                                        break;
				case 'findofferheaders':
					$resultArray = $this->findOfferHeaders();

					if ($_GET['debug'] == 1) {
							t3lib_div::debug($resultArray);
					}

					echo json_encode($resultArray);
					die();
					break;
				case 'getoffercontentbyid':
					$resultArray = $this->getOfferContentById();

					if ($_GET['debug'] == 1) {
							t3lib_div::debug($resultArray);
					}

					echo json_encode($resultArray);
					die();
					break;
				case 'getplantbyid':
					$resultArray = $this->getPlantById();

					if ($_GET['debug'] == 1) {
							t3lib_div::debug($resultArray);
					}

					echo json_encode($resultArray);
					die();
					break;
									
				default:
					$content = '';
			}
		} elseif ($requestMethod == 'PUT' && in_array($selectedService, $validModesPut)) {
			switch ($selectedService) {
				case 'subscribenews':
					$resultArray = $this->storeSubscribeNews();
					echo json_encode($resultArray);
					die();
					break;
				case 'subscribeevents':
					$resultArray = $this->storeSubscribeEvents();
					echo json_encode($resultArray);
					die();
					break;
				case 'subscribeweatherwarnings':
                                        $resultArray = $this->storeSubscribeWarnings();
                                        echo json_encode($resultArray);
                                        die();
                                        break;
				case 'subscribemarketalert':
                                        $resultArray = $this->storeSubscribeMarketAlert();
                                        echo json_encode($resultArray);
                                        die();
                                        break;
				case 'admindeviceregistration':
					$resultArray = $this->processRegistrationRequest();
					echo json_encode($resultArray);
					die();
					break;
				default:
					$content = '';
			}
		} elseif ($requestMethod == 'POST' && in_array($selectedService, $validModesPost)) {
			switch ($selectedService) {
				case 'newsheader':
					$resultArray = $this->deliverNewsHeader();
					echo json_encode($resultArray);
					die();
					break;
				case 'getdashboardservice':

					$starttime = microtime(1);
					$resultArray = $this->deliverDashboard();
					$difference = number_format((microtime(1) - $starttime),6,',','.');
                                        $var = file_put_contents('/var/www/t/fileadmin/tests/test33.csv',$difference."\n",FILE_APPEND);
					echo json_encode($resultArray);
					die();
					break;
				default:
					$content = '';
			}
		} else {
			echo "invalid";
		}
	}

	/**
	 * tx_agrarapp_pi1::init()
	 *
	 * Initialisiert Grundeinstellungen für die Extension
	 *
	 * @param mixed $conf Konfigurationsparmeter für die Extension
	 * @return void
	 */
	function init($conf)
	{
		$this->conf = $conf; //store configuration
		$this->pi_initPIflexForm(); // Init FlexForm configuration for plugin

		//Defintion des Pfades der Extension
		$this->conf['extPath'] = $GLOBALS['TYPO3_LOADED_EXT']['agrarapp']['siteRelPath'];
		//Abfrage des Darstellungs-Modus für die Extension
		$code = $this->pi_getFFvalue($this->cObj->data['pi_flexform'], 'code', 'sGENERAL');
		$this->conf['code'] = $code ? $code : $this->cObj->stdWrap($this->conf['code'], $this->conf['code.']);
		$this->conf['code'] = strtoupper($this->conf['code']);
	}

	/**
	 * tx_agrarapp_pi1::deliverDashboard()
	 *
	 * Funktion für den Dashboard-Service. Hub-Funktion, die alle entsprechenden Unterfunktionen
	 * zusammen aufruft und die Daten in der Antwort zusammenfasst
	 *
	 * @return array $deliverArray Array mit den Dashboard-Informationen
	 */
	function deliverDashboard(){
			
		//Test ob die Funktion mit GET oder POST aufgerufen wird
		if ($_POST) {
			$getCriteria = json_decode(stripslashes($_POST['criteria']), 1);
				
			$requestMethod = 'Post';
		} else {
			$getCriteria = json_decode(stripslashes($_GET['criteria']), 1);
			
			$requestMethod = 'Get';
		}
		//Abfrage der Parameter und Transfer in Array
		$params = $getCriteria['params'];

		$requestString = json_encode($params);

		//Vorbereitung der einzelnen Parameter für die Aufrufe der Einzelfunktionen
		$weatherParams = $params['weatherZipCodes'];
		$contactParams = $params['contactZipCodes'];
		$eventParams = $params['eventZipCodes'];
		$newsParams = $params['newsParams'];
		$marketDataParams = $params['marketDataRange'];
		$goodParams = $params['marketGoodIds'];

		//Aufruf der Einzelfunktionen für die einelnen Bereiche wie News etc-
		$weatherResult = $this->deliverWeatherData($weatherParams);
		//$contactResult = $this->deliverContactContent($contactParams);
		$eventResult = $this->deliverEventHeader($eventParams);
		$newsResult = $this->deliverNewsHeader($newsParams);
		$marketResult = $this->deliverMarketData($marketDataParams,$params['deviceId']);
		$goodResult = $this->deliverGoodCourse($goodParams);

		//Zusammenfassung der Ergebnisse in einem Antwort-Array für die Rückgabe an die App
		$deliverArray = array(
            'requestDate' => substr((microtime(true) * 10000), 0, - 1),
            'errorCode' => $errorCode,
            'errorMessage' => $errorMessage,
            'weatherData' => $weatherResult['localizedWeather'],
            'marketData' => $marketResult['marketCategory'],
            'newsData' => $newsResult['newsHeaders'],
            'eventsData' => $eventResult['eventHeaders'],
            'contactsData' => null //$contactResult['contacts']
        );

		//Rückgabe an die Hauptfunktion
		return $deliverArray;
	}

	function deliverBackgroundImage(){


		//Abfrage der Parameter aus dem Service
		$getCriteria = json_decode(stripslashes($_GET['criteria']), 1);
		$params = $getCriteria['params'];

		if($params['device'] != 'smartphone' && $params['device'] != 'tablet'){
			die();
		}

		$path = '/var/www/t/fileadmin/files/backgrounds/'.$params['device'];
		$relPath = 'fileadmin/files/backgrounds/'.$params['device'];

		$dh  = opendir($path);
		$files= array();
		//Auslesen der Dateien und Übernahme in ein Array
		while (false !== ($filename = readdir($dh))) {
			if(is_file($path.'/'.$filename)){
				$files[filemtime($path.'/'.$filename)] = $filename;
			}
		}

		krsort($files);

		//Löschen aller Dateien, die älter als 7 Tage sind
		foreach ($files as $k=>$filenameItem) {
			if($k < time() - 604800){
				unlink($path.'/'.$filenameItem);
			}
		}


		$fileName = reset($files);

		$fileExploded = explode('_',$fileName);
		array_pop($fileExploded);
		$fileName = implode('_',$fileExploded);

	 	//Aufbereitung des Rückgabe-Arrays
		$resultArray = array(
            		'requestDate' => substr((microtime(true) * 10000), 0, - 1),
            		'errorCode' => $errorCode,
            		'errorMessage' => $errorMessage,
            		'pictureRef' => $relPath .'/'. $fileName
		 );
                //Rückgabe
                return $resultArray;


	}


	/**
	 * tx_agrarapp_pi1::deliverNewsHeader()
	 *
	 * Bereitstellung der News-Teaser für die App
	 *
	 * @param mixed $newsParams Newsheader-Parameter. Nur gesetzt, wenn über die Dashboard-Funktion aufgerufen
	 * @return array $resultArray Array mit den News-Teasern
	 */
	function deliverNewsHeader($newsParams = null){

		//LOG
                $start_time = microtime(true);
                $logText = date("d.m.Y h:i:s",time()) ."\t\t";

		//Überprüfung, ob $newsParams vorhanden. Falls ja, dann Aufruf über den Dashboard-Service.
		//Falls nein, dann ermittlung aus dem Request
		if ($newsParams) {
			$params = $newsParams;
		} else {
			if ($_POST) {
				$getCriteria = json_decode(stripslashes($_POST['criteria']), 1);
			} else {
				$getCriteria = json_decode(stripslashes($_GET['criteria']), 1);
			}
			$params = $getCriteria['params'];
		}
		//Vorbereitung des Arrays für die News-Daten
		$NewsData = array();

		//LOG
		$logText .= count($params) ."\t\t";

		//Durchlauf der Parameter aus dem Request, jeweils Kombination aus PLZ und Kategorie
		foreach($params AS $key => $value) {
			//Falls eine Kategorie gewählt wurde, Verwendung (z.B. Leguminosen), falls nein, dann ignorieren der Kategorie
			if ($value['categoryId'] != '') {
				$cultureID = intval($value['categoryId']);
			} else {
				$cultureID = null;
			}
			//Wenn eine PLZ definiert ist, dann Bereinigung und Verwendung, falls nein, dann Dummy-PLZ, die nicht existiert
			//Letzteres erforderlich, um saubere SQL-Queries zu generieren
			if ($value['zipCode'] != '') {
				$zipcode = $this->cleanZipCode($value['zipCode']);
				$zipcodeQuery = '=' . $zipcode;
			} else {
				$zipcodeQuery = '= 999999';
			}

			//Abfrage der Regionen, die der PLZ zugeordnet sind
			$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			'tx_agrarapp_regions.title,tx_agrarapp_regions.uid',
			'tx_agrarapp_zipcodes
					LEFT JOIN tx_agrarapp_regions_zipcodes_mm ON (tx_agrarapp_regions_zipcodes_mm.uid_foreign = tx_agrarapp_zipcodes.zip)
					LEFT JOIN tx_agrarapp_regions ON (tx_agrarapp_regions.uid = tx_agrarapp_regions_zipcodes_mm.uid_local)',
			'tx_agrarapp_zipcodes.zip ' . $zipcodeQuery,
			'tx_agrarapp_regions.uid'
			);

			//Wenn mindestens eine Region zugeordnet ist, dann Abfrage der News-Daten
			if ($GLOBALS['TYPO3_DB']->sql_num_rows($res)) {

				//Durchlauf der einzelnen Regionen und Sammlung der News-Daten
				while ($regionResult = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
					//Abfrage aller News, die der betreffenden Region zugeordnet sind, abhängig von der gewählten Kategorie
					$newsEntries = $this->getNewsHeaderData($regionResult['uid'], $regionResult['title'], $cultureID);
					//Wenn News gefunden wurden, dann Verarbeitung
					if (is_array($newsEntries)) {
						foreach($newsEntries AS $key1 => $value1) {
							//Wenn die betreffende News noch nicht bekannt ist, dann einfache Übernahme in das Array
							//Falls bereits bekannt, dann Ergänzung Prüfung, ob die betreffende Region schon bekannt ist.
							//Falls ja, dann ignorieren, falls nein, dann Ergänzung von Region-ID und Name bei der betreffenden NEws
							if (!isset($newsData[$key1])) {
								$newsData[$key1] = $value1;
							} else {
								if (!in_array($value1['regionIds'][0], $newsData[$key1]['regionIds'])) {
									$newsData[$key1]['regionIds'][] = $value1['regionIds'][0];
									$newsData[$key1]['regionNames'][] = $value1['regionNames'][0];
								}
							}
						}
					}
				}
			}

			//Abfrage von überregionalen News
			$newsAllRegions = $this->getNewsHeaderData(false, false, $cultureID);

			//Wenn es überregionale NEws gibt, dann Verarbeitung
			if (is_array($newsAllRegions)) {
				//Durchlauf der einzelnen News
				foreach($newsAllRegions AS $key2 => $value2) {
					//Falls die News noch nicht bekannt ist, dann Übernahme in das Ergebnis-Array
					//Falls bereits vorhanden, dann Prüfung, ob die betreffende Region bereits bekannt ist.
					//Falls ja, dann ignorieren, falls nein, dann Ergänzung von Region-ID und Name bei der betreffenden News
					if (!isset($newsData[$key2])) {
						$newsData[$key2] = $value2;
					} else {
						if (!in_array($value2['regionIds'][0], $newsData[$key2]['regionIds'])) {
							$newsData[$key2]['regionIds'][] = $value2['regionIds'][0];
							$newsData[$key2]['regionNames'][] = $value2['regionNames'][0];
						}
					}
				}
			}
		}
		//Absteigende Sortierung des NEws-Arrays, um die neuesten News mit der höchsten ID an den Anfang zu setzen.
		//Anschließend merge mit sich selbst, um die Array-Schlüssel zu resetten auf 0 ... n
		krsort($newsData);
		$NewsData = array_merge($newsData);

		//Durchlauf der einzelnen News, um die Regionen zu bereinigen
		foreach($NewsData AS $key => $value) {
			//Durch Merges etc. kann es in bestimmten Fällen vorkommen, dass ein leeres Element im Array bei den Regionen vorhanden ist.
			//Diese werden hier gesucht und gelöscht. Anschließend reset der Arrays
			foreach($value['regionIds'] AS $key1 => $value1) {
				if (!$value1) {
					unset($NewsData[$key]['regionIds'][$key1]);
					unset($NewsData[$key]['regionNames'][$key1]);
				}
			}
			$NewsData[$key]['regionIds'] = array_merge($NewsData[$key]['regionIds']);
			$NewsData[$key]['regionNames'] = array_merge($NewsData[$key]['regionNames']);
		}

		//Wenn keine News vorhanden sind, dann Rückgabe von NULL
		if (count($NewsData) == 0) {
			$NewsData = null;
		}

		//Aufbereitung des Arrays für die Rückgabe
		$resultArray = array(
            'requestDate' => substr((microtime(true) * 10000), 0, - 1),
            'errorCode' => $errorCode,
            'errorMessage' => $errorMessage,
            'newsHeaders' => $NewsData
            );

		//LOG
		$executionTime = microtime(true) - $start_time;
		$logText .= $executionTime ."\n";
		if(rand(0,9) == 9){
			$file = '/var/www/t/typo3conf/ext/agrarapp/pi1/logs/newsheader.log';
			file_put_contents($file, $logText ,FILE_APPEND);
		}

		//Rückgabe
		return $resultArray;
	}

	/**
	 * tx_agrarapp_pi1::getNewsHeaderData()
	 *
	 * Abfrage der News-Header für die App auf Basis von Region und Kategorie
	 *
	 * @param mixed $regionID ID der Region, für die News gesucht werden sollen
	 * @param mixed $regionName Name der Region. Wird im Ergebnis zurückgeliefert, aber nicht in Abfragen verwendet
	 * @param mixed $cultureID ID der Kategorie, für die News gesucht werden sollen
	 * @return $result Array mit den News-Teasern
	 */
	function getNewsHeaderData($regionID, $regionName, $cultureID){

		//Wenn eine Regions-ID übergeben wurde, dann Abfrage der News für diese Region.
		//Ansonsten Abfrage von überregionalen NEws
		if ($regionID) {
			//Wenn keine Kategorie übergeben wurde, werden alle Kategorien (>0) gesucht
			if ($cultureID == null) {
				$cultureQuery = ' > 0';
			} else {
				$cultureQuery = ' = ' . intval($cultureID);
			}

			//Abfrage der News für die gewählte Region und Kategorie
			$getNewsQuery = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			'tx_agrarapp_infos.uid,tx_agrarapp_infos.title,tx_agrarapp_infos.abstract,tx_agrarapp_infos.cultivar,tx_agrarapp_infos.starttime,tx_agrarapp_infos.endtime,tx_agrarapp_infos_cultivar_mm.uid_foreign',
			'tx_agrarapp_infos_region_mm
				INNER JOIN tx_agrarapp_infos_cultivar_mm ON tx_agrarapp_infos_cultivar_mm.uid_local = tx_agrarapp_infos_region_mm.uid_local
				INNER JOIN tx_agrarapp_infos ON (tx_agrarapp_infos.uid = tx_agrarapp_infos_cultivar_mm.uid_local)',
			'tx_agrarapp_infos_cultivar_mm.uid_foreign ' . $cultureQuery . ' AND tx_agrarapp_infos_region_mm.uid_foreign = ' . $regionID . $this->cObj->enableFields('tx_agrarapp_infos'),
			'tx_agrarapp_infos.uid'
			);

			/*

			   DEAKTIVIERT!!!!!
			   War im ursprünglichen Konzept vorgesehen, wird aber nicht genutzt.
			   Ausgeschaltet, um unnötige Queries zu vermeiden, aber nicht entfernt, falls wieder aktiviert werden soll!!


			   //Abfrage der PLZs, die der betreffenden Region zugeordnet sind
			   $zipCodesQuery = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			   'uid_foreign',
			   'tx_agrarapp_regions_zipcodes_mm',
			   'uid_local = ' . $regionID
			   );
			   //Vorbereitung des PLZ-Arrays
			   $zipArray = array();


			   //Durchlauf der PLZs aus dem Ergebnis. Formatierung als Standard-PLZ, da die PLZ in der Datenbank als Integer ohne führende Null gespeichert werden
			   while ($regionRow = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($zipCodesQuery)) {
			   $zipArray[] = str_pad($regionRow['uid_foreign'], 5, '0', STR_PAD_LEFT);
			   }
			*/

			//Wenn News gefunden wurden, dann Aufbereitung in einem Array
			//Falls nicht, dann Rückgabe von FALSE
			if ($GLOBALS['TYPO3_DB']->sql_num_rows($getNewsQuery)) {
				//Vorbereitung des Ergebnis-Arrays für die News-Abfrage
				$result = array();
				//Durchlauf der einzelnen Ergebnisse und Sammlung im Ergebnis-Array.
				//ID der News ist Array-Schlüssel, um in der übergeordneten Funktion die Bereinigung der Dubletten sicherzustellen
				while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($getNewsQuery)) {
					//Speicherung im Ergebnis-Array
					$result[$row['uid']] = array(
					    'newsID' => $row['uid'],
					    'title' => $row['title'],
					    'teaser' => $row['abstract'],
					    'regionIds' => array(0 => $regionID
					        ),
					    'regionNames' => array(0 => $regionName
					        ),
					    'categoryId' => !$cultureID ? $row['uid_foreign'] : $cultureID,
					    'validFromDate' => $row['starttime'] * 1000 ,
					    'validToDate' => $row['endtime'] * 1000,
					    // 'zipCodes' => count($zipArray) > 0 ? $zipArray : null,
					    'zipCodes' => null,
					    );


				}
			} else {
				$result = false;
			}
		} else {

			//Query greift, wenn keine Region ausgewählt wurde, also überregionale News für eine Kategorie abgerufen werden sollen
			$getNewsQuery = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			'tx_agrarapp_infos.uid,tx_agrarapp_infos.title,tx_agrarapp_infos.abstract,tx_agrarapp_infos.cultivar,tx_agrarapp_infos.starttime,tx_agrarapp_infos.endtime, tx_agrarapp_infos_cultivar_mm.uid_foreign',
			'tx_agrarapp_infos_cultivar_mm
				LEFT JOIN tx_agrarapp_infos ON tx_agrarapp_infos.uid = tx_agrarapp_infos_cultivar_mm.uid_local',
			'tx_agrarapp_infos.region = 0 AND tx_agrarapp_infos_cultivar_mm.uid_foreign = ' . intval($cultureID) . $this->cObj->enableFields('tx_agrarapp_infos')
			);
			//Wenn News gefunden wurden, dann Aufbereitung in einem Array
			//Falls nicht, dann Rückgabe von FALSE
			if ($GLOBALS['TYPO3_DB']->sql_num_rows($getNewsQuery)) {
				//Vorbereitung des Ergebnis-Arrays für die News-Abfrage
				$result = array();
				//Durchlauf der einzelnen Ergebnisse und Sammlung im Ergebnis-Array.
				//ID der News ist Array-Schlüssel, um in der übergeordneten Funktion die Bereinigung der Dubletten sicherzustellen

				while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($getNewsQuery)) {
					$result[$row['uid']] = array(
					    'newsID' => $row['uid'],
					    'title' => $row['title'],
					    'teaser' => $row['abstract'],
					    'regionIds' => array(0 => $regionID
					        ),
					    'regionNames' => array(0 => $regionName
					        ),
					    'categoryId' => $row['uid_foreign'],
					    'validFromDate' => $row['starttime'] * 1000,
					    'validToDate' => $row['endtime'] * 1000,
					    'zipCodes' => count($zipArray) > 0 ? $zipArray : null
					    );

				}
			} else {
				$result = false;
			}
		}
		//Rückgabe der News
		return $result;
	}

	/**
	 * tx_agrarapp_pi1::deliverNewsContent()
	 *
	 * Liefert den kompletten Inhalt für eine einzelne gewählte News mit Texten, Bildern etc.
	 *
	 * @return array $resultArray Array mit den News-Daten
	 */
	function deliverNewsContent(){

		//Abfrage der News-ID und Bereinigung
		$newsID = intval(trim($_GET['id']));

		//Abfrage der News aus der DAtenbank
		//Es erfolgt KEINE Berücksichtigung, ob die News gelöscht oder nicht aktiv ist. Diese Prüfung erfolgt erst später!
		$getNewsQuery = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
		'uid,title,abstract,bodytext,image,bodytext2,image2,bodytext3,image3,starttime,endtime,uid_foreign,deleted,shopdata',
		'tx_agrarapp_infos
			LEFT JOIN tx_agrarapp_infos_cultivar_mm ON (tx_agrarapp_infos_cultivar_mm.uid_local = tx_agrarapp_infos.uid)',
		'uid = ' . $newsID
		);
		//Auswertung des Ergebnis
		$row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($getNewsQuery);

		//Wenn eine News gefunden wurde entsprechend der ID, dann Verarbeitung
		//Ansonsten Rückgabe von NULL
		if (is_array($row)) {
			//Abfrage der Regionen, die der betreffenden News zugeordnet sind
			$regionQuery = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			'tx_agrarapp_regions.uid,tx_agrarapp_regions.title',
			'tx_agrarapp_infos_region_mm
			                LEFT JOIN tx_agrarapp_regions ON (tx_agrarapp_regions.uid = tx_agrarapp_infos_region_mm.uid_foreign)',
			'tx_agrarapp_infos_region_mm.uid_local = ' . $newsID
			);

			//Wenn Regionen gefunden wurden, dann Durchlauf und Übernahme in 2 Arrays für IDs und Namen
			if ($GLOBALS['TYPO3_DB']->sql_num_rows($regionQuery) > 0) {
				$regionNames = array();
				$regionIds = array();
				while ($regionRow = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($regionQuery)) {
					if ($regionRow['title'] && $regionRow['uid']) {
						$regionNames[] = $regionRow['title'];
						$regionIds[] = $regionRow['uid'];
					}
				}
			}
			//Zusammenstellung des NEws-Daten in einem Array
			$NewsData = array(
			    'newsID' => $row['uid'],
			    'name' => $row['title'],
			    'categoryId' => $row['uid_foreign'],
			    'messageTexts' => array(0 => $row['bodytext'] ? $row['bodytext'] : null,
			        1 => $row['bodytext2'] ? $row['bodytext2'] : null,
			        2 => $row['bodytext3'] ? $row['bodytext3'] : null
			        ),
			    'pictureRefs' => array(0 => $row['image'] ? str_replace('/var/www/t/', '', $row['image']) : null,
			        1 => $row['image2'] ? str_replace('/var/www/t/', '', $row['image2']) : null,
			        2 => $row['image3'] ? str_replace('/var/www/t/', '', $row['image3']) : null
			        ),
			    'validFromDate' => $row['starttime'] * 1000,
			    'validToDate' => $row['endtime'] * 1000,
			    'regionNames' => is_array($regionNames) ? $regionNames : array(),
			    'regionIds' => is_array($regionIds) ? $regionIds : array()
			);

			if($row['shopdata'] != ''){


				$NewsData['link'] = unserialize($row['shopdata']);

				if(!is_array($NewsData['link'])){
					$NewsData['link'] = json_decode($row['shopdata'],1);
				}

				if($_GET['device'] == 'smartphone' || $_GET['device'] == 'tablet'){
					$NewsData['link']['url'] = str_replace('!DEVICE!',$_GET['device'],$NewsData['link']['url']);
				}else{
					$NewsData['link']['url'] = str_replace('!DEVICE!','unbekannt',$NewsData['link']['url']);
				}

			}else{
				$NewsData['link'] = false;
			}


			//Wenn die News gelöscht und/oder nicht mehr gültig ist, dann Entfernung der Inhalte und Ergänzung eines Fallback-Textes
			if ($row['deleted'] == 1 || ($row['endtime'] > 0 && $row['endtime'] < time())) {
				$NewsData['messageTexts'] = array('Die von Ihnen gewählte Veranstaltung/Nachricht wurde zwischenzeitlich verschoben oder abgesagt und ist nicht mehr verfügbar. Sobald eine aktuelle Veranstaltung/Nachricht eingestellt wird, werden Sie automatisch benachrichtigt.');
				$NewsData['pictureRefs'] = array();
				$NewsData['name'] = 'Nachricht wurde gelöscht';
				$NewsData['regionNames'] = array();
				$NewsData['regionIds'] = array();
				$NewsData['link'] = false;
			}
		} else {
			$NewsData = null;
		}
		// News Fix mit Ergänzung von Disclaimer für frühe Versionen der App
		if ($row['deleted'] == 0 && count($NewsData['messageTexts']) > 1) {
			if (!isset($_GET['version'])) {
				$NewsData['messageTexts'][] = "-";
				$NewsData['messageTexts'][] = '

Wir bitten um Verständnis, dass für die Richtigkeit der in dieser Anwendung mitgeteilten Daten keine Haftung übernommen werden kann. Diese Ausführungen stellen lediglich einen unverbindlichen Hinweis dar.

Fragen zum Inhalt beantwortet Ihr persönlicher Ansprechpartner.
';
			}
		}
		//Aufbereitung des Rückgabe-Arrays
		$resultArray = array(
            'requestDate' => substr((microtime(true) * 10000), 0, - 1),
            'errorCode' => $errorCode,
            'errorMessage' => $errorMessage,
            'newsContent' => $NewsData
            );
		//Rückgabe
		return $resultArray;
	}

	/**
	 * tx_agrarapp_pi1::deliverEventHeader()
	 *
	 * Bereitstellung der Event-Header für die App
	 *
	 * @param mixed $eventParams Eventheader-Parameter. Nur gesetzt, wenn über die Dashboard-Funktion aufgerufen
	 * @return array $resultArray Array mit Eventheader-Daten
	 */
	function deliverEventHeader($eventParams = null){

		//Wenn $eventParams gesetzt wurde (bei Aufruf über Dashboard), dann Verwendung, ansonsten Abfrage aus Service
		if ($eventParams) {
			$params = $eventParams;
		} else {
			$getCriteria = json_decode(stripslashes($_GET['criteria']), 1);
			$params = $getCriteria['params'];
		}
		//Vorbereitung des Arrays für die Eventheader
		$eventData = array();

		//Abfrage der Event-Daten für die einzelnen Parameter aus dem Service
		foreach($params AS $key => $value) {
			$eventData[] = $this->getEventHeaderData($value);
		}
		//Ergänzung des Ergebnis-Arrays um überregionase Veranstaltungen nach Durchlauf der Parameter
		array_push($eventData, $this->getEventHeaderData(false));

		//Vorbereitung des finalen Ergebnis-Arrays für die Events
		$eventDataFinal = array();
		//Durchlauf der einzelnen Ergebnis-Sets für n verscshiedene PLZs zzgl. überregionale Events, um die Daten zu konsolidieren
		foreach($eventData AS $key => $value) {
			//Durchlauf der einzelnen Events aus einem Ergebnis-Set, um die einzelnen Events zu prüfen
			foreach($value AS $key1 => $value1) {
				//Wenn ein Event noch nicht im finalen Set bekannt ist (ID als Identifikation, dann einfache Übernahme.
				//Falls bekannt, dann weitere Aufbereitung.
				if (!array_key_exists($value1['eventID'], $eventDataFinal)) {
					$eventDataFinal[$value1['eventID']] = $value1;
				} else {
					//Falls ein Event bekannt ist, Ergänzung weiterer Regionen
					if (is_array($value1['regionNames'])) {
						$eventDataFinal[$value1['eventID']]['regionNames'][] = $value1['regionNames'][0];
						$eventDataFinal[$value1['eventID']]['regionIds'][] = $value1['regionIds'][0];
					} else {
						$eventDataFinal[$value1['eventID']]['regionNames'] = array();
						$eventDataFinal[$value1['eventID']]['regionIds'] = array();
					}
				}
			}
		}

		//Falls ein Event keine Regionen-Namen hat, dann Erstellung eines Dummy-Arrays für eine korrekte Rückgabe an die App
		foreach($eventDataFinal AS $key => $value) {
			if ($value['regionNames'] == '') {
				$value['regionNames'] = array();
				$value['regionIds'] = array();
			}
			//Aufbau eines neuen Arrays mit Startdaten der Events als Schlüssel
			$eventDataSort[$value['eventStartDate']][] = $value;
		}
		//Absteigende Sortierung des Hilfs-Arrays, um neueste Veranstaltungen an den Anfang zu setzen
		krsort($eventDataSort);

		//Durchlauf des nach Startdaten absteigenden sortierten Arrays
		foreach($eventDataSort AS $key1 => $value1) {
			//für jedes Startdatum werden 1 ... n Events in ein Hilfs-Array geschrieben
			foreach($value1 AS $key2 => $value2) {
				$eventArraySorted[] = $value2;
			}
		}
		//Merge mit sich selber, um Schlüssel auf 0 ...n zu normieren
		$eventDataFinal = array_merge($eventArraySorted);

		//Wenn keine Events vorhanden sind, dann Rückgabe von NULL
		if (count($eventDataFinal) == 0) {
			$eventDataFinal = null;
		}

		//Aufbau des Rückgabe-Arrays
		$resultArray = array(
            'requestDate' => substr((microtime(true) * 10000), 0, - 1),
            'errorCode' => $errorCode,
            'errorMessage' => $errorMessage,
            'eventHeaders' => $eventDataFinal
        );

		//Rückgabe
		return $resultArray;
	}

	/**
	 * tx_agrarapp_pi1::getEventHeaderData()
	 *
	 * Abfrage der Eventheader-Daten aus der Datenbank
	 *
	 * @param mixed $zipCode PLZ, für die Veranstaltungen gesucht werden
	 * @return array $result Array mit Eventheader-Daten
	 */
	function getEventHeaderData($zipCode){
		//Wenn eine PLZ übergeben wurde, dann Verwendung. Ansonsten Abfrage von überregionalen Veranstaltungen
		if ($zipCode) {
			//Bereinigung der PLZ
			$zipCode = $this->cleanZipCode($zipCode);

			//Abfrage der Region, die der PLZ zugeordnet ist
			$regionQuery = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			'uid_local,title',
			'tx_agrarapp_regions_zipcodes_mm
				LEFT JOIN tx_agrarapp_regions ON (tx_agrarapp_regions.uid = tx_agrarapp_regions_zipcodes_mm.uid_local)',
			'uid_foreign = ' . $zipCode
			);
			//Wenn eine Region gefunden wurde, dann Abfrage der Daten
			if ($GLOBALS['TYPO3_DB']->sql_num_rows($regionQuery) > 0) {
				$regionRow = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($regionQuery);

				/*

				   DEAKTIVIERT!!!!!
				   War im ursprünglichen Konzept vorgesehen, wird aber nicht genutzt.
				   Ausgeschaltet, um unnötige Queries zu vermeiden, aber nicht entfernt, falls wieder aktiviert werden soll!!


				   //Abfrage aller PLZs, die der Region zugeordnet sind
				   $zipCodesQuery = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
				   'uid_foreign',
				   'tx_agrarapp_regions_zipcodes_mm',
				   'uid_local = ' . intval($regionRow['uid_local'])
				   );
				   //Sammlung der PLZs und Ergänzung der führenden Null, da die PLZ in der Datenbank als Integer-Werte gespeichert werden.
				   while ($zipcodeRow = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($zipCodesQuery)) {
				   $zipArray[] = str_pad($zipcodeRow['uid_foreign'], 5, '0', STR_PAD_LEFT);
				   }
				*/
			}


			//Abfrage aller Events, die der betreffenden PLZ zugeordnet sind
			$getEventsQuery = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			'uid,title,abstract,zip,street,city,address_addition,datetime_start,datetime_end,starttime,endtime',
			'tx_agrarapp_regions_zipcodes_mm
				LEFT JOIN tx_agrarapp_events_regions_mm ON (tx_agrarapp_events_regions_mm.uid_foreign = tx_agrarapp_regions_zipcodes_mm.uid_local)
				LEFT JOIN tx_agrarapp_events ON (tx_agrarapp_events.uid = tx_agrarapp_events_regions_mm.uid_local)',
			'tx_agrarapp_events.datetime_end >= ' . time() . ' AND tx_agrarapp_events.markdeleted = 0 AND tx_agrarapp_regions_zipcodes_mm.uid_foreign = ' . $zipCode . $this->cObj->enableFields('tx_agrarapp_events')
			);

			//Wenn Events gefunden wurden, dann Übernahme in ein Ergebnis-Array, falls nicht, dann FALSE als Rückgabe
			if ($GLOBALS['TYPO3_DB']->sql_num_rows($getEventsQuery)) {
				$result = array();

				while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($getEventsQuery)) {

					if(($row['datetime_end'] - $row['datetime_start'] > 82800) && ($row['datetime_end'] - $row['datetime_start'] < 86400)){
						$row['datetime_end'] = $row['datetime_start'];
					}

					$result[] = array(
					    'eventID' => $row['uid'],
					    'title' => $row['title'],
					    'description' => $row['abstract'],
					    'street' => $row['street'],
					    'city' => $row['city'],
					    'zipcode' => str_pad($row['zip'], 5, '0', STR_PAD_LEFT),
					    'additionalAddressData' => $row['address_addition'],
					    'eventStartDate' => $row['datetime_start'] * 1000,
					    'eventEndDate' => $row['datetime_end'] * 1000,
					    'validFromDate' => $row['starttime'] * 1000,
					    'validToDate' => $row['endtime'] * 1000,
					    // 'zipCodes' => count($zipArray > 0) ? $zipArray : null,
					    'zipCodes' => null,
					    'regionNames' => array($regionRow['title']),
					    'regionIds' => array($regionRow['uid_local'])
					);


				}
			} else {
				$result = false;
			}
		} else {
			//Abfrage von überregionalen Events
			$getEventsQuery = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
				'uid,title,abstract,zip,street,city,address_addition,datetime_start,datetime_end,starttime,endtime',
			    'tx_agrarapp_events',
			    'tx_agrarapp_events.datetime_end >= ' . time() . ' AND markdeleted = 0 AND regions = 0' . $this->cObj->enableFields('tx_agrarapp_events')
			);

			//Wenn überregionale Events gefunden wurden, dann Verarbeitung und Übernahme in Array
			if ($GLOBALS['TYPO3_DB']->sql_num_rows($getEventsQuery)) {
				$result = array();

				while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($getEventsQuery)) {

					if(($row['datetime_end'] - $row['datetime_start'] > 82800) && ($row['datetime_end'] - $row['datetime_start'] < 86400)){
                                                $row['datetime_end'] = $row['datetime_start'];
                                        }

					$result[] = array(
					    'eventID' => $row['uid'],
					    'title' => $row['title'],
					    'description' => $row['abstract'],
					    'street' => $row['street'],
					    'city' => $row['city'],
					    'zipcode' => str_pad($row['zip'], 5, '0', STR_PAD_LEFT),
					    'additionalAddressData' => $row['address_addition'],
					    'eventStartDate' => $row['datetime_start'] * 1000,
					    'eventEndDate' => $row['datetime_end'] * 1000,
					    'validFromDate' => $row['starttime'] * 1000,
					    'validToDate' => $row['endtime'] * 1000,
					    'zipCodes' => count($zipArray > 0) ? $zipArray : null,
					    'regionNames' => false,
					    'regionIds' => false
					);
				}
			}
		}

		//Rückgabe
		return $result;
	}

	/**
	 * tx_agrarapp_pi1::deliverEventContent()
	 *
	 * Lieferung vollständiger Event-Daten für die App
	 *
	 * @return array $resultArray Event-Daten für die App
	 */
	function deliverEventContent(){
		//Abfrage und Bereinigung der Event-ID aus dem Service
		$eventID = intval(trim($_GET['id']));

		//Abfrage der Daten für das gewählte Event
		$getEventQuery = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
		    'uid,title,abstract,zip,street,city,address_addition,datetime_start,datetime_end,starttime,endtime,markdeleted',
		    'tx_agrarapp_events',
		    'uid = ' . intval($eventID)
		);
		$row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($getEventQuery);

		//Wenn ein Event gefunden wurde, dann Verarbeitung
		//Ansonsten Rückgabe von NULL
		if (is_array($row)) {
			//Abfrage aller Regionen, denen das Event zugeordnet ist
			$regionQuery = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			'tx_agrarapp_regions.uid,tx_agrarapp_regions.title',
			'tx_agrarapp_events_regions_mm
				LEFT JOIN tx_agrarapp_regions ON (tx_agrarapp_regions.uid = tx_agrarapp_events_regions_mm.uid_foreign)',
			'tx_agrarapp_events_regions_mm.uid_local = ' . $eventID
			);

			//Wenn Regionen gefunden wurden, dann Übernahme der Rregion-Namen und -IDs in zwei Arrays
			if ($GLOBALS['TYPO3_DB']->sql_num_rows($regionQuery) > 0) {
				$regionNames = array();
				$regionIds = array();
				while ($regionRow = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($regionQuery)) {
					if ($regionRow['title'] && $regionRow['uid']) {
						$regionNames[] = $regionRow['title'];
						$regionIds[] = $regionRow['uid'];
					}
				}
			}
			//Aufbau der Event-Daten in einem Array
			$eventData = array(
			    'eventID' => $row['uid'],
			    'title' => $row['title'],
			    'messageTexts' => array(0 => $row['abstract'] ? $row['abstract'] : null
			        ),
			    'pictureRefs' => array(),
			    'street' => $row['street'],
			    'city' => $row['city'],
			    'zipcode' => str_pad($row['zip'], 5, '0', STR_PAD_LEFT),
			    'additionalAddressData' => $row['address_addition'],
			    'eventStartDate' => $row['datetime_start'] * 1000,
			    'eventEndDate' => $row['datetime_end'] * 1000,
			    'validFromDate' => $row['starttime'] * 1000,
			    'validToDate' => $row['endtime'] * 1000,
			    'regionNames' => is_array($regionNames) ? $regionNames : array(),
			    'regionIds' => is_array($regionIds) ? $regionIds : array()
			);

			//Wenn ein Event gelöscht wurde, dann Löschung der Inhalte und Ergänzung von Fallback-Text
			if ($row['markdeleted'] == 1) {
				$eventData = array(
				    'eventID' => $row['uid'],
				    'title' => 'Veranstaltung wurde gelöscht',
				    'messageTexts' => array(0 => 'Die von Ihnen gewählte Veranstaltung/Nachricht wurde zwischenzeitlich verschoben oder abgesagt und ist nicht mehr verfügbar. Sobald eine aktuelle Veranstaltung/Nachricht eingestellt wird, werden Sie automatisch benachrichtigt.'
				        ),
				    'pictureRefs' => array(),
				    'street' => '',
				    'city' => '',
				    'zipcode' => '',
				    'additionalAddressData' => '',
				    'eventStartDate' => '',
				    'eventEndDate' => '',
				    'validFromDate' => '',
				    'validToDate' => '',
				    'regionNames' => '',
				    'regionIds' => ''
				    );
			}
		} else {
			$eventData = null;
		}

		//Aufbau des Rückgabe-Arrays
		$resultArray = array(
		    'requestDate' => substr((microtime(true) * 10000), 0, - 1),
		    'errorCode' => $errorCode,
		    'errorMessage' => $errorMessage,
		    'eventContent' => $eventData
		    );
		//Rückgabe
		return $resultArray;
	}

	/**
	 * tx_agrarapp_pi1::deliverContactContent()
	 *
	 * Bereitstellung der Ansprechpartner-Daten
	 *
	 * @param mixed $contactParams Ansprechpartner-Parameter. Nur gesetzt, wenn über die Dashboard-Funktion aufgerufen
	 * @return $resultArray Array mit Ansprechpartner-Daten
	 */
	function deliverContactContent($contactParams = null){

		//Wenn $contactParams gesetzt ist (über Dashboard aufgerufen), dann Verwendung, ansonsten Abfrage aus Service
		if ($contactParams) {
			$params = $contactParams;
		} else {
			$getCriteria = json_decode(stripslashes($_GET['criteria']), 1);
			$params = $getCriteria['params'];
		}

		//Vorbereitung Ergebnis-Array für Ansprechpartner
		$contactData = array();

		//Abfrage der Kontakt-Daten für die einzelnen Parameter
		foreach($params AS $key => $value) {
			$contactData[] = $this->getContactData($value);
		}
		//Vorbereitung des finalen Ansprechpartner-Arrays
		$contactDataFinal = array();
		//Durchlauf der einzelnen Ergebnis-Sets für die verschiedenen Parameter
		foreach($contactData AS $key => $value) {
			//Durchlauf der einzelnen Ansprechpartner aus den Ergebnis-Sets
			foreach($value AS $key1 => $value1) {
				//Wenn ein Ansprechpartner noch nicht bekannt ist, dann Ergänzung mit ID als Schlüssel im finalen Array
				if (!array_key_exists($value1['consultantId'], $contactDataFinal)) {
					$contactDataFinal[$value1['consultantId']] = $value1;
				}
			}
		}

		//Merge mit sich selbst, um die Schlüssel auf 0 ... n zurückzusetzen
		$contactDataFinal = array_merge($contactDataFinal);

		//Aufbau des finalen Arrays für die App mit allen Daten für die Ansprechpartner
		$contactDataFinal[] = array(
            'name' => $this->conf['defaultContact.']['name'],
            'phone' => $this->conf['defaultContact.']['phone'],
            'mobilePhone' => $this->conf['defaultContact.']['mobilePhone'],
            'email' => $this->conf['defaultContact.']['email'],
            'pictureRef' => $this->conf['defaultContact.']['pictureRef'],
            'consultantId' => 0,
            'zipCode' => 0,
            'regionID' => 0,
            'regionName' => '',
            'plants' => array(
				0 => array(
					'name' => $this->conf['defaultContact.']['locationName'],
					'email' => $this->conf['defaultContact.']['email'],
					'fax' => $this->conf['defaultContact.']['fax'],
					'phone' => $this->conf['defaultContact.']['phone'],
					'city' => $this->conf['defaultContact.']['city'],
					'zipcode' => $this->conf['defaultContact.']['zipcode'],
					'street' => $this->conf['defaultContact.']['street'],
					'division' => $this->conf['defaultContact.']['division']
				)
			)
		);
		//Wenn keine Ansprechpartner gefunden wurden, dann Rückgabe von NULL
		if (count($contactDataFinal) == 0) {
			$contactDataFinal = null;
		}

		//Aufbau des Ergebnis-Arrays
		$resultArray = array(
            'requestDate' => substr((microtime(true) * 10000), 0, - 1),
            'errorCode' => $errorCode,
            'errorMessage' => $errorMessage,
            'contacts' => $contactDataFinal
		);
		//Rückgabe
		return $resultArray;
	}

	/**
	 * tx_agrarapp_pi1::getContactData()
	 *
	 * Abfrage der Ansprechpartner für eine PLZ
	 *
	 * @param mixed $zipCode PLZ, für die Ansprechpartner gesucht werden solle
	 * @return
	 */
	function getContactData($zipCode){

		//Bereinigung der PLZ
		$zipCode = $this->cleanZipCode($zipCode);
		//Abfrage der Daten für die Ansprechpartner, die einer PLZ zugeordnet sind
		//Joins verschiedenster Tabellen, um alle benötigten Daten abzufragen. VORSICHT bei Veränderung ;)
		$getContactsQuery = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
		'tx_agrarapp_profiles.uid,tx_agrarapp_profiles.name,tx_agrarapp_profiles.phone,tx_agrarapp_profiles.mobile,tx_agrarapp_profiles.email,tx_agrarapp_profiles.picture,tx_agrarapp_profiles.zip,tx_agrarapp_regions.title AS region_title, tx_agrarapp_regions.uid AS region_uid,tx_agrarapp_locations.location AS l_title,tx_agrarapp_locations.division AS l_division,tx_agrarapp_locations.street AS l_street, tx_agrarapp_locations.zip AS l_zip,tx_agrarapp_locations.phone AS l_phone,tx_agrarapp_locations.fax AS l_fax,tx_agrarapp_locations.city AS l_city,tx_agrarapp_locations.email AS l_email',
		'tx_agrarapp_profiles_zip_mm
			LEFT JOIN tx_agrarapp_profiles ON (tx_agrarapp_profiles.uid = tx_agrarapp_profiles_zip_mm.uid_local)
			LEFT JOIN tx_agrarapp_regions_zipcodes_mm ON (tx_agrarapp_regions_zipcodes_mm.uid_foreign = ' . $zipCode . ')
			LEFT JOIN tx_agrarapp_regions ON (tx_agrarapp_regions.uid = tx_agrarapp_regions_zipcodes_mm.uid_local)
			LEFT JOIN tx_agrarapp_locations_zipcodes_mm ON (tx_agrarapp_locations_zipcodes_mm.uid_foreign = ' . $zipCode . ')
			LEFT JOIN tx_agrarapp_locations ON (tx_agrarapp_locations.uid = tx_agrarapp_locations_zipcodes_mm.uid_local)',
		'tx_agrarapp_profiles.uid != 103 AND tx_agrarapp_profiles_zip_mm.uid_foreign = ' . $zipCode . $this->cObj->enableFields('tx_agrarapp_profiles')
		);

		//Wenn Ansprechpartner gefunden wurden, dann Verarbeitung, ansonsten Rückgabe von FALSE
		if ($GLOBALS['TYPO3_DB']->sql_num_rows($getContactsQuery)) {
			//Vorbereitung des Ergebnis-Arrays
			$result = array();
			//Durchlauf der einzelnen Ergebnis-Sets
			while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($getContactsQuery)) {
				$result[] = array(
				    'name' => $row['name'],
				    'phone' => $row['phone'],
				    'mobilePhone' => $row['mobile'],
				    'email' => $row['email'],
				    'pictureRef' => str_replace('/var/www/t/', '', $row['picture']),
				    'consultantId' => $row['uid'],
				    'zipCode' => str_pad($row['zip'], 5, '0', STR_PAD_LEFT),
				    'regionId' => $row['region_uid'],
				    'regionName' => $row['region_title'],
				    'plants' => array(0 => array(
				            'name' => $row['l_title'],
				             'email' => $row['l_email'],
				            'fax' => $row['l_fax'],
				            'phone' => $row['l_phone'],
				            'city' => $row['l_city'],
				            'zipcode' => str_pad($row['l_zip'], 5, '0', STR_PAD_LEFT),
				            'street' => $row['l_street'],
				            'division' => $row['l_division']
				            )
				        )
				    );
			}
		} else {
			$result = false;
		}

		//Rückgabe
		return $result;
	}

	/**
	 * tx_agrarapp_pi1::deliverWeatherData()
	 *
	 * Bereitstellung der Wetter-Daten
	 *
	 * @param mixed $weatherParams Gesetzt, wenn über Dashboard aufgerufen,
	 * @return array $resultArray Array mit Wetterdaten
	 */
	function deliverWeatherData($weatherParams = null){

		//Wenn Parameter über den Dashboard-Service übergeben wurden, dann Verwendung, ansonsten Abfrage aus Service
		if ($weatherParams) {
			$params = $weatherParams;
			$dashboardTrigger = 1;
		} else {
			$getCriteria = json_decode(stripslashes($_GET['criteria']), 1);
			$params = $getCriteria['params'];
			$dashboardTrigger = 0;
		}
		//Vorbereitung des Ergebnis-Arrays
		$weatherData = array();

		//Abfrage der Wetter-Daten für die einzelnen Parameter
		foreach($params AS $key => $value) {
			$weatherData[] = $this->getWeatherData($value,$dashboardTrigger);
		}

		//Wenn keine Wetter-Daten gefunden wurden, dann Rückgabe von NULL
		if (count($weatherData) == 0) {
			$weatherData = null;
		}

		//Aufbau des Rückgabe-Arrays
		$resultArray = array(
            'requestDate' => substr((microtime(true) * 10000), 0, - 1),
            'errorCode' => $errorCode,
            'errorMessage' => $errorMessage,
            'localizedWeather' => $weatherData
        );

		//Rückgabe
		return $resultArray;
	}

	/**
	 * tx_agrarapp_pi1::getWeatherData()
	 *
	 * Abfrage der WEtter-DAten für eine einzelne PLZ
	 *
	 * @param mixed $zipCode Postleitzahl, für die Wetterdaten abgerufen werden
	 * @return array $totalWeatherArray Array mit allen Wetterdaten
	 */
	function getWeatherData($zipCode,$dashboardTrigger = 0){

		//Abfrage und Bereinigung der PLZ
		$zipCode = $this->cleanZipCode($zipCode);

		//Aufbau des Dateipfads zum TXT-File mit IDs der Wetterstationen im Filesystem, die der PLZ zugeordnet sind
		$currentWeatherStationsFile = 'fileadmin/files2/weatherlookup/' . str_pad($zipCode, 5, '0', STR_PAD_LEFT) . '.txt';
		//Abruf und Umwandlung in PHP Array
		$currentWeatherStations = json_decode(file_get_contents($currentWeatherStationsFile), 1);

		//Abruf der Liste aller Wetterstationen und Verarbeitung
		$stationsData = json_decode(json_encode((array) simplexml_load_file ('fileadmin/files2/import_weather/liste_messstationen.xml')), 1);
		foreach($stationsData['station'] AS $stationKey => $stationValue) {
			$stationId[$stationValue['@attributes']['id']] = $stationValue['name'];
		}
		//Abfrage der Stations-IDs aus der Gesamtliste aller Station, um die entsprechenden Wetterdaten abzurufen
		foreach($currentWeatherStations AS $key => $value) {
			$stationsArray['station' . ($key + 1)] = $stationId[$value];
		}

		//Abruf der Wetterdaten für eine bestimmte Station
		foreach($currentWeatherStations AS $key => $value) {
			$currentWeatherFile = 'fileadmin/files2/currentWeather/' . trim($value) . '.txt';
			//Wenn die Datei existiert, dann Abfruf der Daten und Abbruch. Wenn nicht, dann weiterer Durchlauf der Fallback-Stationen für die PLZ
			if (is_file($currentWeatherFile)) {
				$currentWeatherArray = json_decode(file_get_contents($currentWeatherFile), 1);
				break;
			}
		}
		//Abfrage der Wettervorhersage aus dem Filesystem auf Basis der PLZ und Umwandlung in PHP Array
		$forecastWeatherFile = 'fileadmin/files3/forecast/' . str_pad($zipCode, 5, '0', STR_PAD_LEFT) . '.txt';
		$forecastWeatherArray = json_decode(file_get_contents($forecastWeatherFile), 1);

		//Durchlauf der einzelnen Prognose-Tage und Umschreibung von einzelnen Daten in eine etwas abweichende Struktur für die App
		foreach($forecastWeatherArray['dates'] AS $key => $value) {
			//Default von 0 für Regen und Wind
			$forecastWeatherArray['dates'][$key]['chanceOfRain'] = 0;
			$forecastWeatherArray['dates'][$key]['windSpeed'] = 0;

			//Durchlauf der einzelnen Perioden (3-Stunden-Zyklus) für den betreffenden Daten. Ermittlung von Regenwahrscheinlichkeit und Windgeschwindigkeit
			//Wenn größer als vorheriger Wert, dann Übernahme, ansonsten weiter.
			//Dient dazu, das Maximum für den Tag in der Übersicht anzuzeigen.
			foreach($value['periods'] AS $key1 => $value1) {
				$currentWindSpeed = $forecastWeatherArray['dates'][$key]['windSpeed'];
				$currentChanceOfRain = $forecastWeatherArray['dates'][$key]['chanceOfRain'];

				$forecastWeatherArray['dates'][$key]['windSpeed'] = $value1['meanWindSpeed'] > $currentWindSpeed ? $value1['meanWindSpeed'] : $currentWindSpeed;
				$forecastWeatherArray['dates'][$key]['chanceOfRain'] = $value1['chanceOfRain'] > $currentChanceOfRain ? $value1['chanceOfRain'] : $currentChanceOfRain;
			}
		}

		//Übernahme des Vorhersage-Arrays in das Hauptarray
		$totalWeatherArray = $forecastWeatherArray;
		

	//Werte werden von der App nicht mehr benötigt, aber bleiben bis auf weiteres im System
		$conditionValues = array(
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

		$windDirections = array(
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
                'long' => 'südost',
                'short' => 'S-S-O'
                ),
            5 => array(
                'long' => 'südwest',
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

		$currentHour = date('h', time());
		if ($currentHour < 8 || $currentHour > 20) {
			$conditionsArray = $conditionValues['night'];
		} else {
			$conditionsArray = $conditionValues['day'];
		}


		//Ergänzung der aktuellen Wetter-Daten zum Gesamt-Array
		$totalWeatherArray['temperature'] = $currentWeatherArray['t'];
		$totalWeatherArray['relativeHumidity'] = $currentWeatherArray['rf'];
		$totalWeatherArray['conditionDayId'] = $currentWeatherArray['wz'];
		$totalWeatherArray['conditionNightId'] = $currentWeatherArray['wz'];
		$totalWeatherArray['windSpeed'] = $currentWeatherArray['wg'];
		$totalWeatherArray['windDirectionId'] = $currentWeatherArray['wr'];
		$totalWeatherArray['windDirectionLong'] = $windDirections[$currentWeatherArray['wr']]['long'];
		$totalWeatherArray['windDirectionShort'] = $windDirections[$currentWeatherArray['wr']]['short'];
		$totalWeatherArray['dewPoint'] = $currentWeatherArray['tp'];
		$totalWeatherArray['sightDist'] = $currentWeatherArray['sw'];
		$totalWeatherArray['chanceOfRain'] = $totalWeatherArray['dates'][0]['chanceOfRain'];

		//Abfrage der Region, die der PLZ zugeordnet sind
		$getRegionQuery = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
		'title,baywaid',
		'tx_agrarapp_regions_zipcodes_mm
		LEFT JOIN tx_agrarapp_regions ON (tx_agrarapp_regions.uid = tx_agrarapp_regions_zipcodes_mm.uid_local)',
		'uid_foreign = ' . $totalWeatherArray['zipCode']
		);
		$regionResult = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($getRegionQuery);
		//Ergänzung der Regions-Daten und Zeitpunkt der letzten Abfrage der Wetterdaten
		$totalWeatherArray['regionId'] = $regionResult['baywaid'];
		$totalWeatherArray['regionName'] = $regionResult['title'];
		$totalWeatherArray['datetime'] = $currentWeatherArray['importTime'];

		//Zusammenführung mit den Stationsdaten
		$totalWeatherArray = array_merge($totalWeatherArray, $stationsArray);

		//if($dashboardTrigger == 1){
			//$totalWeatherArray['dates'] = null;
		//}

		//Rückgabe
		return $totalWeatherArray;
	}

	/**
	 * tx_agrarapp_pi1::deliverLocationInfo()
	 *
	 * Bereitstellung der Standort-Daten für die Einstellungen in der App
	 *
	 * @return array $resultArray Ergebnis-Liste mit Standort-Vorschlägen (PLZ und Orte)
	 */
	function deliverLocationInfo(){
		//Abfrage der Parameter
		$getCriteria = json_decode(stripslashes($_GET['criteria']), 1);
		$params = $getCriteria['params'];


		//Wenn Geolocation-Daten übergeben wurden, dann Verwendung und Ermittlung der nächstgelegenen PLZ und Ort
		if ($params['lat'] && $params['long']) {
			$lat = round(floatval($params['lat']), 3);
			$lng = round(floatval($params['long']), 2);

			$locationSql = "SELECT city,area1,zip,region1,
			    SQRT(POW((69.1 * (tx_agrarapp_zipcodes.latitude - $lat)) , 2 ) +
			    POW((53 * (tx_agrarapp_zipcodes.longitude - $lng)), 2)) AS distance
			FROM tx_agrarapp_zipcodes
			ORDER BY distance ASC
			LIMIT 50
			";

			$locationQuery = $GLOBALS['TYPO3_DB']->sql_query($locationSql);
			//Wenn ein Ort gefunden wurde, dann Übernahme in Array
			while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($locationQuery)) {
				$locationData[] = array(
				    'city' => $row['area1'] != '' ? implode(' - ', array($row['city'], $row['area1'])) : $row['city'],
				    'zipCode' => strlen($row['zip']) == 5 ? $row['zip'] : str_pad($row['zip'], 5, '0', STR_PAD_LEFT),
				    'province' => $row['region1']
				);
			}
		}
		//Wenn kein Geolocation aber dafür PLZ übergeben wurde, dann Suche nach PLZ
		if (!$params['lat'] && $params['zipCode']) {
			//Bereinigung der PLZ
			$zipCode = $this->cleanZipCode($params['zipCode']);

			//Ermittlung, ob eine führende Null vorhanden ist. Falls ja, dann als ergänzender Parameter an die nächste Funktion.
			//Hintergrund: Teileingabe 1234 muss unterschieden werden von Volleingabe 01234.
			if (substr($params['zipCode'], 0, 1) == '0') {
				$leadingZero = 1;
			} else {
				$leadingZero = 0;
			}
			//Abfrage der Standortdaten auf Basis der PLZ. der 3. Parameter gibt an, dass die Funktion zum ersten Mal aufgerufen wird.
			//Grund ist rekursiver Aufruf, da bei "kein Treffer" die PLZ sukzessive um 1 Stelle gekürzt wird, bis Treffer vorliegt
			$locationData = $this->findLocationInfoByZip($zipCode, $leadingZero, 1);
		}
		//Wenn weder Gelocation noch PLZ sondern Ort eingeben wurde, dann entsprechende Abfrage
		if (!$params['lat'] && !$params['zipCode'] && $params['city']) {
			//Bereinigung des String
			$cityName = $this->cleanString(($params['city']));

			//Bereinigung bei Sonderzeichen. Ist aufgrund der Server-Konfiguration erforderlich. Umläute werden als HTML Entities übergeben.
			$ers = array(
			    '&Auml;' => 'Ä',
			    '&Ouml;' => 'Ö',
			    '&UUml;' => 'Ü',
			    '&auml;' => 'ä',
			    '&ouml;' => 'ö',
			    '&uuml;' => 'ü',
			    '&szlig;' => 'ß'
			);
			$cityName = strtr($cityName, $ers);

			//Vorbereitung der Abfrage
			$searchString = mysql_real_escape_string(trim($cityName));
			//Aufbau der Abfrage für Orte auf Basis des Namens, Teileingaben werden akzeptiert
			$locationSql = "SELECT city,area1,zip,region1 FROM tx_agrarapp_zipcodes
			WHERE MATCH (city,area1) AGAINST ('$searchString*' IN BOOLEAN MODE) ORDER BY city,area1";

			//Ausführung der Abfrage
			$locationQuery = $GLOBALS['TYPO3_DB']->sql_query($locationSql);
			//Durchlauf der Ergebnisse
			while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($locationQuery)) {
				$locationData[] = array(
				    'city' => $row['area1'] != '' ? implode(' - ', array($row['city'], $row['area1'])) : $row['city'],
				    'zipCode' => strlen($row['zip']) == 5 ? $row['zip'] : str_pad($row['zip'], 5, '0', STR_PAD_LEFT),
				    'province' => $row['region1']
				    );
			}
		}
		//Vorbereitung des Rückgabe-Arrays
		$resultArray = array(
		    'requestDate' => substr((microtime(true) * 10000), 0, - 1),
		    'errorCode' => $errorCode,
		    'errorMessage' => $errorMessage,
		    'locations' => $locationData
		);

		//Rückgabe
		return $resultArray;
	}


	/**
	 * tx_agrarapp_pi1::findLocationInfoByZip()
	 *
	 * @param mixed $zipCode Postleitzahl
	 * @param integer $leadingZero Angabe, ob PLZ eine führende Null enthält oder nicht
	 * @param integer $firstRun Angabe, ob der erste Durchlauf erfolgt
	 * @return
	 */
	function findLocationInfoByZip($zipCode, $leadingZero = 0, $firstRun = 0){
		//Bereinigung der übergebenen PLZ
		$zipCode = $this->cleanZipCode($zipCode);

		//Wenn eine führende Null vorhanden war und die Funktion zum ersten Mal aufgerufen wird, dann
		//nur PLZs kleiner 9999 in der DB suchen (Integer-Werte ohne führende Null) und entsprechenden Marker setzen
		if ($zipCode < 9999 && $firstRun && $leadingZero) {
			$this->findLocationShortZip = 1;
		}
		//Wenn Marker für PLZs mit führender Null gesetzt ist, dann Einschränkung PLZ-Bereich
		if ($this->findLocationShortZip) {
			$selectaddition = ' AND zip < 9999';
		}
		//Abfrage der Daten für eine PLZ
		$locationQuery = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
		    'city,area1,zip,region1',
		    'tx_agrarapp_zipcodes',
		    'zip LIKE \'' . $zipCode . '%\'' . $selectaddition
		    );
		//Wenn Ergebnisse gefunden werden, dann Übernahme in ein Ergebnis
		if ($GLOBALS['TYPO3_DB']->sql_num_rows($locationQuery)) {
			//Durchlauf aller Orte, die einer PLZ zugeordnet sind
			while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($locationQuery)) {
				$locationData[] = array(
				    'city' => $row['area1'] != '' ? implode(' - ', array($row['city'], $row['area1'])) : $row['city'],
				    'zipCode' => strlen($row['zip']) == 5 ? $row['zip'] : str_pad($row['zip'], 5, '0', STR_PAD_LEFT),
				    'province' => $row['region1']
				    );
			}
			//Rückgabe der Liste mit Orten
			return $locationData;
		} else {
			//Falls kein Treffer erzielt wurde, dann erneuter Aufruf der Funktion mit einer um die letzte Ziffer gekürzten PLZ
			return $this->findLocationInfoByZip(substr($zipCode, 0, - 1));
		}
	}

	/**
	 * tx_agrarapp_pi1::deliverMarketData()
	 *
	 * Bereitstellung von Marktdaten
	 *
	 * @param mixed $marketParams Marktdaten-Parameter, gesetzt bei Aufruf über Dashboard
	 * @return $resultArray
	 */
	function deliverMarketData($marketParams,$deviceId = ''){
			
		//Wenn $marketParams über Dashboard gesetzt wurden, dann verwenden, ansonsten Ermittlung aus Service
		if ($marketParams) {
			$params = $marketParams;
			
		} else {
			$getCriteria = json_decode(stripslashes($_GET['criteria']), 1);
			$params = $getCriteria['params'];
		}
		if($deviceId != ''){
			$params['deviceId'] = $deviceId;
		}
		
		if($params['deviceId']){
			
			$marketAlertData = $this->getMarketAlertSubscriptions($params['deviceId']);
			

		}

		//Vorbereitung WHERE-Einschränkung für die Abfrage
		$whereClause = '';
		//Wenn einzelne Produktids übergeben wurden, dann Einschränkung der Suche auf diese IDs
		//Ansonsten Abfrage aller Produkte in den Marktdaten
		if (is_array($params['goodIds']) && count($params['goodIds']) > 0) {
			foreach($params['goodIds'] AS $key => $value) {
				$cleanParamsArray[] = intval($value);
			}
			$whereClause = ' AND tx_agrarapp_marketdata.uid IN (' . implode(',', $cleanParamsArray) . ')';
		}
		//Abfrage der Marktdaten
		$getMarketData = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
		'tx_agrarapp_marketdata.uid,price,category,details,originalid,datetime,tx_agrarapp_marketdata_categories.title',
		'tx_agrarapp_marketdata
			LEFT JOIN tx_agrarapp_marketdata_categories ON (tx_agrarapp_marketdata_categories.uid = tx_agrarapp_marketdata.category)',
		'1=1 ' . $whereClause . $this->cObj->enableFields('tx_agrarapp_marketdata'),
		'',
		'sorting ASC'
		);

		//Vorbereitung des Ergebnis-Arrays
		$dataArray = array();
		//Durchlauf der Abfrage-Ergebnisse für die Marktdaten.
		while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($getMarketData)) {

			//Wenn die aktuelle Produktkategorie noch nicht im Ergebnis-Array existiert, dann anlegen mit Name
			if (!array_key_exists($row['category'], $dataArray)) {
				$dataArray[$row['category']] = array(
				    'id' => $row['category'],
				    'name' => $row['title'],
				    'marketCategory' => null,
				    'goods' => array()
				    );
			}
			//Konvertierung der Produkt-Dateils in ein PHP-Array
			$dataDetails = json_decode($row['details'], 1);
			// Hack für den Mischpreis Heizöl
			if (strpos($dataDetails['name'], 'oel')) {
				$dataDetails['name'] = str_replace('oel', 'öl', $dataDetails['name']);
			}

			$violateLimit = NULL;
			$violateFutureLimit = NULL;
			

			if($dataDetails['settlement'] == ''){
				$dataDetails['settlement'] = $dataDetails['currentprice'];
			}	
			
			if($marketAlertData){
				$violateLimit = $this->checkMarketAlertLimitViolation($row['uid'],$dataDetails['settlement'],$marketAlertData);
				$violateFutureLimit = $this->checkMarketAlertFutureLimitViolation($row['uid'],$row['originalid'],$marketAlertData,1);					
			}	


			//Übernahme der Produkt-Daten in das Ergebnis-Array als Element für die aktuelle Kategorie
			$dataArray[$row['category']]['goods'][] = array(
		                'goodId' => $row['uid'],
                		'originalId' => $row['originalid'],
		                'name' => $dataDetails['name'],
		                'description' => $dataDetails['description'],
		                'isin' => $dataDetails['isin'],
		                'currency' => $dataDetails['currency'],
                		'unit' => $dataDetails['unit'],
		                'datetime' => $row['datetime'] * 1000,
                		'openPrice' => $this->cropNumber($dataDetails['openprice']),
		                'highPrice' => $this->cropNumber($dataDetails['highprice']),
		                'lowPrice' => $this->cropNumber($dataDetails['lowprice']),
                		'currentPrice' => $this->cropNumber($dataDetails['settlement']) != '' ? $this->cropNumber($dataDetails['settlement']) : $this->cropNumber($dataDetails['currentprice']),
		                'changeNet' => $dataDetails['changeNet'] > 0 ? '+'. $this->cropNumber($dataDetails['changeNet']) : $this->cropNumber($dataDetails['changeNet']),
                		'changePercent' => $dataDetails['changePercent'] > 0 ? '+'. $this->cropNumber($dataDetails['changePercent']) : $this->cropNumber($dataDetails['changePercent']),
		                'settlement' => $this->cropNumber($dataDetails['settlement']),
                		'previousClosePrice' => $this->cropNumber($dataDetails['previousClosePrice']),
		                'previousCloseDate' => $dataDetails['previousCloseDate'] * 1000,
                		'dataGranularity' => $dataDetails['dataGranularity'],
                		'dataSource' => $dataDetails['dataSource'],
                		'hasFutureCourseData' => $dataDetails['hasFutureCourseData'],
						'violateLimit' => $violateLimit,
						'violateFutureLimit' => $violateFutureLimit
                );



		}





		//Vorbereitung des Rückgabe-Arrays
		$resultArray = array(
            'requestDate' => substr((microtime(true) * 10000), 0, - 1),
            'errorCode' => $errorCode,
            'errorMessage' => $errorMessage,
            'marketCategory' => array_merge($dataArray)
        );
		//Rückgabe
		return $resultArray;
	}



	/**
	 * tx_agrarapp_pi1::deliverGoodCourse()
	 *
	 * Bereitstellung von Kursdaten für ein einzelnes Produkt
	 *
	 * @param mixed $goodParams Gesetzt, wenn über Dashboard aufgerufen
	 * @return $resultArray
	 */
	function deliverGoodCourse($goodParams = null){
		//Wenn über DAshboard gesetzt, dann Verwendung. Ansonsten Abfrage der Parameter aus Service
		if ($goodParams) {
			$params = $goodParams;
		} else {
			$getCriteria = json_decode(stripslashes($_GET['criteria']), 1);
			$params = $getCriteria['params'];
		}
	

		//Durchlauf der einzelnen Parameter
		foreach($params AS $key => $value) {
			//Definition der gültigen Abfrage-Zeiträume
			$validRanges = array(
			    'WEEK' => '-1 week',
			    'MONTH' => '-1 month',
			    'QUARTER' => '-3 months',
			    'HALF_YEAR' => '-6 months',
			    'YEARLY' => '-1 year'
			);
			
			if($value['deviceId']){
			       
                 	       $marketAlertData = $this->getMarketAlertSubscriptions($value['deviceId']);
                	}	
			
						

			//Definition des abgefragten Produkts und des Zeitraums
			$requestedGood = intval($value['goodId']);
			$requestedRange = $this->cleanString($value['dataRange']);

			//Wenn ein gültiger Zeitraum gewählt wurde, dann Ermittlung der Daten
			if (array_key_exists($requestedRange, $validRanges)) {
				//Ermittlung des Datums als Zeitstempel, ab dem Daten abgefragt werden
				$startDate = strtotime($validRanges[$requestedRange], time());
				//Abfrage des aktuellen Preises und Produktdaten
				$currentPriceQuery = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
				    'uid,price,originalid,datetime,details',
				    'tx_agrarapp_marketdata',
				    'uid = ' . $requestedGood
				);
				$currentPriceRow = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($currentPriceQuery);
				$goodDetails = json_decode($currentPriceRow['details'], 1);

				$goodFutures = $this->getGoodFutures($currentPriceRow['originalid'],$marketAlertData,$currentPriceRow['uid']);
				
				$currentPrice = $goodDetails['settlement'] != '' ? $goodDetails['settlement'] : $goodDetails['currentprice'];
				
				$violateLimit = NULL;

				
                        	
                        	if($marketAlertData){
					$goodDetails['settlement'] = $goodDetails['settlement'] != '' ? $goodDetails['settlement'] : $goodDetails['currentprice'];
					$violateLimit = $this->checkMarketAlertLimitViolation($currentPriceRow['uid'],$goodDetails['settlement'],$marketAlertData);
							
					$violateFutureLimit = $this->checkMarketAlertLimitViolation($currentPriceRow['uid'],$goodDetails['settlement'],$marketAlertData,1);
					if($violateFutureLimit){
						$violateLimit = $violateFutureLimit;
					}		
					                              	
                        	} 
				//print_r($goodDetails);
				//Übernahme der Produktdaten in ein Zwischen-Array
				$dataArray = array(
				    'goodId' => $currentPriceRow['uid'],
				    'requestDate' => substr((microtime(true) * 10000), 0, - 1),
				    'fromDate' => $startDate * 1000,
				    'currency' => $goodDetails['currency'],
				    'unit' => $goodDetails['unit'],
				    'currentPrice' => $goodDetails['settlement'] != '' ? $this->cropNumber($goodDetails['settlement']) : $this->cropNumber($goodDetails['currentprice']),
				    'changeNet' => $goodDetails['changeNet'] > 0 ? '+'. $this->cropNumber($goodDetails['changeNet']) : $this->cropNumber($goodDetails['changeNet']),
				    'changePercent' => $goodDetails['changePercent'] > 0 ? '+'. $this->cropNumber($goodDetails['changePercent']) : $this->cropNumber($goodDetails['changePercent']),
				    'name' => $goodDetails['name'],
				    'datetime' => $goodDetails['datetime'] * 1000,
				    'values' => array(
				        date('Ymd', time()) => array(
				            'amount' => $this->cropNumber($currentPriceRow['price']),
				            'timestamp' => $currentPriceRow['datetime'] * 1000
				        )
				    ),
				    'violateLimit' => $violateLimit,
				    'futures' => $goodFutures != FALSE ? $goodFutures : NULL
				);



				//Abfrage der vorher gespeicherten Preisdaten für das Produkt
				$pastPriceQuery = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
				    'uid,price,originalid,datetime,details',
				    'tx_agrarapp_marketdata_history',
				    'originalid LIKE \'' . $currentPriceRow['originalid'] . '\' AND deleted = 0 AND datetime >= ' . $startDate,
				    '',
				    'datetime DESC'
				);
				//Durchlauf der Ergebnisse für alle historischen Preise im gewählten Zeitraum und Übernahme der Daten
				while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($pastPriceQuery)) {


					$details = json_decode($row['details'],1);

					if($details['settlement'] != $row['price'] && (strpos($currentPriceRow['originalid'],'A0Z308') || strpos($currentPriceRow['originalid'],'A0Z309'))){

						$row['price'] = $details['settlement'];
					}

					$dataArray['values'][date('Ymd', $row['datetime'])] = array(
					    'amount' => $this->cropNumber($row['price']),
					    'timestamp' => $row['datetime'] * 1000
					);
				}
				//Wenn es sich um Mischpreise der BayWa handelt (ID enthält "baywa" als String), dann Ergänzung von leeren Werten am Wochenende
				if (strpos($currentPriceRow['originalid'], 'baywa') !== false) {
					for($i = $startDate;$i < time();$i += 86400) {
						if (date('N', $i) < 6) {
							if (!array_key_exists(date('Ymd', $i), $dataArray['values'])) {
								$dataArray['values'][date('Ymd', $i)] = array(
								    'amount' => null,
								    'timestamp' => $i * 1000
								    );
							}
						}
					}
				}

				//aufsteigende Sortierung der historischen Preise
				ksort($dataArray['values']);


				//Merge des Arrays, um die Schlüssel auf 0 ... n zu setzen
				$dataArray['values'] = array_merge($dataArray['values']);


				//Vorbereitung des Rückgabe-Arrays
				$resultArray = array(
				    'requestDate' => substr((microtime(true) * 10000), 0, - 1),
				    'errorCode' => $errorCode,
				    'errorMessage' => $errorMessage,
				    'goodCourse' => $dataArray
				    );
			}

			return $resultArray;
		}
	}

	function getGoodFutures($goodID,$marketAlertData,$goodUID){

		$marketDataLookup = file_get_contents('/var/www/t/fileadmin/files/marketdata_lookup.txt');
		$marketDataLookupArray = json_decode($marketDataLookup,1);

		$futureID = $marketDataLookupArray[$goodID]['futureTitle'];

		$query = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
		        'originalid,originalid_short,datetime,details',
		        'tx_agrarapp_futures',
		        'originalid_short LIKE \''. $futureID  .'\' AND datetime > '. time(),
		        '',
		        'datetime ASC'
		);

		if($GLOBALS['TYPO3_DB']->sql_num_rows($query) > 0){

			unset($futuresArray);
			while($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($query)){

				$details = json_decode($row['details'],1);

				$violateLimit = NULL;
				
					
				$violateLimit = $this->checkMarketAlertLimitViolation($goodUID,$details['settlement'],$marketAlertData,1);	
				
				$futuresArray[] = array(
					'amount' => $this->cropNumber($details['settlement']),
					'changeNet' => $this->cropNumber($details['changeNet']) > 0 ? '+'. $this->cropNumber($details['changeNet']) : $this->cropNumber($details['changeNet']),
					'changePercent' => $this->cropNumber($details['changePercent']) > 0 ? '+'. $this->cropNumber($details['changePercent']) : $this->cropNumber($details['changePercent']),
					'timestamp' => $row['datetime'] * 1000,
					'violateLimit' => $violateLimit
				);
			}

			return $futuresArray;
		}else{
			return FALSE;
		}

	}


	function deliverWeatherWarnings(){

		$getCriteria = json_decode(stripslashes($_GET['criteria']), 1);

		$params = $getCriteria['params'];
	 	$warningLevels = array(
			'DISABLED' => FALSE,
			'LEVEL_0' => 0,
			'LEVEL_1' => 1,
			'LEVEL_2' => 2,
			'LEVEL_3' => 3,
			'LEVEL_4' => 4
		);



		$highestWarning = 0;
		$latestWarning = 0;
		foreach($params AS $key => $value){

			$zipCode = $this->cleanZipCode($value['zipCode']);
		        $warningsFile = '/var/www/t/fileadmin/files/weatherwarnings/'. $zipCode .'.txt';


                	$weatherWarnings = json_decode(file_get_contents($warningsFile), 1);



			//print_r($weatherWarnings);

			if(is_array($weatherWarnings[0])){
				foreach($weatherWarnings AS $keyWarning => $valueWarning){
					$valueWarning['zipcode'] = str_pad($valueWarning['zipcode'], 5, '0', STR_PAD_LEFT);
					if((strpos($value['warningLevel'],'LEVEL_') !== FALSE || strpos($value['warningLevel'],'DISABLED') !== FALSE) && $warningLevels[$value['warningLevel']] !== FALSE  && $valueWarning['warning_level'] >= $warningLevels[$value['warningLevel']]){

						$valueWarning['timestamp'] = $valueWarning['timestamp'] * 1000;

						if($valueWarning['valid_to'] > time() * 1000){
							$warningsResult[] = $valueWarning;
							if($valueWarning['warning_level'] > $highestWarning){
								$highestWarning = $valueWarning['warning_level'];
							}

							if($valueWarning['valid_to'] > $latestWarning){
                                                        	$latestWarning = $valueWarning['valid_to'];
                                                	}
						}
					}
				}
				continue;
			}elseif(is_array($weatherWarnings)){
				$weatherWarnings['zipcode'] = str_pad($weatherWarnings['zipcode'], 5, '0', STR_PAD_LEFT);

				if((strpos($value['warningLevel'],'LEVEL_') !== FALSE || strpos($value['warningLevel'],'DISABLED') !== FALSE) && $warningLevels[$value['warningLevel']] !== FALSE  && $weatherWarnings['warning_level'] >= $warningLevels[$value['warningLevel']]){


                                                $weatherWarnings['timestamp'] = $weatherWarnings['timestamp'] * 1000;
						if($weatherWarnings['valid_to'] > time() * 1000){
							$warningsResult[] = $weatherWarnings;
                                                	if($weatherWarnings['warning_level'] > $highestWarning){
                                                        	$highestWarning = $weatherWarnings['warning_level'];
                                                	}

							if($weatherWarnings['valid_to'] > $latestWarning){
                                                        	$latestWarning = $weatherWarnings['valid_to'];
                                                	}
						}
                                        }
			}
		}

		$warningCount = count($warningsResult);
		if($getCriteria['infoOnly']){
			$warningsResult = FALSE;
		}

		$returnArray = array(
                	'requestDate' => substr((microtime(true) * 10000), 0, - 1),
                    	'errorCode' => $errorCode,
                	'errorMessage' => $errorMessage,
			'numberOfWarnings' => $warningCount,
			'highestWarningLevel' => $highestWarning,
			'latestWarningTime' => $latestWarning,
			'localizedWarnings' => is_array($warningsResult) ? $warningsResult : NULL
                );

		return $returnArray;


	}
	
	/**
	 * tx_agrarapp_pi1::findofferheaders()
	 *
	 * Bereitstellung der Offer-Teaser für die App
	 *	
	 * @param void
	 * @return array $resultArray Array mit den Offer-Teasern
	 */	
	function findofferheaders(){
		$returnArray=array(
			'requestDate' => substr((microtime(true) * 10000), 0, -1),
		    'errorCode' => '',
		    'errorMessage' => ''
		);
		
		if ($_POST) {
			$getCriteria = json_decode(stripslashes($_POST['criteria']), 1);
		} else {
			$getCriteria = json_decode(stripslashes($_GET['criteria']), 1);
		}
		$params = $getCriteria['params'];
		$zipcodeArray=array();
		foreach($params AS $key => $value) {
			
			//Wenn eine PLZ definiert ist, dann Bereinigung und Verwendung, falls nein, dann Dummy-PLZ, die nicht existiert
			//Letzteres erforderlich, um saubere SQL-Queries zu generieren
			if ($value['zipCode'] != '') {
				$zipcode = $this->cleanZipCode($value['zipCode']);
				$zipcodeArray[] = $zipcode;
			} else {
				$zipcodeArray[] = '999999';
			}
		}
		if(!empty($zipcodeArray)){
			$query=$GLOBALS['TYPO3_DB']->exec_SELECTquery(
				'tx_agrarapp_offers.*,lvl1.uid AS lvl1Id, lvl1.title AS lvl1Title,lvl2.uid AS lvl2Id,lvl2.title AS lvl2Title',
				'tx_agrarapp_offers LEFT JOIN tx_agrarapp_offercategory AS lvl1 ON tx_agrarapp_offers.offercategory=lvl1.uid LEFT JOIN tx_agrarapp_offercategory AS lvl2 ON lvl1.parentcategory=lvl2.uid LEFT JOIN tx_agrarapp_offers_locations_mm ON tx_agrarapp_offers_locations_mm.uid_local=tx_agrarapp_offers.uid LEFT JOIN tx_agrarapp_locations_zipcodes_mm ON tx_agrarapp_offers_locations_mm.uid_foreign= tx_agrarapp_locations_zipcodes_mm.uid_local',
				'tx_agrarapp_offers.deleted=0 AND tx_agrarapp_offers.hidden=0 AND tx_agrarapp_offers.validtodate > '.time().' AND tx_agrarapp_locations_zipcodes_mm.uid_foreign IN ('.implode(',',$zipcodeArray).') GROUP BY tx_agrarapp_offers.uid ORDER BY lvl2.uid, lvl2.parentcategory,  lvl1.uid,  lvl1.title ASC, lvl2.title ASC, tx_agrarapp_offers.validFromDate DESC'
			);
			
			$mainCounter=0;
			$subCounter=0;
			$mainId=0;
			$subId=0;
			$offersMain=array();
			$offersSub=array();
			while($queryRow=$GLOBALS['TYPO3_DB']->sql_fetch_assoc($query)){															
				if($queryRow['lvl2Id']){							
					if($mainId==0){
						$mainId=$queryRow['lvl2Id'];
					}elseif($mainId != $queryRow['lvl2Id']){
						$mainId=$queryRow['lvl2Id'];
						$mainCounter++;
						$subCounter=0;
					}
					
					if($subId==0){
						$subId=$queryRow['lvl1Id'];
					}elseif($subId!=$queryRow['lvl1Id']){
						$subId=$queryRow['lvl1Id'];												
						$subCounter++;
						$offersSub=array();
					}					
					
					$offersSub[]=array(
										'offerId'=>$queryRow['uid'],
										'teaser'=>$queryRow['teaser'],
										'validFromDate'=>$queryRow['validfromdate']*1000,
										'validToDate'=>$queryRow['validtodate']*1000
									);
					$returnArray['offerCategories'][$mainCounter]['offerHeaders'] = NULL;
					$returnArray['offerCategories'][$mainCounter]['categoryName'] = $queryRow['lvl2Title'];
					$returnArray['offerCategories'][$mainCounter]['categoryId'] = $queryRow['lvl2Id'];																
					$returnArray['offerCategories'][$mainCounter]['subCategories'][$subCounter]['categoryName'] = $queryRow['lvl1Title'];
					$returnArray['offerCategories'][$mainCounter]['subCategories'][$subCounter]['categoryId'] = $queryRow['lvl1Id'];														
					$returnArray['offerCategories'][$mainCounter]['subCategories'][$subCounter]['offerHeaders']=$offersSub;
						
							
				}else{	
					$subId=0;
					if($mainId==0){
						$mainId=$queryRow['lvl1Id'];
					}elseif($mainId != $queryRow['lvl1Id']){
						$mainId=$queryRow['lvl1Id'];
						$offersMain=array();
						$mainCounter++;
					}
					
					$offersMain[]=array(
									'offerId'=>$queryRow['uid'],
									'teaser'=>$queryRow['teaser'],
									'validFromDate'=>$queryRow['validfromdate']*1000,
									'validToDate'=>$queryRow['validtodate']*1000
								);			
					$returnArray['offerCategories'][$mainCounter]['categoryName']=$queryRow['lvl1Title'];						
					$returnArray['offerCategories'][$mainCounter]['categoryId']=$queryRow['lvl1Id'];
					$returnArray['offerCategories'][$mainCounter]['offerHeaders']=$offersMain;
					$returnArray['offerCategories'][$mainCounter]['subCategories']=NULL;											
				}
				
				$counter++;
			}
		}
		
		
		
		return $returnArray;
	}
	
	/**
	 * tx_agrarapp_pi1::getOfferContentById()
	 *
	 * Bereitstellung der Offer-Details für die App
	 *	
	 * @param void
	 * @return array $resultArray Array mit den Offer-Details
	 */	
	function getOfferContentById(){
		$returnArray=array(
			'requestDate' => substr((microtime(true) * 10000), 0, -1),
		    'errorCode' => '',
		    'errorMessage' => ''
		);
		
		if ($_POST) {
			$getCriteria = json_decode(stripslashes($_POST['criteria']), 1);
		} else {
			$getCriteria = json_decode(stripslashes($_GET['criteria']), 1);
		}
		$params = $getCriteria['params'];
		$id=$params['contentId'];
		
		$query=$GLOBALS['TYPO3_DB']->exec_SELECTquery(
				'tx_agrarapp_offercategory.uid AS categoryId, tx_agrarapp_offercategory.title AS categoryName, tx_agrarapp_offers.uid AS offerId,tx_agrarapp_offers.bodytext, tx_agrarapp_offers.bodytext2, tx_agrarapp_offers.bodytext3, tx_agrarapp_offers.image, tx_agrarapp_offers.image2, tx_agrarapp_offers.image3, tx_agrarapp_offers.title AS name, tx_agrarapp_offers.url AS link, tx_agrarapp_offers.soldout, tx_agrarapp_offers.validfromdate, tx_agrarapp_offers.validtodate, tx_agrarapp_offers.uid AS offerId, tx_agrarapp_locations.uid AS plantId, tx_agrarapp_locations.location AS plantName',
				'tx_agrarapp_offers LEFT JOIN tx_agrarapp_offercategory ON tx_agrarapp_offercategory.uid=tx_agrarapp_offers.offercategory LEFT JOIN tx_agrarapp_offers_locations_mm ON tx_agrarapp_offers_locations_mm.uid_local=tx_agrarapp_offers.uid LEFT JOIN tx_agrarapp_locations ON tx_agrarapp_locations.uid=tx_agrarapp_offers_locations_mm.uid_foreign',
				'tx_agrarapp_offers.uid = '.intval($id).''
				);
		$plantCounter=0;
		while($queryRow = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($query) ){
			$returnArray['categoryId']=$queryRow['categoryId'];
			$returnArray['categoryName']=$queryRow['categoryName'];
			$returnArray['link']=$queryRow['link'];
			$returnArray['messageTexts']=array(
				0 => $queryRow['bodytext'],
				1 => $queryRow['bodytext1'],
				2 => $queryRow['bodytext2']
			);
			$returnArray['pictureRefs']=array(
				0 => $queryRow['image'],
				1 => $queryRow['image1'],
				2 => $queryRow['image2']
			);
			$returnArray['soldOut'] = $queryRow['soldout'];
			$returnArray['name'] = $queryRow['name'];
			$returnArray['offerId'] = $queryRow['offerId'];
			$returnArray['validFromDate'] = $queryRow['validfromdate']*1000;
			$returnArray['validToDate'] = $queryRow['validtodate']*1000;
			$returnArray['plantHeaders'][$plantCounter]['plantId']=$queryRow['plantId'];
			$returnArray['plantHeaders'][$plantCounter]['plantName']=$queryRow['plantName'];
			$plantCounter++;
		}
		return $returnArray;
	}
	
	/**
	 * tx_agrarapp_pi1::getPlantById()
	 *
	 * Bereitstellung der Plant Details für die App
	 *	
	 * @param void
	 * @return array $resultArray Array mit den Plant-Details
	 */	
	function getPlantById(){
		$returnArray=array(
			'requestDate' => substr((microtime(true) * 10000), 0, -1),
		    'errorCode' => '',
		    'errorMessage' => ''
		);
		
		if ($_POST) {
			$getCriteria = json_decode(stripslashes($_POST['criteria']), 1);
		} else {
			$getCriteria = json_decode(stripslashes($_GET['criteria']), 1);
		}
		
		$params = $getCriteria['params'];
		$id = $params['plantId'];
		
		$query = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
				'*',
				'tx_agrarapp_locations',
				'uid = '.$id.''
				);
		
		while($queryRow = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($query)){
			$returnArray['city'] = $queryRow['city'];
			$returnArray['contactName'] = $queryRow['email'];			
			$returnArray['division'] = $queryRow['division'];
			$returnArray['email'] = $queryRow['email'];
			$returnArray['fax'] = $queryRow['fax'];
			$returnArray['mobile'] = $queryRow['mobile'];
			$returnArray['name'] = $queryRow['location'];
			$returnArray['phone'] = $queryRow['phone'];
			$returnArray['street'] = $queryRow['street'];
			$returnArray['zipCode'] = $queryRow['zip'];
		}
		
		return $returnArray;
	}
	
	/**
	 * tx_agrarapp_pi1::storeSubscribeNews()
	 *
	 * Speicherung von News-Abos über die App
	 *
	 * @return array $resultArray Status-Array für App
	 */
	function storeSubscribeNews(){
		//Abfrage der Parameter aus dem Service
		$getCriteria = json_decode(stripslashes($_GET['criteria']), 1);
		$deviceId = $this->cleanString($getCriteria['deviceId']);
		$params = $getCriteria['params'];

		//$logfile = fopen("/var/www/t/typo3conf/ext/agrarapp/pi1/log.txt", "a"); // wird die Logdatei geöffnet

                //fwrite($logfile, serialize($_GET)); // in die Logdatei geschrieben
                //fclose($logfile); // und zum Schluss wird die Logdatei wieder geschlossen





		//Löschung aller bisherigen News-Abos für die gewählte Device ID
		$GLOBALS['TYPO3_DB']->exec_DELETEquery(
		    'tx_agrarapp_subscriptions',
		    'deviceid = \'' . $deviceId . '\' AND subtype = 0'
		);

		//Neuer Aufbau der Abos. Kombinationen aus PLZ und Kategorie
		//Alle Abos werden in einer Tabelle gespeichert, daher "subtype=0" für News
		foreach($params AS $key => $value) {
			$zipCode = $this->cleanZipCode($value['zipCode']);

			$insertArray = array(
			    'tstamp' => time(),
			    'crdate' => time(),
			    'category' => intval($value['categoryId']),
			    'zipcode' => $zipCode,
			    'subtype' => 0,
			    'deviceid' => $deviceId
			    );

			$insertQuery = $GLOBALS['TYPO3_DB']->exec_INSERTquery(
			    'tx_agrarapp_subscriptions',
			    $insertArray
			    );
		}
		//Vorbereitung Rückgabe-Array
		$resultArray = array(
		    'requestDate' => substr((microtime(true) * 10000), 0, 1),
		    'errorCode' => $errorCode,
		    'errorMessage' => $errorMessage
		);
		//Rückgabe
		return $resultArray;
	}

	/**
	 * tx_agrarapp_pi1::storeSubscribeEvents()
	 *
	 * Speicherung von Event-Abos über die App
	 *
	 * @return array $resultArray Status-Array für App
	 */
	function storeSubscribeEvents(){
		//Abfrage der Parameter aus dem Service
		$getCriteria = json_decode(stripslashes($_GET['criteria']), 1);
		$deviceId = $this->cleanString($getCriteria['deviceId']);
		$params = $getCriteria['params'];

		//Löschung aller bisherigen Event-Abos für die gewählte Device ID
		$GLOBALS['TYPO3_DB']->exec_DELETEquery(
		    'tx_agrarapp_subscriptions',
		    'deviceid = \'' . $deviceId . '\' AND subtype = 1'
		);

		//Neuer Aufbau der Abos für die PLZs
		//Alle Abos werden in einer Tabelle gespeichert, daher "subtype=1" für Events
		foreach($params AS $key => $value) {
			$zipCode = $this->cleanZipCode($value);

			$insertArray = array(
			    'tstamp' => time(),
			    'crdate' => time(),
			    'zipcode' => $zipCode,
			    'subtype' => 1,
			    'deviceid' => $deviceId
			    );

			$insertQuery = $GLOBALS['TYPO3_DB']->exec_INSERTquery(
			    'tx_agrarapp_subscriptions',
			    $insertArray
			    );
		}

		//Vorbereitung Rückgabe-Array
		$resultArray = array(
		    'requestDate' => substr((microtime(true) * 10000), 0, - 1),
		    'errorCode' => $errorCode,
		    'errorMessage' => $errorMessage
		);

		//Rückgabe
		return $resultArray;
	}

	/**
         * tx_agrarapp_pi1::storeSubscribeWarnings()
         *
         * Speicherung von Wetterwarnung-Abos über die App
         *
         * @return array $resultArray Status-Array für App
         */
    function storeSubscribeWarnings(){
                //Abfrage der Parameter aus dem Service
                $getCriteria = json_decode(stripslashes($_GET['criteria']), 1);
                $deviceId = $this->cleanString($getCriteria['deviceId']);
                $params = $getCriteria['params'];

                //Löschung aller bisherigen Event-Abos für die gewählte Device ID
                $GLOBALS['TYPO3_DB']->exec_DELETEquery(
                    'tx_agrarapp_subscriptions',
                    'deviceid = \'' . $deviceId . '\' AND subtype = 2'
                );

                //Neuer Aufbau der Abos für die PLZs
                //Alle Abos werden in einer Tabelle gespeichert, daher "subtype=1" für Events
                foreach($params AS $key => $value) {
                        $zipCode = $this->cleanZipCode($value['zipCode']);

			$warningLevelArray = explode('_',$value['warningLevel']);

			if(count($warningLevelArray) == 2 && $warningLevelArray[1] >= 0 && $warningLevelArray[1] <= 4){
				$warningLevel = intval($warningLevelArray[1]);
			}else{
				$warningLevel = -1;
			}

			$kfzQuery = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
				'kfz',
				'tx_agrarapp_zipcodes',
				'zip ='. $zipCode,
				'kfz'
			);

			$kfzResult = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($kfzQuery);

                        $insertArray = array(
                            'tstamp' => time(),
                            'crdate' => time(),
                            'zipcode' => $zipCode,
			    'kfz' => $kfzResult['kfz'],
			    'category' => $warningLevel,
                            'subtype' => 2,
                            'deviceid' => $deviceId
                        );

                        $insertQuery = $GLOBALS['TYPO3_DB']->exec_INSERTquery(
                            'tx_agrarapp_subscriptions',
                            $insertArray
                        );
                }

                //Vorbereitung Rückgabe-Array
                $resultArray = array(
                    'requestDate' => substr((microtime(true) * 10000), 0, - 1),
                    'errorCode' => $errorCode,
                    'errorMessage' => $errorMessage
                );

                //Rückgabe
                return $resultArray;
        }

	/**
	 * tx_agrarapp_pi1::storeSubscribeMarketAlert()
	 *
	 * Speicherung von Market Alerts
	 *
	 * @return array $resultArray Status-Array für App
	 */
	function storeSubscribeMarketAlert(){
		//Abfrage der Parameter aus dem Service
		$getCriteria = json_decode(stripslashes($_GET['criteria']), 1);
		$deviceId = $this->cleanString($getCriteria['deviceId']);
		$params = $getCriteria['params'];


		$marketDataLookup = file_get_contents('/var/www/t/fileadmin/files/marketdata_alert_lookup.txt');
		$marketDataLookupArray = json_decode($marketDataLookup,1);

		$futureStatus = $marketDataLookupArray[$goodID]['hasFuture'];
		$futureStatus = 1;
		
		$marketAlertArray = $this->getMarketAlertSubscriptions($deviceId);

	
		//Löschung aller bisherigen Event-Abos für die gewählte Device ID
		$GLOBALS['TYPO3_DB']->exec_DELETEquery(
		    'tx_agrarapp_subscriptions',
		    'deviceid = \'' . $deviceId . '\' AND subtype = 3'
		);
		
		if($getCriteria['enablePush'] == 1){
			$hidden = 0;
		}else{
			$hidden = 1;
		}

		foreach($params AS $key => $value) {
			$insertArray = array();	
			$insertArrayFinal = array();	
			$goodID = intval($value['goodId']);
			$lowerLimit = $value['lowerLimit'] != "" ? abs(round(floatval(str_replace(',','.',$value['lowerLimit'])),3)) : NULL;
			$upperLimit = $value['upperLimit'] != "" ? abs(round(floatval(str_replace(',','.',$value['upperLimit'])),3)) : NULL;
			
	
			if($marketAlertArray[$goodID][0][0] == $lowerLimit){
			
				$limitTime['course'][0] = $marketAlertArray[$goodID][0]['limittime'][0] > 0 ? $marketAlertArray[$goodID][0]['limittime'][0] : 0;
			}
			if($marketAlertArray[$goodID][0][1] == $upperLimit){
                                
				$limitTime['course'][1] = $marketAlertArray[$goodID][0]['limittime'][1] > 0 ? $marketAlertArray[$goodID][0]['limittime'][1] : 0;
                        }
			if($marketAlertArray[$goodID][1][0] == $lowerLimit){
                                
				$limitTime['future'][0] = $marketAlertArray[$goodID][1]['limittime'][1] > 0 ? $marketAlertArray[$goodID][1]['limittime'][0] : 0;
                        }
			if($marketAlertArray[$goodID][1][1] == $upperLimit){
                               
				$limitTime['future'][1] = $marketAlertArray[$goodID][1]['limittime'][1] > 0 ? $marketAlertArray[$goodID][1]['limittime'][1] : 0;
                        }
				
		

		

			//Speicherung Untergrenze normaler Kurswert
			$insertArray['regular_low'] = array(
					'tstamp' => time(),
					'crdate' => time(),
					'hidden' => $hidden,
					'subtype' => 3,
					'limitid' => $goodID,
					'limittype' => 0,
					'limitborder' => 0,
					'limitvalue' => $lowerLimit,
					'limittime' => $limitTime['course'][0],
					'deviceid' => $deviceId
			);
			//Speicherung Obergrenze normaler Kurswert
			$insertArray['regular_high'] = array(
					'tstamp' => time(),
					'crdate' => time(),
					'hidden' => $hidden,
					'subtype' => 3,
					'limitid' => $goodID,
					'limittype' => 0,
					'limitborder' => 1,
					'limitvalue' => $upperLimit,
					'limittime' => $limitTime['course'][1],
					'deviceid' => $deviceId
			);

			if($futureStatus == 1){
				//Speicherung Untergrenze Future
					$insertArray['future_low'] = array(
						'tstamp' => time(),
						'crdate' => time(),
						'hidden' => $hidden,
						'subtype' => 3,
						'limitid' => $goodID,
						'limittype' => 1,
						'limitborder' => 0,
						'limitvalue' => $lowerLimit,
						'limittime' => $limitTime['future'][0],
						'deviceid' => $deviceId
				);
					//Speicherung Obergrenze Future
					$insertArray['future_high'] = array(
						'tstamp' => time(),
						'crdate' => time(),
						'hidden' => $hidden,
						'subtype' => 3,
						'limitid' => $goodID,
						'limittype' => 1,
						'limitborder' => 1,
						'limitvalue' => $upperLimit,
						'limittime' => $limitTime['future'][1],
						'deviceid' => $deviceId
				);
			}


			foreach($insertArray AS $key => $value){
				$arrayValues = array();
				foreach(array_values($value) AS $valKey => $valValue){
					if($valValue != NULL AND !is_int($valValue)){
						$arrayValues[] = '\''. $valValue .'\'';
					}elseif($valValue == '' AND $valValue !== 0){
						$arrayValues[] = 'NULL';
					}else{
						$arrayValues[] = $valValue;
					}
				};
				$insertString = 'INSERT INTO tx_agrarapp_subscriptions ('. implode(',',array_keys($value)) .') VALUES ';
				$insertArrayFinal[] = '('. implode(',', $arrayValues) .')';

			}

			$insertString .= implode(',',$insertArrayFinal) .';';



			$GLOBALS['TYPO3_DB']->sql_query($insertString);

			unset($insertArray);
			unset($insertArrayFinal);
			$insertString = '';

		}

		//Vorbereitung Rückgabe-Array
		$resultArray = array(
		    'requestDate' => substr((microtime(true) * 10000), 0, - 1),
		    'errorCode' => $errorCode,
		    'errorMessage' => $errorMessage
		);

		//Rückgabe
		return $resultArray;
	}


	/**
	 * tx_agrarapp_pi1::processRegistrationRequest()
	 *
	 * Verarbeitung der Registrierungs-Anfragen einer App
	 *
	 * @return array $resultArray Rückgabe mit Statusinformationen an die App
	 */
	function processRegistrationRequest(){
		//Abfrage der Parameter aus dem Service
		$getCriteria = json_decode(stripslashes($_GET['criteria']), 1);
		$getCriteria['action'] = $this->cleanString($getCriteria['action']);

		//Definition der gültigen Requests je nach Methode
		$validRequests = array(
		    'GET' => array(
		        'INFO' => 'valid',
		        'REGISTER' => 'valid',
		        'DEREGISTER' => 'valid'
		        ),
		    'PUT' => array(
		        'REGISTER' => 'valid',
		        'DEREGISTER' => 'valid'
		        )
		    );
		// Überprüfung, ob der Request gültig ist, Falls ja, dann Bearbeitung, falls nein, dann Rückgabe von NULL
		if ($validRequests[$_SERVER['REQUEST_METHOD']][$getCriteria['action']] === 'valid') {
		} else {
			return null;
		}
		//FALL: Gerät soll registriert werden
		if ($getCriteria['action'] === 'REGISTER') {
			//Prüfung, ob das Gerät mit der ID bereits bekannt ist
			$checkDeviceQuery = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			    'uid',
			    'tx_agrarapp_devices',
			    'deviceid = \'' . $getCriteria['deviceId'] . '\''
			);
			//Falls nein, dann Erfassung des Geräts in der Datenbank
			//Falls ja, dann Aktualisierung des Datenbank-Eintrags und Rückgabe von Informationen zu den gespeicherten Daten
			if ($GLOBALS['TYPO3_DB']->sql_num_rows($checkDeviceQuery) === 0) {
				//Vorbereitung Array mit Daten für das Gerät
				$insertArray = array(
				    'tstamp' => time(),
				    'crdate' => time(),
				    'deviceid' => $getCriteria['deviceId'],
				    'token' => $getCriteria['token'],
				    'ostype' => $getCriteria['osType'],
				    'appversion' => intval(ltrim(trim($_GET['version']),'0'))

				);
				//Ergänzung in der DB
				$GLOBALS['TYPO3_DB']->exec_INSERTquery(
				    'tx_agrarapp_devices',
				    $insertArray
				);
				//Vorbereitung Rückgabe-Array für App
				$detailsArray = array(
				    'deviceId' => $getCriteria['deviceId'],
				    'status' => 'REGISTERED'
				);
			} else {
				//Aktualisierungs-Array für das Gerät
				$updateArray = array(
				    'tstamp' => time(),
				    'deleted' => 0,
				    'token' => $getCriteria['token'],
				    'appversion' => intval(ltrim(trim($_GET['version']),'0'))
				);
				//Aktualisierung der Daten
				$GLOBALS['TYPO3_DB']->exec_UPDATEquery(
				    'tx_agrarapp_devices',
				    'deviceid = \'' . $getCriteria['deviceId'] . '\'',
				    $updateArray
				);
				//Vorbereitung Rückgabe-Array für App
				$detailsArray = array(
				    'deviceId' => $getCriteria['deviceId'],
				    'status' => 'REGISTERED'
				);
				//Ergänzung von Informationen zu bereits bestehenden Abos für Events oder NEws
				$detailsArray['newsSubsriptions'] = $this->getSubscriptions($getCriteria['deviceId'], 'NEWS');
				$detailsArray['eventSubsriptions'] = $this->getSubscriptions($getCriteria['deviceId'], 'EVENTS');
			}
		}

		//Fall: Gerät soll abgemeldet werden in der Datenbank
		if ($getCriteria['action'] === 'DEREGISTER') {
			//Prüfung, ob das Gerät bereits registriert ist
			$checkDeviceQuery = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			    'uid',
			    'tx_agrarapp_devices',
			    'deviceid = \'' . $getCriteria['deviceId'] . '\''
			);
			//Falls bekannt, dann wird das Gerät als deaktiviert marktiert und alle Abos gelöscht
			if ($GLOBALS['TYPO3_DB']->sql_num_rows($checkDeviceQuery) === 1) {
				$this->deleteSubscriptions($getCriteria['deviceId']);

				$updateArray = array(
				    'tstamp' => time(),
				    'deleted' => 1,
			);

				$GLOBALS['TYPO3_DB']->exec_UPDATEquery(
				    'tx_agrarapp_devices',
				    'deviceid = \'' . $getCriteria['deviceId'] . '\'',
				    $updateArray
				);
			}
			//Vorbereitung Rückgabe-Array für App
			$detailsArray = array(
			    'deviceId' => $getCriteria['deviceId'],
			    'status' => 'DEREGISTERED'
			);
		}
		//Fall: Status-Abfrage der App zu dem aktuellen Gerät
		elseif ($getCriteria['action'] === 'INFO') {
			//Prüfung, ob das Gerät registriert ist
			$checkDeviceQuery = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			    'uid',
			    'tx_agrarapp_devices',
			    'deviceid = \'' . trim($getCriteria['deviceId']) . '\''
			);

			//Falls ja, dann Rückgabe des Status und aller aktuell laufenden Abos für News und Events
			//Falls nein, dann Rückgabe entsprechender Info
			if ($GLOBALS['TYPO3_DB']->sql_num_rows($checkDeviceQuery) === 1) {
				$detailsArray = array(
				    'deviceId' => $getCriteria['deviceId'],
				    'status' => 'REGISTERED'
				    );

				$detailsArray['newsSubsriptions'] = $this->getSubscriptions($getCriteria['deviceId'], 'NEWS');
				$detailsArray['eventSubsriptions'] = $this->getSubscriptions($getCriteria['deviceId'], 'EVENTS');
			} else {
				$detailsArray = array(
				    'deviceId' => $getCriteria['deviceId'],
				    'status' => 'UNREGISTERED'
				    );
			}
		}
		//Aufbau Rückgabe-Array mit Basis-Infos
		$baseArray = array(
		    'requestDate' => substr((microtime(true) * 10000), 0, - 1),
		    'errorCode' => $errorCode,
		    'errorMessage' => $errorMessage
		);

		//Ergänzung Detail-Daten für die App
		$resultArray = array_merge($baseArray, $detailsArray);

		//Rückgabe
		return $resultArray;
	}

	/**
	 * tx_agrarapp_pi1::getSubscriptions()
	 *
	 * Abfrage der bestehenden Abos für eine Geräte-ID nach Typ
	 *
	 * @param mixed $deviceId ID des Geräts
	 * @param mixed $type Art des Abos, 0 = News, 1 = Event
	 * @return array $resultArray Array mit bestehenden Abos
	 */
	function getSubscriptions($deviceId, $type){
		switch ($type) {
			case 'NEWS':
				$subtype = 0;
				break;
			case 'EVENTS':
				$subtype = 1;
				break;
			default:
				$subtype = - 1;
		}

		//Abfrage bestehender Abos für die ID
		$getSubscriptions = $GLOBALS['TYPO3_DB']->exec_SELECTquery($selectQuery,
		    'tx_agrarapp_subscriptions',
		    'deviceid = \'' . $this->cleanString($deviceId) . '\''
		    );

		$resultArray = array();

		//Durchlauf der Ergebnis-Listen.
		//Bei Events wird nur die PLZ zurückgegeben
		while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($getSubscriptions)) {
			if ($subtype === 0) {
				$resultArray[] = $row;
			} else {
				$resultArray[] = $row['zipCode'];
			}
		}
		//Rückgabe
		return $resultArray;
	}

	/**
	 * tx_agrarapp_pi1::deleteSubscriptions()
	 *
	 * Löschen der bestehenden Abos für ein Gerät
	 *
	 * @param mixed $deviceID Geräte-ID
	 * @return void
	 */
	function deleteSubscriptions($deviceID){
		$GLOBALS['TYPO3_DB']->exec_DELETEquery(
		    'tx_agrarapp_subscriptions',
		    'deviceid = \'' . $this->cleanString($deviceId) . '\''
		);
	}

	/**
	 * tx_agrarapp_pi1::deliverWeatherMaps()
	 *
	 * Bereitstellung der Wetterkarten für die App
	 *
	 * @return array $resultArray Wetterkarten-Details entsprechend Request
	 */
	function deliverWeatherMaps(){

		//Abfrage der Parameter aus dem Service
		$getCriteria = json_decode(stripslashes($_GET['criteria']), 1);
		$params = $getCriteria['params'];
		//Definition der "animierten" Maps mit Slideshow von Einzelbildern
		$animatedMaps = array(
        	0 => 'RADAR',
            1 => 'RAIN',
            2 => 'SAT_GER',
            3 => 'SAT_EU'
        );
		//Definition von "statischen" Maps mit einem Einzelbild
		$staticMaps = array(
        	0 => 'HUMIDITY',
            1 => 'TEMP',
            2 => 'DEW'
        );
		//Nicht mehr
		$globalMaps = array(
            // 1 => 'SAT_EU'
        );

		//Wenn ein Karten-Typ abgefragt wird, dann Verarbeitung
		if(count($params['mapTypes'])) {
			//Counter für die Ergebnis-Liste der einzelnen angeforderten KArten-Typen
			$i = 0;
			//Für jeden abgefragten Karten-Typ wird geprüft, welche Karte gefragt ist,
			//welche Region gewünscht ist und welcher Zeitraum abgefragt wird
			foreach($params['mapTypes'] AS $key => $value) {
				$mapResult[$i] = array(
				    'mapType' => $this->cleanString($value),
				    'mapRange' => $this->cleanString($params['mapRange']),
				    'mapRegion' => $this->cleanString($params['mapRegion'])
				);

				//Prüfung, ob es sich bei der gewünschten Karte um eine animierte oder statische Karte handelt (global nicht mehr genutzt)
				//und Aufruf der entsprechenden Helfer-Funktion für den Karten-Typ, die die Daten liefert
				if (in_array($value, $animatedMaps)) {
					$mapResult[$i]['image'] = $this->getAnimatedImages($value, $params['mapRange'], $params['mapRegion']);
				} elseif (in_array($value, $staticMaps)) {
					$mapResult[$i]['image'][] = $this->getStaticImage($value, $params['mapRange'], $params['mapRegion']);
				} elseif (in_array($value, $globalMaps)) {
					$mapResult[$i]['image'][] = $this->getGlobalImage($value);
				} else {
					$mapResult = false;
				}

				if(intval(ltrim($_GET['version'],'0')) < 200){
                   			if(count($mapResult[$i]['image']) > 0){     
						foreach($mapResult[$i]['image'] AS $key => $value){
							$mapResult[$i]['image'][$key]['url'] = 'fileadmin/files/upgrade_maps/app_upgrade_0200';
						}
					}else{
						$mapResult[$i]['image'][0] = array(
							'id' => time() * 10,
							'url' => 'fileadmin/files/upgrade_maps/app_upgrade_0200',
							'timestamp' => time() * 100							
						);
					}
                		}

				$i++;
			}
		}

		//Vorbereitung Rückgabe-Array
		$resultArray = array(
            'requestDate' => substr((microtime(true) * 10000), 0, - 1),
            'errorCode' => $errorCode,
            'errorMessage' => $errorMessage,
            'maps' => $mapResult
        );

		//Rückgabe
		return $resultArray;
	}

	function getGlobalImage($mapType)
	{
		$path = PATH_site . 'fileadmin/files2/weather/maps/' . $this->cleanString($mapType);
		$relativePath = 'fileadmin/files2/weather/maps/' . $this->cleanString($mapType);

		$modifyPathArray = array(
		    'SAT_EU' => 'EUROPE'
		    );

		$finalPathAbsolute = $path . '/' . $modifyPathArray[$mapType];
		$finalPathRelative = $relativePath . '/' . $modifyPathArray[$mapType];

		$dh = opendir($finalPathAbsolute);
		if ($dh) {
			// Alle Dateien im Verzeichnis auslesen und in ein Array packen
			while (false !== ($filename = readdir($dh))) {
				if (is_file($finalPathAbsolute . '/' . $filename)) {
					$fileDetails = pathinfo($filename);
					$fileDetailsArray = explode('_', $fileDetails['filename']);

					$files[$fileDetailsArray[1]] = $finalPathRelative . '/' . $fileDetailsArray[0] . '_' . $fileDetailsArray[1];
				}
			}
			$files = array_unique($files);
			krsort($files);
			$filesArray = array_slice($files, 0, 1, 1);
		} else {
			$filesArray = null;
		}

		foreach($filesArray AS $key => $value) {
			$filesArrayResult = array(
			    'id' => $key,
			    'url' => $value
			    );
		}

		return $filesArrayResult;
	}

	/**
	 * tx_agrarapp_pi1::getStaticImage()
	 *
	 * Abruf von Statischen Bildern für Wetterkarten
	 *
	 * @param mixed $mapType Art der Karte
	 * @param mixed $mapRange Zeitraum für den abgefragt wird
	 * @param mixed $mapRegion Kartenregion
	 * @return array $filesArrayResult Array mit allen gefundenen Bildern
	 */
	function getStaticImage($mapType, $mapRange, $mapRegion){

		//Definition des Pfads zu den Bildern abhängig von Bild-Typ und Region
		$path = PATH_site . 'fileadmin/files2/weather/maps/' . $this->cleanString($mapType) . '/' . $this->cleanString($mapRegion);
		//Definition des relatives Pfads zu den Bildern abhängig von Bild-Typ und Region
		$relativePath = 'fileadmin/files2/weather/maps/' . $this->cleanString($mapType) . '/' . $this->cleanString($mapRegion);
		//Öffnen des Zielordners
		$dh = opendir($path);

		//Wenn das Verzeichnis existiert, dann Auslesen der Bilder, ansonsten Rückgabe von NULL
		if ($dh) {
			// Alle Dateien im Verzeichnis auslesen und in ein Array packen
			while (false !== ($filename = readdir($dh))) {
				if (is_file($path . '/' . $filename)) {
					$fileDetails = pathinfo($filename);
					//Explode des Dateinamens in 2 Teile, um den Zeitstempel im Dateinamen als Schlüssel für das Array verwenden zu können, das alle gefundenen Bilder enthält
					$fileDetailsArray = explode('_', $fileDetails['filename']);
					$files[$fileDetailsArray[1]] = $relativePath . '/' . $fileDetailsArray[0] . '_' . $fileDetailsArray[1];
				}
			}
			//Bereinigung um evtl. doppelt vorhandene Dateien
			$files = array_unique($files);
			//Absteigende Sortierung der Dateien im Array
			krsort($files);
			//Slice des Arrays und Auswahl nur des ersten Bildes im Array
			$filesArray = array_slice($files, 0, 1, 1);
		} else {
			$filesArray = null;
		}
		//Durchlauf des Arays mit allen Dateien und Aufbau des Rückgabe-Arrays, dass ID und URL enthält
		foreach($filesArray AS $key => $value) {
			$fileTimeArray = explode('_',$value);

			$filesArrayResult = array(
			    'id' => $key,
			    'url' => $value,
			    'timestamp' => array_pop($fileTimeArray)
			    );
		}
		//Rückgabe
		return $filesArrayResult;
	}


	/**
	 * tx_agrarapp_pi1::getAnimatedImages()
	 *
	 * Abruf von animierten Karten für die App
	 *
	 * @param mixed $mapType Art der Karte
	 * @param mixed $mapRange Zeitraum der Abfrage
	 * @param mixed $mapRegion Region der Karte
	 * @return array $filesArrayResult Array mit allen Bildern
	 */
	function getAnimatedImages($mapType,$mapRange,$mapRegion){

		$maxImages = array(
			'RADAR' => 24,
			'SAT_GER' => 24,
			'SAT_EU' => 24,
			'RAIN' => 70
		);
		if(intval(ltrim($_GET['version'],'0')) >= 200){
			$path = PATH_site.'fileadmin/files2/weather/maps/'. $this->cleanString($mapType).'/'. $this->cleanString($mapRegion);
			$relativePath = 'fileadmin/files2/weather/maps/'. $this->cleanString($mapType).'/'. $this->cleanString($mapRegion);
		}else{
			$path = PATH_site.'fileadmin/files/weather/maps/'. $this->cleanString($mapType).'/'. $this->cleanString($mapRegion);
			$relativePath = 'fileadmin/files/weather/maps/'. $this->cleanString($mapType).'/'. $this->cleanString($mapRegion);
		}

		if($mapType == 'SAT_EU'){


			if(intval(ltrim($_GET['version'],'0')) >= 200){
				$path = PATH_site.'fileadmin/files2/weather/maps/'. $this->cleanString($mapType);
				$relativePath = 'fileadmin/files2/weather/maps/'. $this->cleanString($mapType);
			}else{
				$path = PATH_site.'fileadmin/files/weather/maps/'. $this->cleanString($mapType);
				$relativePath = 'fileadmin/files/weather/maps/'. $this->cleanString($mapType);
			}
			$modifyPathArray = array(
                        	'SAT_EU' => 'EUROPE'
                	);

			$path = $path .'/'. $modifyPathArray[$mapType];
			$relativePath = $relativePath .'/'. $modifyPathArray[$mapType];

		}

		$dh  = opendir($path);
		if($dh){

			//Alle Dateien im Verzeichnis auslesen und in ein Array packen
			while (false !== ($filename = readdir($dh))) {
				if(is_file($path .'/'. $filename)){
					$fileDetails = pathinfo($filename);
					$fileDetailsArray = explode('_',$fileDetails['filename']);


					if($mapType == 'RAIN' && count($fileDetailsArray) == 3){



						if($fileDetailsArray[2] != 's' && $fileDetailsArray[2] != 'm' && $fileDetailsArray[2] != 'l' ){

							$files[$fileDetailsArray[1]] = $relativePath .'/'. $fileDetailsArray[0] .'_'. $fileDetailsArray[1] .'_'. $fileDetailsArray[2];
						}else{
							$files[$fileDetailsArray[1]] = $relativePath .'/'. $fileDetailsArray[0] .'_'. $fileDetailsArray[1];
						}
					}elseif($mapType == 'RAIN' && count($fileDetailsArray) == 4){
						if ($fileDetailsArray[3] != 's' && $fileDetailsArray[3] != 'm' && $fileDetailsArray[3] != 'l') {
							$files[$fileDetailsArray[2]] = $relativePath . '/' . $fileDetailsArray[0] . '_' . $fileDetailsArray[1] . '_' . $fileDetailsArray[2];
						} else {
							$files[$fileDetailsArray[2]] = $relativePath . '/' . $fileDetailsArray[0] . '_' . $fileDetailsArray[1] .'_'. $fileDetailsArray[2];
						}
					}else{

						$files[substr($fileDetailsArray[1],0,-1)] = $relativePath .'/'. $fileDetailsArray[0] .'_'. $fileDetailsArray[1];
					}
				}
			}
			$files = array_unique($files);

			krsort($files);

			if($maxImages[$mapType]){
				$filesArray = array_slice($files, 0, $maxImages[$mapType],1);
			}

			asort($filesArray);

		}else{
			$filesArray = null;
		}

		$baseTime = FALSE;
		if($this->cleanString($mapType) == 'RAIN'){
			$timeData = json_decode(json_encode((array) simplexml_load_file ('/home/aptagricheck/files/ext/weatherR2/zeitstempel_niederschlagOT.xml')), 1);
			$multiTimer = 3600;

			if(!is_numeric($timeData['zeit'])){
				$timeData['datum'] = $timeData['datum'] .' '. $timeData['zeit'];
				$timeExplode = explode(':',$timeData['zeit']);

				$offsetValue = ltrim($timeExplode[0],'0');

			}else{
				$timeData['datum'] = $timeData['datum'] .' '. $timeData['zeit'] .':00';
				$offsetValue = $timeData['zeit'];
			}







			if(intval(ltrim($_GET['version'],'0')) >= 200) {

				$fileOffset = $offsetValue - 3;
				if($fileOffset < 0){
					$fileOffset = 0;
				}

				$filesArray = array_slice($filesArray,$fileOffset);


				foreach($filesArray AS $key => $value){
					$fileDetailsArrayTemp = explode('_',$value);

					$filesArrayTemp[$fileDetailsArrayTemp[1]] = $value;
				}

				$filesArray = $filesArrayTemp;


			}



		}elseif($this->cleanString($mapType) == 'SAT_EU' || $this->cleanString($mapType) == 'SAT_GER'){
			$timeData = json_decode(json_encode((array) simplexml_load_file ('fileadmin/files2/import_weather/zeitstempel_Europa-600-OT.xml')), 1);

			$timeValues = array_keys($filesArray);

			$baseTime = substr($timeValues[0],0,10) - (substr($timeValues[0],0,10) % 3600) - 21600;


			$multiTimer = 10800;
		}elseif($this->cleanString($mapType) == 'RADAR'){
			$timeData = json_decode(json_encode((array) simplexml_load_file ('/home/aptagricheck/files/ext/weatherR2/zeitstempel_radarOT.xml')), 1);
			$timeDataArray = explode(' ',$timeData['datum']);
			$baseTime  = strtotime($timeDataArray[1] .' '. $timeDataArray[2]) - 20700;

			$multiTimer = 900;
		}

		if(!$baseTime) {
			if($this->cleanString($mapType) == 'RAIN'){
				$baseTime = strtotime($timeData['datum']);

				if($baseTime % 3600 != 0){
					$baseTime = $baseTime - ($baseTime % 3600) ;
				}
				if(date('I', $baseTime) == 1){
					$baseTime += 3600;
				}

			}else{
				
				$baseTime = strtotime($timeData['datum']) - 3600;

				if(date('I', $baseTime) == 1){
                                        $baseTime += 3600;
                                }else{
					$baseTime += 10800;
				}
			}
		}




		foreach($filesArray AS $key => $value){

			if($this->cleanString($mapType) == 'RAIN' || $this->cleanString($mapType) == 'RADAR' || $this->cleanString($mapType) == 'SAT_GER' || $this->cleanString($mapType) == 'SAT_EU'){
				$timeStampValue = ($baseTime + ($i++ * $multiTimer)) * 1000;
			}else{
				$timeStampValue =  substr($key,0,10) * 1000;
			}


			$filesArrayResult[] = array(
				'id' => $key,
				'url' => $value,
				'timestamp' => $timeStampValue
			);
		}



		return $filesArrayResult;

	}
	
	function getMarketAlertSubscriptions($deviceId){
		//$GLOBALS['TYPO3_DB']->store_lastBuiltQuery = true;		

	
		$query = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			'limitid,limittype,limitborder,limitvalue,limittime',
			'tx_agrarapp_subscriptions',
			'subtype = 3 AND deviceid = \'' . $deviceId . '\''
		);

		//echo $GLOBALS['TYPO3_DB']->debug_lastBuiltQuery;	
		
		if($query){
			while($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($query)){
				//print_r($row);
								
				$resultArray[$row['limitid']][$row['limittype']][$row['limitborder']] = $row['limitvalue'];
				$resultArray[$row['limitid']][$row['limittype']]['limittime'][$row['limitborder']] = $row['limittime'];


			}
		}else{
			$resultArray = FALSE;
		}
			
		if($_GET['debug'] == 1){
			
		}
		
		return $resultArray;
		
			
		
	}
	
	function checkMarketAlertLimitViolation($goodId,$price,$alertArray,$futuresCheckGoodCourse = 0){
		
		
		if($futuresCheckGoodCourse == 0){
			if(floatval($alertArray[$goodId][0][0]) > 0 && floatval($alertArray[$goodId][0][0]) > $price){
				$alertResponse = 'BELOW';
			}elseif(floatval($alertArray[$goodId][0][1]) > 0 && floatval($alertArray[$goodId][0][1]) < $price){
				$alertResponse = 'BEYOND';
		
			}else{
				$alertResponse = NULL;
	
			}
		}else{
		

			if(floatval($alertArray[$goodId][1][0]) > 0 && floatval($alertArray[$goodId][1][0]) > $price){
                                $alertResponse = 'BELOW';
                        }elseif(floatval($alertArray[$goodId][1][1]) > 0 && floatval($alertArray[$goodId][1][1]) < $price){
                                $alertResponse = 'BEYOND';

                        }else{
                                $alertResponse = NULL;
                        }
		}
		return $alertResponse;		
		
	}		

	
	
	function checkMarketAlertFutureLimitViolation($goodId,$originalId,$alertArray){
		
		$originalId = substr($originalId, 0, strpos($originalId, '.', strpos($originalId, '.')+1));
		
		$alertResponse = NULL;
		
		if($originalId != ''){
			//Zeitstempel des ersten Tags im aktuellen Monat, um alte Futures zu ignorieren
			$dateTime = strtotime(date('Y-m-01'));
			
			//$GLOBALS['TYPO3_DB']->store_lastBuiltQuery = true;
		

			$query = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
				'min(price) AS minprice,max(price) as maxprice',
				'tx_agrarapp_futures',
				'datetime > '. $dateTime  .' AND price > 0 AND originalid_short = \'' . $originalId . '\''
			);
		
			//echo $GLOBALS['TYPO3_DB']->debug_lastBuiltQuery;	
			
	
			if($query){
				$row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($query);
				
							
				if($row['minprice'] > 0 && floatval($alertArray[$goodId][0][0]) > 0 && floatval($alertArray[$goodId][0][0]) > $row['minprice']){
					$limitExceed++;
					$alertResponse = 'BELOW';
				}
				if($row['minprice'] > 0 && floatval($alertArray[$goodId][0][1]) > 0 && floatval($alertArray[$goodId][0][1])  < $row['maxprice']){
					$limitExceed++;
					$alertResponse = 'BEYOND';
				}
			
			}
		//	echo $limitExceed;
			if($limitExceed > 1){
				$alertResponse = 'BOTH';
			}				
		}
		
		
		return $alertResponse;
		
		
	}


	/**
	 * tx_agrarapp_pi1::cleanZipCode()
	 *
	 * Bereinigung von PLZs, die über den Webservice kommen
	 * PLZs werden auf 5 Stellen gekürzt, um führende Nullen bereinigt und als Integer-Werte zurückgegeben
	 *
	 * @param mixed $zipCode PLZ aus dem Webservice
	 * @return integer Postleitzahl
	 */
	function cleanZipCode($zipCode){
		$zipCode = preg_replace('[\D]', '', trim($zipCode));
		$zipCode = substr($zipCode, 0, 5);
		$zipCode = ltrim($zipCode, '0');
		$zipCode = intval($zipCode);

		return $zipCode;
	}



	/**
	 * tx_agrarapp_pi1::cleanString()
	 *
	 * Bereinigung von Strings, die über den Webservice kommen
	 * Alle Strings werden von Tags, etc. entfernt und Leerzeichen am Anfang oder Ende entfernt. Außerdem werden die Strings escaped soweit erforderlich
	 *
	 * @param mixed $string
	 * @return
	 */
	function cleanString($string){
		$value = trim(htmlentities(strip_tags(preg_replace('/\s/u', '', $string))));

		if (get_magic_quotes_gpc()) {
			$value = stripslashes($value);
		}
		$value = mysql_real_escape_string($value);
		return $value;
	}

	function cropNumber($number,$separator='.',$decimals=2){

		$number = number_format($number,5,$separator,'');

		$numberShort = substr($number, 0, ( (strpos($number, $separator)+1)+$decimals ));

		return $numberShort;
	}

}

?>
