<?php

/* 
	baiklah selanjutnya kita akan belajar bagaimana melakukan parsing terhadap suatu halaman
	website menggunakan URL suatu website, pada latihan kali ini saya akan jelaskan bagaimana
	meload suatu URL untuk diambil HTML page nya, untuk diambil halaman htmlnya
	sehingga kita bisa melakukan parsing. 

	Pada latihan kali ini kita akan menggunakan ilmuwebsite sebagai kambing hitam percobaan,
	menggunakan salah satu kategori dari website ilmuwebsite.com ini, untuk nantinya
	diambil daftar judul-judul di halaman kategori tersebut

	baiklah langsung saja ya ... 

*/


/* 
	yang pertama kali di lakukan seperti biasanya adalah pertama kali mendefinisikan object nya dulu
	menginduk kepada class domDocument, sehingga dari sinilah kita bisa melakukan parsing menggunakan
	library bawaan php untuk parsing
*/

$dom = new domDocument; 


/*
	kemudian karena dia diambil dari suatu halaman html, sudah dipastikan bahwa halaman html
	itu biasanya ada saja bagian yang tidak standar susunan htmlnya, sehingga nantinya bisa
	menghambat proses parsing, sehingga kita perlu mengantisipasinya dengan seperti ini ...
*/

libxml_use_internal_errors(true);

/* 
	jika sudah kita akan langsung lakukan load URL halaman html yang akan kita parsing
*/

$dom->loadHTMLFile('https://www.ilmuwebsite.com/web-development');

/*
	kemudian kita akan memanfaatkan domxpath, yang mana nantinya hal ini digunakan untuk
*/

$xpath = new DOMXpath($dom);

/*
	langkah selanjutnya adalah kita akan mengambil judul dengan cara 
	menggunakan XPATH dari si judulnya, nah ini bisa diambil menggunakan browser chrome, 
	caranya sangat mudah ... 
	
*/

$element = $xpath->query("//*/div[@class='post-title']/h3/a");

/*
	nah tentunya element yang berisi judul-judul, tidak hanya memiliki satu judul, tapi banyak
	nah kita cek terlebih dahulu apakah judulnya yang diambil dengan pola xpath
	itu ada ... alias exist, bernilai lebih dari nol
*/

if(count($element) > 0){

	/*
		jika ada maka kita akan lakukan pengulangan foreach untuk 
		mengeluarkan judul-judul tersebut kedalam barisan text,
		kurang lebih seperti ini ... 
	*/
	$x = 1;
	foreach($element as $e){
		echo $x. ". ". $e->textContent ."\n".  $e->getAttribute('href') . "\n\n";
		$x++;
	}
}


/*
	sehingga nantinya apabila kita lihat di command promptnya hasilnya kurang lebih nanti
	akan seperti ini .. 
*/