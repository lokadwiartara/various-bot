<?php

/*

	baiklah kita akan masuk ke latihan selanjutnya, pada latihan sebelumnya telah dijelaskan bagaimana cara menghandle, menangani ketika tidak sengaja Anda meng-close atau menutup jendela si chromedrivernya, dengan menggunakan error handling try and catch, pada latihan selanjutnya, kita akan coba lanjutkan sebagaimana yang telah dijelaskan di latihan bot 1, bahwa kita akan melakukan pengecekan suatu kondisi apakah user telah melakukan login atau belum dengan mengecek elemen html tertentu,

	sehingga dari situ bisa membedakan perintah apa selanjutnya dilakukan ketika sudah login
	maupun belum login ... 
	
	
	untuk bisa melakukan pengecekan login atau belum pada latihan kali kita akan lakukan login terlebih dahulu, menggunakan latihanbot1-bab9
	
	seperti ini terlebih dahulu, apa bila sudah login  
	selanjutnya kita manfaatkan file latihanbot2-bab9 untuk nantinya diterapkan ke dalam latihan kali ini ... 	
*/

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

$sessionSaved = json_decode(file_get_contents('session.json'),TRUE);

function openBrowserSaveSession(){
	// Use the remote addr to locate where javaw is running
	$host = 'http://localhost:4444/wd/hub'; // this is the default
	 
	$options = new ChromeOptions();
	$options->setExperimentalOption("excludeSwitches", ['enable-automation', 'enable-logging']);
	$options->addArguments(array('--start-maximized', "--disable-infobars", "--disable-extensions"));
	$prefs['profile']['default_content_setting_values']['notifications'] = 2;
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

if(isset($sessionSaved['sessionID'])){
	$driver = RemoteWebDriver::createBySessionID($sessionSaved['sessionID']);
}
else{
	$driver = openBrowserSaveSession();
}

try {
    $driver->get('https://facebook.com');
    sleep(rand(2,4));
} catch (Throwable $e) {
	$driver = openBrowserSaveSession();
}

$driver->get('https://facebook.com');

/*
	kita akan gunakan duzun hquery untuk mendeteksi ada atau tidaknya tombol "indikator login profil"
*/

$pageSource = hQuery::fromHTML($driver->getPageSource());
$loginProfile = $pageSource->find('#mount_0_0 > div > div:nth-child(1) > div.rq0escxv.l9j0dhe7.du4w35lb > div:nth-child(4) > div.ehxjyohh.kr520xx4.poy2od1o.b3onmgus.hv4rvrfc.n7fi1qx3 > div.du4w35lb.l9j0dhe7.byvelhso.rl25f0pe.j83agx80.bp9cbjyn > div.bp9cbjyn.j83agx80.datstx6m.taijpn5t.oi9244e8.d74ut37n > a');

if($loginProfile != NULL && count($loginProfile) > 0){
	echo 'Sudah login...';
}
else{
	echo "Belum login...\n";
	echo "Proses login...";

	$email = $driver->findElement(WebDriverBy::cssSelector('#email'));
	if($email){
		$email->sendKeys('');
	}

	$password = $driver->findElement(WebDriverBy::cssSelector('#pass'));
	if($password){
		$password->sendKeys('');
	}

	$login = $driver->findElement(WebDriverBy::cssSelector('#u_0_b'));
	if($login){
		$login->click();
	}

	sleep(5);
	echo "SELESAI\n";
}

