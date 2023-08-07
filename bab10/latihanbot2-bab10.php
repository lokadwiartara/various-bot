<?php
	// chromedriver --port=4444 --url-base=/wd/hub
	require_once "vendor/autoload.php";

	use duzun\hQuery;
	use Facebook\WebDriver\Chrome\ChromeDriver;
	use Facebook\WebDriver\Remote\DesiredCapabilities;
	use Facebook\WebDriver\Remote\RemoteWebDriver;
	use Facebook\WebDriver\WebDriverBy;
	use Facebook\WebDriver\WebDriverKeyboard;
	use Facebook\WebDriver\WebDriverKeys;
	use Facebook\WebDriver\WebDriverExpectedCondition;
	use Facebook\WebDriver\Chrome\ChromeOptions;

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
		$task = 1;
	}
	else if(trim($line) == '2'){
		$task = 2;
	}
	
	fclose($handle);

	echo "\n";

	function openBrowserSaveSession(){
		$host = 'http://localhost:4444/wd/hub'; 
		 
		$options = new ChromeOptions();
		$options->setExperimentalOption("excludeSwitches", ['enable-automation', 'enable-logging']);
		$options->addArguments(array('--start-maximized', "--disable-infobars", "--disable-extensions"));
		$prefs['profile']['default_content_setting_values']['notifications'] = 2;
		$options->setExperimentalOption('prefs', $prefs);

		$caps = DesiredCapabilities::chrome();
		$caps->setCapability(ChromeOptions::CAPABILITY, $options);

		$driver = RemoteWebDriver::create($host, $caps);	
		file_put_contents('session.json',
		    json_encode([
		        'sessionID' => $driver->getSessionID()
		    ])
		);	

		return $driver;
	}

	switch($task){
		case 1: 
			echo "Proses login berlangsung...";

			$sessionSaved = json_decode(file_get_contents('session.json'),TRUE);

			if(isset($sessionSaved['sessionID'])){
				$driver = RemoteWebDriver::createBySessionID($sessionSaved['sessionID']);
			}
			else{
				$driver = openBrowserSaveSession();
			}

			try {
			    $driver->get('https://web.whatsapp.com/');
			    sleep(rand(2,4));
			} catch (Throwable $e) {
				$driver = openBrowserSaveSession();
			}

			$driver->get('https://web.whatsapp.com/');	

			echo "\n\nJika sudah merekam barcodenya, ketik 'ya' (tanpa tanda kutip): ";

			$handle = fopen ("php://stdin","r");
			$line = fgets($handle);
			$task = 0;

			if(trim($line) != 'ya'){
			    echo "\nAnda memilih 0, keluar...!\n";
			    exit;
			}			
			
			fclose($handle);					

			echo "Selesai login web whatsapp ...\n";

		break;

		case 2: 
		break;
		default: break;
	}

	echo "\n";
?>