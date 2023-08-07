<?php

/*
	pada latihan kali ini kita masuk ke dalam fitur bot lainnya yang lebih mendalam lagi
	yakni proses pengecekan login atau tidaknya si bot menggunakan chrome driver
	adalah dengan melakukan parsing beberapa bagian yang dirasa mewakili bahwa bot sudah login,
	bingung ya ? langsung saja kita praktikan ... 
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

// Use the remote addr to locate where javaw is running
$host = 'http://localhost:4444/wd/hub'; // this is the default
 
$prefs['profile']['default_content_setting_values']['notifications'] = 2;

$sessionSaved = json_decode(file_get_contents('session.json'),TRUE);

if(isset($sessionSaved['sessionID'])){
	$driver = RemoteWebDriver::createBySessionID($sessionSaved['sessionID']);
}
else{
	$options = new ChromeOptions();
	$options->addArguments(array('--start-maximized', "--disable-infobars", "--disable-extensions"));
	$options->setExperimentalOption('prefs', $prefs);

	$caps = DesiredCapabilities::chrome();
	$caps->setCapability(ChromeOptions::CAPABILITY, $options);

	$driver = RemoteWebDriver::create($host, $caps);	
	file_put_contents('session.json',
	    json_encode([
	        'sessionID' => $driver->getSessionID()
	    ])
	);
}

try {
    $driver->get('https://facebook.com');
    sleep(rand(2,4));
} catch (Throwable $e) {
	$options = new ChromeOptions();
	$options->addArguments(array('--start-maximized', "--disable-infobars", "--disable-extensions"));
	$options->setExperimentalOption('prefs', $prefs);

	$caps = DesiredCapabilities::chrome();
	$caps->setCapability(ChromeOptions::CAPABILITY, $options);

	$driver = RemoteWebDriver::create($host, $caps);	
	file_put_contents('session.json',
	    json_encode([
	        'sessionID' => $driver->getSessionID()
	    ])
	);
}

$driver->get('https://facebook.com/loka.dwiartara');

/*
	apabila kita jalankan pada command line hasilnya adalah banyak warning dan errornya
	ini dikarenakan kita masih menggunakan session lama, yang mana browsernya sudah di tutup,
	nah bagaimana menangani error seperti ini ? 

	kita akan menggunakan try and catch yang mana ini merupakan operand baru yang di sediakan oleh php 
*/

