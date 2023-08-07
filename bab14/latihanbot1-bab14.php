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

	function openBrowserSaveSession(){
		$host = 'http://localhost:4444/wd/hub'; 
		$options = new ChromeOptions();
		$options->addArguments(array('--start-maximized', '--disable-infobars', '--disable-gpu', '--no-sandbox' )); 

		$options->setExperimentalOption("excludeSwitches", ['enable-automation', 'enable-logging']);
		$options->setExperimentalOption("mobileEmulation", ["deviceName" => "Galaxy S5"]);
	    $prefs['profile']['default_content_setting_values']['notifications'] = 2;
	    //$prefs['profile']['default_content_setting_values']['geolocation'] = 1;
	    $prefs['credentials_enable_service'] = false;        
	    $prefs['profile']['password_manager_enabled'] = false;
	    //$prefs['profile']['default_content_setting_values']['images'] = 1;

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

	function checkExistElement($driver,$pattern){
		$getPageSource = $driver->getPageSource();
		$pageSource = hQuery::fromHTML($getPageSource);
	    $elementPattern = $pageSource->find($pattern);

	    if($elementPattern != NULL && count($elementPattern) > 0){
	    	return TRUE;
	    }
	    else{
	    	return FALSE;
	    }		
	}

	function handleElement($driver,$pattern,$event,$key=null,$minTime=5,$maxTime=10){
		$getPageSource = $driver->getPageSource();
		$pageSource = hQuery::fromHTML($getPageSource);
	    $elementPattern = $pageSource->find($pattern);

	    if($elementPattern != NULL && count($elementPattern) > 0){

			$element= $driver->findElement(WebDriverBy::cssSelector($pattern));
			if($element){
				if($event == 'click'){
					$element->click();
				}
				else if($event == 'sendkey'){
					$element->sendKeys($key);
				}
		        
		        sleep(rand($minTime,$maxTime));
		    }	
	    }
	    else{
	    	return FALSE;
	    }
	}

	$email = '';
	$password = '';
	$nama = "";

	$sessionSaved = json_decode(file_get_contents('session.json'),TRUE);

	if(isset($sessionSaved['sessionID'])){
		$driver = RemoteWebDriver::createBySessionID($sessionSaved['sessionID']);
	}
	else{
		$driver = openBrowserSaveSession();
	}

	try {
	    $driver->get('https://m.tokopedia.com');
	    sleep(rand(2,4));
	} catch (Throwable $e) {
		$driver = openBrowserSaveSession();
		$driver->get('https://m.tokopedia.com');
		sleep(rand(2,4));
	}
	
	$driver->get('https://m.tokopedia.com/user');
	
	if(!checkExistElement($driver, $patternUserLogin = '#content > div > div.css-1qdx46r > div.css-1kukc4r > div > h4')){	
		sleep(rand(5,7));

		handleElement($driver, $patternBtnBrowser = 'body > div > div.unf-sheet__wrapper.css-hs190r-unf-bottomsheet-container.e1t50qzi0 > article > div > div > div > div:nth-child(3) > button', 'click',null,5,10);

		handleElement($driver, $patternBtnClose = '#content > div > div.css-1b63jbm.show > div.css-4y7pje > img', 'click',null,5,10);

		handleElement($driver, $patternInputEmail = '#input', 'click',null,5,10);

		$driver->get('https://m.tokopedia.com/login?ld');

		handleElement($driver, $patternInputEmail = '#input', 'sendkey', $email,5,10);

		handleElement($driver, $patternBtnNext = '#button-submit', 'click',null,5,10);

		handleElement($driver, $patternPassword = '#password', 'sendkey', $password,5,10);

		handleElement($driver, $patternBtnLogin = '#button-submit', 'click',null,5,10);
		
		handleElement($driver, $patternVerifikasi = '#content > div > div.css-m3m09l > div > div.css-1q4odjg > div', 'click',null,10,20);

		if(checkExistElement($driver, $patternKodeVerifikasi = '#content > div > div.css-m3m09l > div > div.css-1kg220 > input')){
			echo "Masukkan kode verifikasinya : ";

			$handle = fopen ("php://stdin","r");
			$code = fgets($handle);

			if(trim($code) == '0'){
				echo "\nAnda memilih 0, keluar...!\n";
				exit;
			}

			fclose($handle);	

			handleElement($driver, $patternKodeVerifikasi = '#content > div > div.css-m3m09l > div > div.css-1kg220 > input', 'sendkey', $code, 5,10);

			handleElement($driver, $patternBtnVerifikasi = '#content > div > div.css-m3m09l > div > button', 'click',null, 5, 10);			
		}	
	}	

	$driver->get('https://m.tokopedia.com/search?q=herbal&st=shop');
	sleep(rand(5,10));

	if(checkExistElement($driver,$patternToko = 'body')){
		for($x=0;$x<2;$x++){
			$bodyScroll = $driver->findElement(WebDriverBy::cssSelector('body'));
			if($bodyScroll){
				$bodyScroll->sendKeys(WebDriverKeys::END);
				sleep(rand(7,15));
			}
		}
	}

	$listTokoPS = hQuery::fromHTML($driver->getPageSource());

	$getListLinkToko = $listTokoPS->find('#content > div > div.css-1miza9j > div > div > a.css-17mdoot');

	if(count($getListLinkToko) > 0){
		$x = 0;
		$linkTokos = [];
		foreach($getListLinkToko as $data => $link){
			$linkTokos[$x] = $link->attr('href');
			$x++;
		}		

		$x = 1;
		foreach($linkTokos as $linkToko){
			$driver->get('https://m.tokopedia.com'.$linkToko);
			sleep(rand(3,5));

			$bodyScroll = $driver->findElement(WebDriverBy::cssSelector('body'));
			$bodyScroll->sendKeys(WebDriverKeys::PAGE_DOWN);
			sleep(rand(2,3));
			
			$bodyScroll->sendKeys(WebDriverKeys::PAGE_UP);
			sleep(rand(2,3));

			handleElement($driver, $patternBtnChat = '#content > div > div:nth-child(2) > div.css-1a9h75c.is-seen', 'click');

			if(checkExistElement($driver,$patternName = '#content > div > div > div.css-s5hc7d > div.css-qty2ma > nav > div > div > div.css-n7qnqa > div.css-1cjqj6a > div.css-i6bazn > div > h3')){
				$tokoNamePS = hQuery::fromHTML($driver->getPageSource());
				$tokoName = $tokoNamePS->find($patternName);

				foreach($tokoName as $data => $link){
					$linkTokos = $link->text();
				}		

				echo $x.". ".$linkTokos. "\n";
			}	

			$pesan = 'Halo '.$linkTokos.', mohon maaf mengganggu waktunya...' ; 

			handleElement($driver, $patternInputChat = '#content > div > div > div.css-1twk0fb > form > div > div > div', 'sendkey', $pesan);	

			// handleElement($driver, $patternBtnSend = '#content > div > div > div.css-1twk0fb > form > button', 'click');

			$x++;	
		}
	}