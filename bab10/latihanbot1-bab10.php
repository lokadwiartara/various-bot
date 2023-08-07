<?php

	echo "\nSilahkan pilih pekerjaan yang akan di lakukan BOT :\n\n";
	echo "1 Login Web Whatsapp\n";
	echo "2 Kirim Pesan\n";
	echo "0 Keluar\n\n";
	echo "Masukkan jenis pekerjaannya (ketikkan nomor, \nsetelah itu tekan enter untuk melanjutkan) : ";

	$handle = fopen ("php://stdin","r");
	$line = fgets($handle);
	$task = 0;

	if(trim($line) == '0'){
	    echo "\nAnda memilih 0, keluar...!\n";
	    exit;
	}
	else if(trim($line) == '1'){
		//echo "Anda memilih login web...";
		$task = 1;
	}
	else if(trim($line) == '2'){
		//echo "Anda memilih kirim pesan...";
		$task = 2;
	}
	
	fclose($handle);

	echo "\n";

?>