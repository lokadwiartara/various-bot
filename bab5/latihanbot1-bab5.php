<?php

/* 
	yang perlu dilakukan pertama kali adalah, meload terlebih dahulu autoload.php
	dimana autoload.php ini dihasilkan oleh composer, nah ketika meload autoload.php
	apabila nanti ada lagi library yang baru kita sisipkan, kita tidak perlu lagi 
	meloadnya, kita cukup langsung gunakan saja library, untuk meload autoload.php, 
	cukup tuliskan seperti ini ... 
*/

require_once "vendor/autoload.php";

/*
	setelah dilakukan load, selanjutnya kita harus memastikan bahwa kita akan 
	menggunakan fasilitas dari hQuery, nah caranya adalah seperti ini.
*/

use duzun\hQuery;

/*
	dalam readmenya, ketika kita akan melakukan parsing dari suatu URL, 
	
	https://github.com/duzun/hQuery.php

	Anda cukup mendefinisikannya seperti ini saja,
*/

$doc = hQuery::fromUrl('https://www.ilmuwebsite.com', ['Accept' => 'text/html,application/xhtml+xml;q=0.9,*/*;q=0.8']);


/* 
	pada praktik kali ini kita akan mencoba mengambil link-link yang didalamnya 
	terdapat suatu gambar, atau gambar yang berada di dalam link. nah biasanya
	ini mewakili thumbnail dari suatu artikel, dimana ketika thumbnail / image nya
	di klik dia akan masuk kedalam suatu halaman lainnya, terkait dari detil
	dari suatu artikel, atau newsnya.
*/


/*  
	yang pertama kita lakukan adalah mendefinisikan terlebih dahulu, selector css 
	untuk nantinya digunakan dalam proses parsing. kurang lebih seperti ini 
*/
$getImages = $doc->find('a[href] > img');

/*
	setelah itu dilakukan pengecekan terlebih dahulu apakah link yang didalamnya
	terdapat image apakah exist ? atau tidak ? 
*/
if(count($getImages) > 0){

/*
	jika exist, maka kita akan lakukan looping/pengulangan untuk
	mengeluarkan barisan imagenya. caranya sangat mudah cukup seperti ini saja
	yang mana caranya tidak terlalu jauh berbeda ketika menggunakan domdocument
	maupun menggunakan domxpath
*/

	foreach ($getImages as $data => $image) {
		echo $image->attr('src')."\n";
	}
}

/*
	bagaimana mudah bukan ? selanjutnya kita akan mencoba praktik yang lainnya,
	tapi masih di dasar dalam melakukan parsing menggunakan hquery. 
	disini kita akan mengambil judul artikel, yang diwakili dengan tag h2.
	yang perlu dilakukan adalah seperti ini : 
*/

$getHeading2 = $doc->find('h2');

/*
	setelah itu kita hitung terlebih dahulu apakah h2 nya itu exist dalam htmlnya
*/
if(count($getHeading2) > 0){

/*
	jika eksis kita akan lakukan pengulangan untuk mengeluarkan hasil dari array 
	yang berisi judul judulnya
*/
	foreach ($getHeading2 as $data => $head) {
		echo $head->text()."\n";
	}
}
