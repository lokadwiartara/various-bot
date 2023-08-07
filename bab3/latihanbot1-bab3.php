<?php

/* 
	yang pertama kita lakukan adalah kita buat terlebih dahulu sebuah file kosong, 
	dan kita akan berikan namanya latihanbot1-bab2.php

	kemudian kita buat sebuah object yang menginduk kepada class domDocument,
	kurang lebih seperti ini.
*/

$dom = new domDocument; 

/*
	oh iya lupa saya menjelaskan, untuk masuk ke dalam tahapan ini, diharapkan Anda sebelumnya sudah mengerti sedikit php, sampai ke bagian OOP nya. kemudian pemahaman html juga diperlukan, baiklah kita lanjut lagi. 

	untuk materi dasar-dasar mengenai pemgoraman menggunakan php Anda bisa melihat materi yang ada dalam bab 4

	kemudian karena kita tidak sedang menggunakan URL suatu website untuk diparsing, kita akan buat sendiri halaman html sederhananya, kemudian kita sisipkan html sederhana itu dan menggunakan method yang ada dalam domdocument
*/

$dom->loadHTML('<div class="all">
	<p>Hai, <a href="ilmuwebsite.com">klik disini</a> untuk menuju ilmuwebsite.com<br /> :)</p>
</div>');

/* 	dalam halaman html sederhana yang belum lengkap tersebut terdapat induk elemen yakni div, kemudian di dalam div
	ada paragraph dan di dalam paragraph ada tag anchor atau a yang mewakili link, sebagai contohnya kita akan 
	mengambil anchor saja atau linknya saja. nah kemudian link tersebut kita preteli ya. 
*/

$a = $dom->getElementsByTagName('a');
// print_r
echo $a->item(0)->textContent; // mengambil text 
echo "\n";
echo $a->item(0)->getAttribute('href');
echo "\n";

/* 	
	kemudian kita lihat hasilnya di command line php, anda juga sebetulnya bisa menggunakan browser, 
	cuma agar bisa berjalan php di browser di haruskan terlebih dahulu mengaktifkan webserver nya 
	dalam hal ini kita menggunakan apache, dan tentunya apache ini akan memakan resource lagi,
	mungkin anda penasaran kan cuma sedikit, ya memang sedikit yang dipakai resourcenya tapi 
	silahkan di biasakan untuk menghemat resource sehingga kedepannya anda akan terbiasa bekerja
	secara efisien, baik dari segi penghematan resource hardware hingga ke efisiensi koding mengoding 
*/

/* 
	oke untuk kasus yang lain kita coba mengambil paragraph yang ada dalam document html yang kita 
	buat sebelumnya, caranya mudah sekali cukup memanggil tagnamenya saja seperti ini .. 
*/

$p = $dom->getElementsByTagName('p');
echo $p->item(0)->textContent;
