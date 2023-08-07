<?php 

/* 
	pada latihan sebelumnya kita telah mencoba-coba beberapa fitur yang disediakan oleh php webdriver 
	kali ini kita akan coba fitur lain, 
	
	masih berkaitan dengan pemanfaatan browser, yang akan kita coba diantaranya 
	
	fitur untuk me maximize browser, kemudian fitur untuk mendapatkan url yang saat ini sedang di akses 
	mendapatkan title dari halaman yang saat ini sedang di akses, mendapatkan ukuran windows dari browsernya, menavigasi browser yakni back atau kembali ke halaman yang sebelumnya 

	seperti biasa yang harus dilakukan adalah ...
*/

require_once "vendor/autoload.php";

/*
	ada beberapa class yang akan kita tambahkan, yang nantinya akan digunakan dalam praktik kali ini 
*/

use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverDimension; #ditambahkan
use Facebook\WebDriver\WebDriverWindow; #ditambahkan
use Facebook\WebDriver\WebDriverKeyboard; 
use Facebook\WebDriver\WebDriverKeys;
use Facebook\WebDriver\WebDriverNavigation; #ditambahkan
use Facebook\WebDriver\WebDriverExpectedCondition;

/* 
	kita pastikan terlebih dahulu apakah selenium servernya berjalan
*/

// Use the remote addr to locate where javaw is running
$host = 'http://localhost:4444'; // this is the default
 
/* 
	kemudian kita langsung buat saja sebuah variable driver yang nantinya 
	digunakan dalam menghandle browser 
*/

// See all the capabilities here: https://github.com/SeleniumHQ/selenium/wiki/DesiredCapabilities
$driver = RemoteWebDriver::create($host, DesiredCapabilities::chrome());


/*
	kita coba kembali untuk membuka halaman pencarian google 
	lalu kita tambahkan seperti ini 
*/

$driver->manage()->window()->maximize();
$driver->get("https://www.google.com");
echo "Anda sekarang berada di halaman depan " . $driver->getTitle()."\n";
echo $driver->getCurrentURL()."\n\n";
sleep(5);

/*
	nah bagaimana ? sudah mengerti bukan ? 
	barisan yang ini digunakan untuk membuka halaman google dan menampilkan status apa title dari halaman yang saat ini sedang dibuka 

	dan juga Anda bisa mengeluarkan alamat dari url apa yang saat ini sedang diakses 
	
	kemudian Anda juga bisa berpindah halaman ke website lainnya, layaknya ketika Anda browsing menggunakan browser chrome saja ya 
*/

$driver->get("https://www.youtube.com");
echo "Sekarang Anda berada di halaman depan " . $driver->getTitle()."\n";
echo $driver->getCurrentURL()."\n\n";
sleep(10);


/* 
	tidak hanya berpindah ke website lain 
	Anda pun bisa melakukan back navigation atau kembali ke halaman sebelumnya   
	caranya adalah seperti ini ... 
*/

$driver->navigate()->back();
echo "Setelah di back, Anda sekarang berada di halaman depan " . $driver->getTitle()."lagi ... \n";
echo $driver->getCurrentURL()."\n\n";

/* 
	selain itu Anda pun bisa mendapatkan ukuran dari windows browser yang sedang berjalan saat ini 
*/

$getSize =  $driver->manage()->window()->getSize();
echo "Ukuran windows saat ini adalah ".$getSize->getWidth().'x'.$getSize->getHeight()."\n";
sleep(5);

/* 
	Anda juga bisa melakukan maximize pada windowsnya, caranya adalah seperti ini ... 
*/

$driver->manage()->window()->maximize();
$getSize =  $driver->manage()->window()->getSize();

/* 
	Dan Anda pun bisa mengakses seberapa lebar dan tinggi dari browsernya
*/

echo "Ukuran windows setelah di maximize adalah ".$getSize->getWidth().'x'.$getSize->getHeight()."\n";
sleep(5);

/*
	untuk quit atau menutup browser Anda bisa menggunakan perintah quit seperti di ini ... 
*/

$driver->quit();