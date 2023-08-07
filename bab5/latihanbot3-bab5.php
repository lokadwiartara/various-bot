<?php

/*
	Kemudian setelah mencoba duzun hquery dan library parsing lainnya,
	
	"muncul pertanyaan seperti ini ... " 
	
	Lalu bagaimana caranya bisa melakukan parsing html dari halaman 
	yang mengharuskan login terlebih dahulu ???

	Nah kita bisa memadukannya, dengan duzun dan goutte...

	Goutte menggunakan fasilitas dari GuzzleHTTP yang mana GuzzleHTTP 
	ini menggunakan library CURL untuk melakukan request HTTP melalui url. 

	Nah kelemahan bot yang dibuat menggunakan Goutte ini tidak bisa 
	menghandle website-website jenis HTML tak kasat mata, yang pada bab 3 
	sudah saya jelaskan detilnya.

	semacam Tokopedia, facebook, airbnb yang frontend nya di buat menggunakan 
	fullstack javascript, atau website jenis single page application lainnya. 
	
	baiklah untuk bisa menggunakan goutte kita terlebih dahulu lakukan 
	pengambilan library goutte nya terlebih dahulu

	dalam halaman readme githubnya https://github.com/FriendsOfPHP/Goutte
	untuk mengambil library goutte cukup lakukan saja seperti ini ... 

	composer require fabpot/goutte
	
	nah apabila sudah di lakukan pengambilan library kita bisa cek di folder 
	vendor kurang lebih seperti ini nanti hasilnya, yang kurang
	lebih sama dengan yang ada di dalam folder pc/laptop milik Anda.
*/

/* 
	sebagai contohnya kita akan menggunakan sebuah halaman login
	yang sudah saya sediakan di ilmuwebsite.com, URL-nya disini ... 

	kemudian kita bisa testing terlebih dahulu 
	kita bisa login dengan username dan password sama-sama admin

	setelah kita login nanti kita akan dihadapkan kesebuah halaman admin page
	yang mana disitu berisi halaman member user. 

	nah halaman inilah yang nantinya kita akan grab isinya, kemudian kita keluarkan
	isinya ke layar, namun Anda bisa juga memasukkannya ke dalam database nantinya ... 
*/


/* 
	baiklah yang perlu kita lakukan pertama kali adalah ... 
	seperti biasa kita akan load autoload nya 
*/

require_once "vendor/autoload.php";

/*
	setelah itu kita panggil kedua library yang akan digunakan
*/

use duzun\hQuery;
use Goutte\Client;

/*
	lalu silahkan dipersiapkan objek nya, menggunakan class client
	yang disediakan oleh library goutte
*/

$client = new Client();

/*
	kemudian kita pilih terlebih dahulu website yang akan kita lakukan
	login untuk nantinya kita bisa parsing
*/

$crawler = $client->request('GET', 'https://www.ilmuwebsite.com/botlogin/index.php?page=login');
// echo $crawler->html();

/*
	lalu silahkan cari button untuk loginnya
*/

$form = $crawler->selectButton('Login')->form();

/*
	setelah itu kita langsung submit username dan password sehingga kita 
	bisa login dengan cara mengisikan form lalu mensubmit/mengirimkannya
*/

$crawler = $client->submit($form, array('username' => 'admin', 'password' => 'admin'));
// echo $crawler->text();

/*
	nah biasanya jika sudah berhasil kita bisa langsung masuk ke dalam halaman lainnya
	yang mana kita bisa langsung lakukan parsing, setelah melakukan proses login, seperti
	ini, bagaimana mudah bukan caranya ?
*/

$crawler = $client->request('GET', 'https://www.ilmuwebsite.com/botlogin/index.php?page=user-list');

/*
	untuk membuktikan proses kita sudah login atau belum silahkan saja
	di echo kan html nya. caranya kurang lebih seperti ini ... 
*/

// echo $crawler->html();

/*
	baik ya sudah kita sudah cek dan ternyata sudah bisa login dengan goutte 
	langkah selanjutnya kita akan ambil table usernya, caranya bagaimana
	yang pertama kita lakukan adalah menyimpan html yang tadi sudah login itu
	kedalam suatu variable seperti ini 
*/

$htmlAdminPage = $crawler->html();

/*
	setelah itu kita inisialisasi, kita gunakan hQuerynya untuk disisipkan
	ke dalam variable table sehingga nantinya bisa digunakan dalam proses parsing
*/

