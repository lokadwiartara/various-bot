<?php

/*
	pada latihan kali ini kita akan mencoba kembali fitur-fitur dasar yang ada dalam web driver
	seperti biasa yang akan kita lakukan pertama kali adalah menyisipkan autoload hasil dari composernya 
	yang berbeda kali ini kita akan bermain main di chrome option sehingga nantinya kita bisa membuat browser itu bisa mengakses halaman website layaknya di browser pada mobile atau android 
*/

require_once "vendor/autoload.php";

use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverDimension; #ditambahkan
use Facebook\WebDriver\WebDriverWindow; #ditambahkan
use Facebook\WebDriver\WebDriverKeyboard; 
use Facebook\WebDriver\WebDriverKeys;
use Facebook\WebDriver\WebDriverNavigation; #ditambahkan
use Facebook\WebDriver\WebDriverMouse; #ditambahkan
use Facebook\WebDriver\WebDriverExpectedCondition;
use Facebook\WebDriver\Chrome\ChromeOptions; #ditambahkan

$host = 'http://localhost:4444';

/*
	kita akan mensetting browser dari webdriver chrome
	yang pertama kita akan lakukan adalah menyiapkan variable optionnya
*/

$options = new ChromeOptions();

/*
	kemudian kita akan maksimalkan chrome webdrivernya ketika running
	di sini saya juga akan menambahkan devtools nya 
*/

$options->addArguments(array('--start-maximized','--auto-open-devtools-for-tabs'));
// https://peter.sh/experiments/chromium-command-line-switches/

/*
	setelah itu kita tambahkan option mobileEmulation sehingga browser 
	bekerja layaknya browser pada mobile atau smartphone android dan lain lain ...  
*/

$options->setExperimentalOption("mobileEmulation", ["deviceName" => "Galaxy S5"]);

/*
	langkah selanjutnya kita akan terapkan option option tersebut ke dalam
	pembuatan variable drivernya, lalu kita langsung tes, apakah bekerja sebagaimana mestinya ? 
*/

$caps = DesiredCapabilities::chrome();
$caps->setCapability(ChromeOptions::CAPABILITY, $options);
$driver = RemoteWebDriver::create($host, $caps);

$driver->get("https://instagram.com/ilmuwebsite");

