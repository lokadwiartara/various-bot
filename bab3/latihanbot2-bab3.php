<?php

/*
	selain menggunakan versi inline, maksudnya html nya itu disisipkan ke dalam variable  
	seperti contoh di latihanbot1, kita juga bisa 
	menggunakan file html untuk bisa melakukan parsing, contohnya seperti ini 

	kita persiapkan terlebih dahulu file html nya ...
	kurang lebih seprti ini ..., berisi table-table data, yang mana 
	tugas dari bot nya adalah memparsing table-table menjadi barisan data
	yang mana nantinya bisa dimasukkan ke dalam database data misalnya
	data peserta kursus

	pertama kita sediakan terlebih dahulu variable yang isinya itu url atau path
	dari suatu file htmlnya cara seperti ini ... sehingga nantinya path/letak 
	suatu file nantinya bisa dengan mudah dipanggil oleh method loadhtmlfile yang
	ada dalam domdocument
*/

$file = __DIR__. "/file.html";

/*
	kemudian kita buat objek dari domdocumentnya 
*/

$dom = new domDocument; 

/*
	setelah itu kita langsung load saja file htmlnya seperti ini
*/

$dom->loadHTMLFile($file);

/*
	setelah diload kita bisa langsung mengambil data yang ada dalam table tersebut
	yang pertama dilakukan adalah seperti ini ... 
	kemudian kita ambil data tablenya di mulai dari tbody
	lalu setelah itu diambil perbarisnya ... 
*/

$tables = $dom->getElementsByTagName('tbody'); 
$rows = $tables->item(0)->getElementsByTagName('tr'); 

/*
	nah dari setiap barisnya itu kita ambil setiap data di bagi per kolomya
	kurang lebih seperti ini .. 
	...
	
*/

foreach ($rows as $row) {
	$cols = $row->getElementsByTagName('td'); 
	echo 'Materi: '.$cols->item(0)->nodeValue."\n"; 
	echo 'Tutor: '.$cols->item(1)->nodeValue."\n"; 
	echo 'Peserta: '.$cols->item(2)->nodeValue; 
	echo "\n--------------------------------------------\n"; 
}

