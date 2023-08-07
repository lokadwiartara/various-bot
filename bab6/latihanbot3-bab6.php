<?php

/*
	pada latihan kali ini kita akan mencoba kembali fitur-fitur dasar yang ada dalam web driver
	seperti biasa yang akan kita lakukan pertama kali adalah menyisipkan autoload hasil dari composernya 
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

// getCurrentURL()
// getPageSource()
// quit()
// takeScreenshot()

/* 
	kita pastikan terlebih dahulu apakah selenium servernya berjalan
*/

// Use the remote addr to locate where javaw is running
$host = 'http://localhost:4444'; // this is the default
 
/* 
	kemudian kita langsung buat saja sebuah variable driver yang nantinya 
	digunakan dalam menghandle browser 
*/

$driver = RemoteWebDriver::create($host, DesiredCapabilities::chrome());

/*
	kita akan mencoba untuk membuka halaman website google
	dan kemudian kita lakukan screenshoot halaman tersebut dan menyimpannya ke dalam sebuah file png
*/

$driver->get("https://www.google.com");

$driver->takeScreenshot("d:/ss.png");

// 	$driver->getPageSource();

$driver->quit();
