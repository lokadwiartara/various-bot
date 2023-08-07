<?php

/*
	setelah mengerti dari dasar dalam melakukan parsing menggunakan hQuery, selanjutnya kita akan 
	masuk lagi ke dalam proses parsing yang sedikit kompleks, masih menggunakan ilmuwebsite.com 
	sebagai tumbal, atau kelinci percobaannya. 
*/

require_once "vendor/autoload.php";

use duzun\hQuery;

/*
	pada praktik kali ini kita akan mengambil detil artikel dari satu halaman kategori yang 
	ada di ilmuwebsite.com, misalnya kategori web development, kita akan menampilkan ke 
	seluruhan artikel dari setiap detil artikelnya
*/

$doc = hQuery::fromUrl('https://www.ilmuwebsite.com/web-development', 
	['Accept' => 'text/html,application/xhtml+xml;q=0.9,*/*;q=0.8']);

/*
	yang pertama kali kita lakukan adalah mencari css selector terlebih dahulu 
*/

$patternLinkhead = 'div.post-content-details > div.post-title > h3 > a';

/* 
	apabila terlalu panjang, dan hasilnya belum memadai Anda bisa melakukan 
	perubahan di sisi selector nya misalnya menjadi seperti ini 

	div.post-content-details > div.post-title > h3 > a
*/

$getLinkhead = $doc->find($patternLinkhead);

// echo count($getLinkhead);

/* 
	jika sudah bernilai sama dengan yang tampil, berarti sudah pas, kita akan lanjutkan ... 
	(di commenting bagian echo count nya)
*/

if(count($getLinkhead) > 0){
	foreach($getLinkhead as $data => $linkhead){
		// echo $linkhead->attr('href') . "\n";

/*
	kita sudah mendapatkan link dari headnya, mantap, kita lanjut masuk ke dalam 
	setiap link tersebut, jadi yang akan kita parsing adalah : 
	* judul dari artikel
	* gambar dari artikel
	* cuplikan artikel mengambil hanya satu-tiga kalimat, atau beberapa karakter saja.  
*/
	echo "-----------------------------------------------------------------\n";
	echo 'Proses parsing artikel "'.$linkhead->text().'"'."\n".$linkhead->attr('href')."... \n\n";

	/* 	
		setelah mendapatkan linknya, kita bisa masuk ke dalam halaman website menggunakan
		link/url tersebut, caranya seperti ini : 
	*/

	$detail = hQuery::fromUrl($linkhead->attr('href'), 
		['Accept' => 'text/html,application/xhtml+xml;q=0.9,*/*;q=0.8']);
	
	/* 
		setelah itu kita bisa parsing, satu demi satu yang akan kita keluarkan
		diantaranya ada judul, gambar/images, dan yang terakhir adalah articlenya
	*/

	$titles = $detail->find('div.post-content-details > div.post-title > h2');
	$images = $detail->find('div.post-item > div.post-image > a > img');
	$articles = $detail->find('div.post-item > div.post-content-details > div.post-description > div');

	/*
		setelah didefinisikan langsung saja kita keluarkan isinya, caranya adalah 
		seperti ini kurang lebih
	*/

	echo "***Judul : ".$titles[0]->text()."\n";
	echo "***Gambar : ".$images[0]->src."\n";
	echo "***Isi Artikel : ".substr($articles[0]->text(),0,500)."... \n";

	echo "\n";	

	/*
		agar tidak disangka bot, kita bisa mengurangi kecepatan parsingnya, dengan menggunakan
		sleep, sehingga nantinya ada jeda waktu, saya berikan di sini 2, sehingga nantinya
		akan ada jeda waktu 2 detik, setiap kali mengunjungi link untuk melakukan parsingnya.
	*/
	sleep(2);	
	}
}