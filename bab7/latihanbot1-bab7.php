<?php

/* 
	yang dilakukan pertama kali adalah seperti biasa, kita includekan autoload dari hasil require composernya
*/

require_once "vendor/autoload.php";

/* 
	setelah itu kita bisa langsung list apa saja yang akan kita gunakan pada praktik latihan pembelajaran kali ini ... 	
*/

use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverKeyboard;
use Facebook\WebDriver\WebDriverKeys;
use Facebook\WebDriver\WebDriverExpectedCondition;

/*
	kemudian pastikan untuk mengecek apakah chromedriver berjalan di port 4444
	setelah dipastikan jalan maka kita bisa menyediakan sebuah variable yang menyimpan informasi bahwa chromedriver berjalan di port tersebut
*/

// Use the remote addr to locate where javaw is running
$host = 'http://localhost:4444'; // this is the default
 
/*
	setelah itu kita bisa memanfaatkan variable tersebut untuk nantinya bisa kita gunakan untuk langsung mengotomatisasi browser yang dihasilkan oleh chromedriver ....  
*/

// See all the capabilities here: https://github.com/SeleniumHQ/selenium/wiki/DesiredCapabilities
$driver = RemoteWebDriver::create($host, DesiredCapabilities::chrome());


/* kita arahkan langsung ke dalam halaman form register akun di facebook */

$driver->get("https://www.facebook.com/r.php");

/* oke sudah terbuka ya */
/* 
	kemudian kita akan mencoba menghandle elemen web yang ada di form ini
	kita bisa menggunakan bantuan developer console yang sudah disediakan oleh google
	chrome yang ditampilkan saat ini, Anda bisa memanfaatkannya untuk mendapatkan id,name,class hingga css selector dan xpath yang mana nantinya itu semua digunakan sebagai jembatan Anda bisa 

	mengotomatisasi pengisiian form input,  maupun mengklik button yang ada dalam formulir ini ... 
*/


$firstName = $driver->findElement(WebDriverBy::name('firstname'));

if($firstName){
	// https://facebook.github.io/php-webdriver/1.4.0/Facebook/WebDriver/WebDriverKeys.html
	$firstName->sendKeys('Loka'); //.WebDriverKeys::ENTER
}

$lastName = $driver->findElement(WebDriverBy::name('lastname'));

if($lastName){
	$lastName->sendKeys('Dwiartara');
}

/* 
	mantap ya, berkat bantuan php webdriver dan chromewebdriver, proses pengisian form secara otomatis dilakukan oleh script yang Anda tulis ini ... bagaimana ? Kita lanjut.... 
	
	pengisian form berjenis teks sudah, kemudian kita akan lanjutkan ke pengisian username password dan memilih tanggal lahir, tentunya dengan menggunakan script juga ya ... 

	setelah beres kita bisa langsung mengklik button ok untuk membuat akun baru di facebook ... bagaimana mantap bukan ? Kita langsung praktik saja ... 

	selain menggunakan name, Anda juga bisa menggunakan ID seperti ini 
*/

$email = $driver->findElement(WebDriverBy::id('u_0_s'));
if($email){
/*
	apabila kita lihat terdapat konfirimasi email yang muncul setelah email dimasukkan
	kita bisa menghandlenya dengan cara memberikan jeda sekitar 1 detik untuk melanjutkan pengisian email konfirmasi
*/
	$email->sendKeys('');

	/* bagian ini sampai selesai kurung kurawal silahkan diperhatikan */
	sleep(1);
	$emailConfirm = $driver->findElement(WebDriverBy::id('u_0_v'));
	if($emailConfirm){
		$emailConfirm->sendKeys('ilmuwebsiteid@gmail.com');
	}
}	

$password = $driver->findElement(WebDriverBy::id('password_step_input'));
if($password){
	$password->sendKeys('');
}

/* goto 78 */

/* oke jika sudah selesai kita akan memilih select option yang ada di sini mulai dari tanggal bulan dan tahun, caranya cukup mudah kita akan menggunakan function click pada stiap elementnya */

$day = $driver->findElement(WebDriverBy::id('day'));
if($day){
	$day->click();

	/* setelah dari sini kita akan mengklik misalkan tanggal 24, kita cukup gunakan css selector saja */
	$twentyFour = $driver->findElement(WebDriverBy::cssSelector('#day > option:nth-child(24)'));
	if($twentyFour) $twentyFour->click();
}


/* 
	selanjutnya kita akan pilih isian dari bulan 
	yang perlu kita lakukan adalah ... kita mengambil nama id 
*/

$month = $driver->findElement(WebDriverBy::id('month'));
if($month){
	$month->click();

/* 
	apabila kita ingin memilih bulannya yang perlu di lakukan adalah mengambil saja selectornya
	sangat mudah sekali caranya ... 	
*/

	$jan = $driver->findElement(WebDriverBy::cssSelector('#month > option:nth-child(1)'));
	if($jan) $jan->click();
}

/*
	kemudian kita akan memilih tahun dari formulir ini, caranya sama dengan untuk tanggal dan bulan
	yang pertama kita lakukan adalah pertama mengambil id dari si tahun ini ... 
*/

$year = $driver->findElement(WebDriverBy::id('year'));
if($year){
	$year->click();

/*
	selanjutnya apabila kita ingin memilih tahunnya, langkah selanjutnya adalah kita ambil selector cssnya dengan cara seperti biasa ...

	oke begini kurang lebih caranya ...  
*/

	$year1987 = $driver->findElement(WebDriverBy::cssSelector('#year > option:nth-child(34)'));
	if($year1987) $year1987->click();
}


/*
	langkah selanjutnya kita akan memilih jenis kelamin, karena saya adalah laki-laki jadi saya akan memilih opsi laki-laki yang pertama dilakukan adalah kita ambil terlebih dahulu apa selector css dari opsi laki-laki, setelah itu 
*/

$man = $driver->findElement(WebDriverBy::cssSelector('#u_0_5'));
if($man){
	$man->click();
}

/* kemudian langsung kita jalankan */
/* hasilnya adalah seperti ini ... */
/* oke mantap ya semuanya sudah terisi dengan benar */
/* kita akan coba dengan eksekusi akhir mengklik button daftar memanfaatkan css selector */
$daftar = $driver->findElement(WebDriverBy::cssSelector('#u_0_14'));
if($daftar) $daftar->click();

/*
$pass = $driver->findElement(WebDriverBy::id('pass'));
if($pass){
	$pass->sendKeys('passwordnya');
}
*/