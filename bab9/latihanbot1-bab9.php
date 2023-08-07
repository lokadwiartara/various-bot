<?php

/*
	seperti yang dijelaskan di awal bab 9, bahwa agar bisa memanfaatkan browser yang ada kita harus memanfaatkan session browsernya di simpan ke dalam sebuah file, sehingga perintah di command line juga harus di sesuaikan menggunakan wd/hub agar bisa di akses folder session nantinya, 

	masuk ke command line lalu jalankan 

	chromedriver --port=4444 --url-base=/wd/hub

	baiklah setelah itu kita langsung ke membuat folder baru khusus untuk latihan bab 9
	kemudian kita buat file latihan pertama untuk bab 9, yakni seperti biasa adalah
	latihanbot1-bab9.php

	setelah itu langkah yang kita lakukan selanjutnya adalah meload file composernya 
	setelah itu kita load semua yang dibutuhkan untuk melakukan pembuatan bot diantaranya ... 

	mungkin Anda bertanya-tanya mengapa kita harus meload class dalam beberapa baris, singkatnya baris-baris yang di load ini itu cukup untuk membuat bot yang cukup komplit dan kompleks, karena function-function atau method yang dibutuhkan itu ada dalam barisan yang di load ini ... 
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

/* 
	kemudian sebagai perbedaan karena kita akan menggunakan session 
	maka untuk host kita sesuaikan dengan yang ada dalam command line 	
*/

// Use the remote addr to locate where javaw is running
$host = 'http://localhost:4444/wd/hub'; // this is the default
 
/* 
	setelah itu seperti biasa setelah kita meload semuanya, 
	kita juga bisa menghindari adanya pertanyaan notifikasi dari web-web yang biasanya
	setiap kali di buka pertama kali selalu bertanya dalam bentuk popup notification,
	nah itu kita bisa cegah dengan barisan code seperti ini 
*/

$prefs['profile']['default_content_setting_values']['notifications'] = 2;

/*
	Anda yang mungkin masih bertanya bagaimana bisa dalam bentuk array seperti itu, semuanya itu sudah dijelaskan dalam wiki dokumentasinya yang bisa Anda akses lewat url berikut ini ... 
	// See all the capabilities here: https://github.com/SeleniumHQ/selenium/wiki/DesiredCapabilities
*/

/*
	baik yang selanjutnya kita lakukan adalah kita buat dulu koneksi awalnya 
*/

$options = new ChromeOptions();
$options->addArguments(array('--start-maximized', "--disable-infobars", "--disable-extensions"));
$options->setExperimentalOption('prefs', $prefs);

$caps = DesiredCapabilities::chrome();
$caps->setCapability(ChromeOptions::CAPABILITY, $options);

$driver = RemoteWebDriver::create($host, $caps);	

/*
	kemudian kita simpan browser sessionnya ke dalam suatu file json 
	yang mana nantinya apabila kita ulang jalankan scriptnya tidak akan balik lagi untuk membuka browser baru, cukup gunakan session yang sama atau dengan kata lain browser yang sama ketika di running scriptnya pertama kali ... 
*/
file_put_contents('session.json',
    json_encode([
        'sessionID' => $driver->getSessionID()
    ])
);

$driver->get('https://facebook.com');

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

/*


	kemudian kita jalankan scriptnya pada command line 
	setelah itu kita lihat akan ada sebuah file baru yang ter-Create 
	dampak dari barisan kode file_put_contens  ... 
	apabila kita lihat isinya adalah array session dari browsernya yang mana 
	ini bisa kita gunakan ulang, caranya seperti apa ... 


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

$driver->get('https://facebook.com/ilmuwebsite');


	bagaimana ? 
	cukup mudah bukan ? 
*/