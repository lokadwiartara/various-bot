<?php
	// chromedriver --port=4444 --url-base=/wd/hub
	// https://peter.sh/experiments/chromium-command-line-switches/
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

	$email = '';
	$password = '';


	function openBrowserSaveSession(){
		$host = 'http://localhost:4444/wd/hub'; 
		 
		$options = new ChromeOptions();
		$options->addArguments(array('--start-maximized', '--disable-infobars', '--disable-gpu', '--no-sandbox' )); 

		$options->setExperimentalOption("excludeSwitches", ['enable-automation', 'enable-logging']);
        $prefs['profile']['default_content_setting_values']['notifications'] = 2;
        $prefs['profile']['default_content_setting_values']['geolocation'] = 1;
        $prefs['credentials_enable_service'] = false;        
        $prefs['profile']['password_manager_enabled'] = false;
        $prefs['profile']['default_content_setting_values']['images'] = 1;

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

	$sessionSaved = json_decode(file_get_contents('session.json'),TRUE);

	if(isset($sessionSaved['sessionID'])){
		$driver = RemoteWebDriver::createBySessionID($sessionSaved['sessionID']);
	}
	else{
		$driver = openBrowserSaveSession();
	}

	try {
	    $driver->get('http://olx.co.id/');
	    sleep(rand(2,4));
	} catch (Throwable $e) {
		$driver = openBrowserSaveSession();
		$driver->get('http://olx.co.id/');
	}			

	sleep(rand(3,5));

	$frontPageSource = hQuery::fromHTML($driver->getPageSource());
	$iconProfile = $frontPageSource->find('div[data-aut-id="iconProfile"]');

	// echo count($iconProfile);

	if($iconProfile == NULL){
		$btnLogin = $driver->findElement(WebDriverBy::cssSelector('#container > header > div > div > div._14lZ9._110yh > button'));

		if($btnLogin){
			$btnLogin->click();
		}

		sleep(rand(3,5));

		$btnLoginEmail = $driver->findElement(WebDriverBy::cssSelector('div._2AC5E > div > button[data-aut-id=emailLogin]'));

		if($btnLoginEmail){
			$btnLoginEmail->click();
		}

		sleep(rand(3,5));

		$inputEmail = $driver->findElement(WebDriverBy::cssSelector('#email_input_field'));

		if($inputEmail){
			$inputEmail->sendKeys($email);
		}

		sleep(rand(3,5));

		$btnLanjut = $driver->findElement(WebDriverBy::cssSelector('form > div.BEs0P > button[type=submit]'));

		if($btnLanjut){
			$btnLanjut->click();
		}

		sleep(rand(3,5));

		$inputPassword = $driver->findElement(WebDriverBy::cssSelector('#password'));

		if($inputPassword){
			$inputPassword->sendKeys($password);
		}

		sleep(rand(3,5));

		$btnLogin = $driver->findElement(WebDriverBy::cssSelector('form > div.BEs0P > button[type=submit]'));

		if($btnLogin){
			$btnLogin->click();
		}

		sleep(rand(3,5));

		$btnLocation = $driver->findElement(WebDriverBy::cssSelector('#container > header > div > div > div._2KctL > div > div > div:nth-child(1) > div > div'));

		if($btnLocation){
			$btnLocation->click();
		}

		sleep(rand(3,5));

		$btnLocationNow = $driver->findElement(WebDriverBy::cssSelector('#container > header > div > div > div._2KctL > div > div > div:nth-child(1) > div > div:nth-child(2) > div > div.gs1FE._1ssIk > div'));

		if($btnLocationNow){
			$btnLocationNow->click();
		}

		sleep(rand(3,5));	
	}

	$btnKategori = $driver->findElement(WebDriverBy::cssSelector('#container > div > div > div > div._17tTs > div._1YKEc > span._2uhZ0 > button'));

	if($btnKategori){
		$btnKategori->click();
	}

	sleep(rand(3,5));

	$linkMobil = $driver->findElement(WebDriverBy::cssSelector('#container > div > div > div > div._2NAUI > div > div:nth-child(1) > div:nth-child(2) > a'));

	if($linkMobil){
		$linkMobil->click();
	}

	/* AMBIL SEMUA ITEMNYA */