$table = hQuery::fromHTML($htmlAdminPage);

/*
	setelah itu kita hanya akan mengambil table usernya saja, kurang lebih seperti ini
	patternnya 
*/

$patternTable = '#table-user';

/*
	kemudian kita langsung saja grab si table tersebut
*/

$getTable = $table->find($patternTable);

/*
	untuk mengeceknya kita bisa gunakan echo $getTable untuk 
	mengecek apakah nilainya lebih dari 0, yang mana itu artinya adalah exist/ada
*/

echo count($getTable);

/*
	kemudian kita bisa keluarkan htmlnya , apakah sudah bentuk table nya saja ? 
*/

echo $getTable;

/*
	setelah itu kita akan gunakan html table tersebut, untuk kemudian kita akan parsing
	hanya untuk diambil kontennya saja, seperti nama email dan nomor handphone
	caranya adalah kita akan gunakan html dari hasil grab tadi, yang berupa tablenya saja
	nah itu kita parsing lagi kita akan mengambil tbody tr nya saja.
	yang mana data-data dari table itu diwakili oleh tbody tr 
*/

$tbody = hQuery::fromHTML($getTable);
$getTr = $tbody->find('tbody tr');

/*
	kemudian bisa kita keluarkan/cek kode html yang dihasilkan oleh kalimat parsing ini 
*/

echo $getTr;

/*
	oke kurang lebih seperti itu ya, kemudian kita akan keluarkan setiap barisnya seperti ini 
*/	


foreach($getTr as $row){
		echo $row;
}


/*
	lalu kita akan keluarkan satu demi satu isi dari kolom tablenya, atau td (table data)
	caranya seperti ini 
*/

foreach($getTr as $row){
	echo $row->children." \n";
}

/*
	kemudian untuk mengambil setiap satuan childrennya kita akan gunakan arraynya
	caranya seperti ini 
*/

foreach($getTr as $row){
	echo $row->children[0]." \n";
}

/*
	untuk versi lengkapnya kita akan keluarkan semuanya ... 
*/

$x = 1;
echo "\n";
foreach($getTr as $row){
	
	echo "Data ke : ".$x."\n";
	
	echo "Nama : ".$row->children[0]."\n";
	echo "Email : ".$row->children[1]."\n";
	echo "No. handphone : ".$row->children[2]."\n";
	echo "\n################################\n\n";
	$x++;
}

/*
	langkah terakhir adalah kita akan masukkan itu semua ke dalam database.
	caranya seperti apa ?
	mudah sekali ... 
	
	kita akan gunakan sebuah library php yang sangat mudah digunakan, 
	untuk proses CRUD pada database
	
	yang perlu kita lakukan adalah, mempersiapkan dulu database dan tablenya
	menggunakan phpmyadmin, simak selengkapnya

	https://github.com/WebsiteBeaver/Simple-MySQLi

	jika sudah selesai, selanjutnya kita akan menginstall library simple-mysqli
	sehingga kita bisa melakukan operasi CRUD terhadap database yang baru saja kita buat.
	
	#####################################

	Setelah beres kita langsung lakukan pembuatan koneksi antara bot dan database mysql nya
	caranya seperti ini ... 

try {
 	$mysqli = new SimpleMySQLi("localhost", "root", "", "latbot", "utf8mb4", "assoc");
} catch(Exception $e) {
 	error_log($e->getMessage());
 	exit('Someting weird happened'); //Should be a message a typical user could understand
}

$x = 1;
echo "\n";
foreach($getTr as $row){
	
	echo "Data ke : ".$x."\n";
	
	echo "Nama : ".$row->children[0]."\n";
	echo "Email : ".$row->children[1]."\n";
	echo "No. handphone : ".$row->children[2]."\n";

	$insert = $mysqli->query("INSERT INTO tb_db_user (nama, email, no_hp) 
		VALUES (?, ?, ?)", [ $row->children[0], $row->children[1],$row->children[2] ]);

	// echo $insert->affectedRows();
	// echo $insert->insertId();

	if($insert){
		echo "\nBerhasil di insert ke database ... \n";

	}

	echo "\n################################\n\n";

	$x++;
}


*/


/*
	untuk dokumentasi lebih lengkapnya, silahkan kunjungi 
	http://hquery.duzun.me/doxy/html/functions_func.html 
*/