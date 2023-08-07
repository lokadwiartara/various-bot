<?php

/* 
	seperti biasa yang pertama dilakukan seperti biasa adalah melakukan load terhadap autoload.php nya
	di sisipkan ke dalam file latihan php kali ini 
*/

require_once "vendor/autoload.php";

/*
	untuk bisa menggunakan fitur-fitur yang ada pada browser, 
	kita akan menggunakan fasilitas default dari php webdriver. 

	diantaranya adalah : 
*/

use duzun\hQuery;
use Facebook\WebDriver\Chrome\ChromeDriver;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriverBy;

/*
	untuk melihat apa saja yang bisa Anda gunakan, sehingga nantinya bisa disisipkan
	ke barisan tersebut, silahkan Anda melihat link di bawah ini ...

	perlu diketahui bahwa ini sudah menjadi dasar yang harus di gunakan ketika 
	pertama kali akan membuat suatu project... menyisipkan fitur-fitur yang nantinya
	akan digunakan, 

	yang selanjutnya dilakukan adalah mempersiapkan dulu untuk variable server dari
	selenium servernya. adanya di localhost port 4444

	kita koding di php, dan bot-bot itu bisa mengendalikan chrome browser itu
	berkat jembatan yang disediakan oleh seleniumnya ... 
*/
$host = 'http://localhost:4444'; // this is the default
 
/*
	setelah kita mendefinisikannya kita bisa langsung menjalankan 
	web driver, sehingga nantinya browser-browser itu bisa di kendalikan 
	oleh php bot nantinya 

	kemudian langkah selanjutnya adalah kita buat barisan untuk mengendalikan browser 
	chrome, yang artinya kurang lebih kita akan buat pengendali browser chrome
	melalu fasilitas selenium yang sedang berjalan, dan menggunakan library remotewebdriver
*/

// See all the capabilities here: https://github.com/SeleniumHQ/selenium/wiki/DesiredCapabilities
$driver = RemoteWebDriver::create($host, DesiredCapabilities::chrome());

/*
	langkah selanjutnya adalah kita tentunkan website apa yang akan di lakukan otomatisasi
	untuk contoh praktik kali ini kita akan menggunakan google.com sebagai website
	untuk kelinci percobaan, kita akan mencari suatu keyword secara otomatis dengan php webdriver, 
	lalu di ambil isinya oleh duzun hquery 
*/

// 

/* 
	kita akan mulai dengan mencoba membuka halaman google dan melakukan suatu pencarian 
	tentunya menggunakan web driver ya 
*/

$driver->get("https://www.google.com");

/*
	sampai sini kita coba terlebih dahulu ... 
	
	##### 

	oke berhasil ya kita membuka google lewat webdrivernya
	
	kita matikan lagi si chrome drivernya, kita akan lakukan koding ulang ...

	dimana nantinya sibot ini akan kita program sehingga bisa mengisikan 
	form inputan dalam pencariannya, caranya kita dapatkan terlebih dahulu 
	selector css, caranya sangat mudah sekali kita tinggal klik kanan inspect, 
	kemudian kita copas saja hasil inspect nya 

	lalu kita terapkan ke dalam webdrivernya, caranya adalah seperti ini ... 
*/

$input = $driver->findElement( WebDriverBy::cssSelector('#tsf > div:nth-child(2) > div.A8SBwf > div.RNNXgb > div > div.a4bIc > input') );

/*
	kita cek terlebih dahulu apakah si form inputan pencariannya itu eksis,
	apabila eksis kita langsung kirimkan saja text yang akan kita masukkan 
	ke dalam inputan pencarian keywordnya ... caranya seperti ini 
*/

if($input){
	$input->sendKeys('gunung salak endah'."\n");
	sleep(5);
}

/* 
	langsung kita jalankan, .... oke ya ternyata berjalan dengan baik, 
	kita matikan lagi chrome drivernya

	bagaimana ? mudah bukan ? kita akan uji coba kembali, menambahkan salah satu fitur dari web
	driver yang bisa di berlakukan ke pada browser chrome automationnya, yakni mengquit browser
	secara otomatis, begini caranya ... 
*/

// $driver->quit();

/*
	bagaimana ? mengasyikan bukan ? bermain-main dengan php webdriver
	kita akan lanjut lagi ke pemrogaman bot yang lebih kompleks lagi ... 
	hasil dari pencarian google kita akan lakukan parsing, sehingga nanti 
	yang ditampilkan hanya judul dan linknya saja ... 

	kita matikan dulu driver quitnya ... 
*/

// echo $driver->getPageSource();

/*
	kemudian kita masukkan ke dalam sebuah variable untuk nantinya bisa
	kita lakukan parsing 
*/

$htmlSearchResult = $driver->getPageSource();

/*
	dan jangan lupa karena kita belum memasukkan library duzun HQuery
	kita require terlebih dahulu di composernya, yakni duzun/hquery nya
	caranya gampang, di bab 4 kita sudah praktikan berkali kali
*/

$htmlserp = hQuery::fromHTML($htmlSearchResult);

$getListLink = $htmlserp->find('#rso > div:nth-child(1) > div > div.yuRUbf > a');

if(count($getListLink) > 0){
	$x = 1;
	echo "\n";
	foreach($getListLink as $data => $link){

		$linkHTML = hQuery::fromHTML($link);
		$linkText = $linkHTML->find('h3');

		echo $x.". ".$linkText[0]->text()."\n".$link->attr('href')."\n\n";
		$x++;
	}
}

$driver->quit();

/*
	ya kurang lebih seperti itu, jika masih pusing wajar karena kita baru dipengenalan dulu ... 
	selanjutnya kita akan pelajari lebih mendalam ... 
*/