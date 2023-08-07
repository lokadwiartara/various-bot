<?php

// chromedriver --port=4444 --url-base=/wd/hub
require_once "vendor/autoload.php";

use duzun\hQuery;
use Facebook\WebDriver\Chrome\ChromeDriver;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverKeyboard;
use Facebook\WebDriver\WebDriverKeys;
use Facebook\WebDriver\WebDriverExpectedCondition;
use Facebook\WebDriver\Chrome\ChromeOptions;

function openBrowserSaveSession(){
	$host = 'http://localhost:4444/wd/hub'; 
	$options = new ChromeOptions();
	$options->addArguments(array('--start-maximized', '--disable-infobars', '--disable-gpu', '--no-sandbox' )); 

	$options->setExperimentalOption("excludeSwitches", ['enable-automation', 'enable-logging']);
    $prefs['profile']['default_content_setting_values']['notifications'] = 2;
    //$prefs['profile']['default_content_setting_values']['geolocation'] = 1;
    $prefs['credentials_enable_service'] = false;        
    $prefs['profile']['password_manager_enabled'] = false;
    //$prefs['profile']['default_content_setting_values']['images'] = 1;

    $options->setExperimentalOption('prefs', $prefs);    

	$caps = DesiredCapabilities::chrome();
	$caps->setCapability(ChromeOptions::CAPABILITY, $options);

	$driver = RemoteWebDriver::create($host, $caps);	
	file_put_contents('session.json',
	    json_encode([
	        'sessionID' => $driver->getSessionID()
	    ])
	);	

	return $driver;
}

$sessionSaved = json_decode(file_get_contents('session.json'),TRUE);

if(isset($sessionSaved['sessionID'])){
	$driver = RemoteWebDriver::createBySessionID($sessionSaved['sessionID']);
}
else{
	$driver = openBrowserSaveSession();
}

try {
    $driver->get('https://web.whatsapp.com/');
    sleep(rand(2,4));
} catch (Throwable $e) {
	$driver = openBrowserSaveSession();
	$driver->get('https://web.whatsapp.com/');
}

echo "\nJika sudah merekam barcodenya, ketik 'ya' (tanpa tanda kutip): ";

$handle = fopen ("php://stdin","r");
$line = fgets($handle);
$task = 0;

if(trim($line) != 'ya'){
    echo "\nAnda memilih 0, keluar...!\n";
    exit;
}	

$driver->get('https://web.whatsapp.com/');

/* mulai mengambil dari database */
$templatePesan = 'Assalamualaikum, selamat pagi Bapak/Ibu {{nama}}, mohon maaf mengganggu waktunya. Apakah kami diperkenankan untuk mengirimkan info yang barangkali bermanfaat untuk Bapak/Ibu? Kami mendapatkan Nomor Bapak/Ibu karena sebelumnya Bapak/Ibu telah tergabung dalam Iklan Baris penjualan Mobil.';

$db = new MysqliDb ('localhost', 'root', '', 'db_olx');

$cols = Array ("contact_id", "contact_name", "contact_number");
$contacts = $db->get ("tbl_contact", null, $cols);
if($db->count > 0){
	echo "========================================\n";

	$x = 1;
	foreach($contacts as $contact) {
		echo $x.". Follow UP User ".$contact['contact_name'].'... '."\n\n";
		$pesan = str_replace('{{nama}}', $contact['contact_name'], $templatePesan);
		// echo '"'.$pesan.'"'; 

		$driver->get('https://api.whatsapp.com/send?phone='.$contact['contact_number'].'&text='.$pesan);
		
		$pageSource = hQuery::fromHTML($driver->getPageSource());
		$sendButtonCheck = $pageSource->find('#action-button');
		if($sendButtonCheck != NULL && count($sendButtonCheck) > 0){
			$sendButton = $driver->findElement(WebDriverBy::cssSelector('#action-button'));
			$sendButton->click();
		}

		sleep(rand(3,5));

		$pageSource = hQuery::fromHTML($driver->getPageSource());
		$useWebCheck = $pageSource->find('#fallback_block > div > div > a');
		if($useWebCheck != NULL && count($useWebCheck) > 0){
			$useWeb = $driver->findElement(WebDriverBy::cssSelector('#fallback_block > div > div > a'));
			$useWeb->click();
		}

		sleep(rand(7,15));

		$pageSource = hQuery::fromHTML($driver->getPageSource());
		$sendNowCheck = $pageSource->find('#main > footer > div._3SvgF._1mHgA.copyable-area > div:nth-child(3) > button');
		if($sendNowCheck != NULL && count($sendNowCheck) > 0){
			$sendNow = $driver->findElement(WebDriverBy::cssSelector('#main > footer > div._3SvgF._1mHgA.copyable-area > div:nth-child(3) > button'));
			// $sendNow->click();
		}

		echo "Selesai mengirim pesan whatsapp ...\n";

		echo "\n\n";
		sleep(2);
		$x++;
    }
}