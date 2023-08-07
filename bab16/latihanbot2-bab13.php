<?php

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

		$prefs['profile']['default_content_setting_values']['notifications'] = 2;
		//$prefs['profile']['default_content_setting_values']['geolocation'] = 1;
		$prefs['profile']['password_manager_enabled'] = false;
		$prefs['credentials_enable_service'] = false;
		$prefs['profile']['default_content_setting_values']['images'] = 1;
		$options->setExperimentalOption('prefs', $prefs);
		$options->addArguments(['--start-maximized', '--disable-gpu', '--no-sandbox']);
		$options->setExperimentalOption('excludeSwitches', ['enable-automation', 'enable-logging']);
		$options->setExperimentalOption("mobileEmulation", ["deviceName" => "Galaxy S5"]);

		$caps = DesiredCapabilities::chrome();

		$caps->setCapability(ChromeOptions::CAPABILITY, $options);

		$driver = RemoteWebDriver::create($host, $caps);

		file_put_contents('session.json', 
				json_encode([ 
					'sessionID'=> $driver->getSessionID()
				]) 
			);

		return $driver;		
	}

	function checkExistElement($getPageSource, $pattern){
		$pageSource = hQuery::fromHTML($getPageSource);
		$elementPattern = $pageSource->find($pattern);
		if($elementPattern != NULL && count($elementPattern) > 0){
			return TRUE;
		}
		else{
			return FALSE;
		}
	}

	$sessionSaved = json_decode(file_get_contents('session.json'), TRUE);
	if(isset($sessionSaved['sessionID'])){
		$driver = RemoteWebDriver::createBySessionID($sessionSaved['sessionID']);
	}
	else{
		$driver = openBrowserSaveSession();
	}

	try{
		$driver->get('https://www.instagram.com/accounts/login/');
		sleep(rand(2,4));
	}catch(Throwable $e){
		$driver = openBrowserSaveSession();	
		$driver->get('https://www.instagram.com/accounts/login/');
		sleep(rand(2,4));
	}	

	if(!checkExistElement($driver->getPageSource(), $patternLoggedIn = '#react-root > section > nav.NXc7H.f11OC > div > div > div.KGiwt > div > div > div:nth-child(5) > a')){
		$username = "";
		$password = "";

		if(checkExistElement($driver->getPageSource(), $patternInputUsername = 'input[name="username"]')){
			$inputUsername = $driver->findElement(WebDriverBy::cssSelector($patternInputUsername));
			if($inputUsername){
				$inputUsername->sendKeys($username);
				sleep(rand(1,3));
			}
		}

		if(checkExistElement($driver->getPageSource(), $patternInputPassword = 'input[name="password"]')){
			$inputPassword = $driver->findElement(WebDriverBy::cssSelector($patternInputPassword));
			if($inputPassword){
				$inputPassword->sendKeys($password);
				sleep(rand(1,3));
			}
		}	

		// #loginForm > div.Igw0E.IwRSH.eGOV_._4EzTm.kEKum > div:nth-child(6) > button

		if(checkExistElement($driver->getPageSource(), $patternBtnLogin = '#loginForm > div.Igw0E.IwRSH.eGOV_._4EzTm.kEKum > div:nth-child(6) > button')){
			$btnLogin = $driver->findElement(WebDriverBy::cssSelector($patternBtnLogin));
			if($btnLogin){
				$btnLogin->click();
				sleep(rand(5,7));
			}
		}	
	}

	// #react-root > div > div:nth-child(3) > a._3m3RQ._7XMpj
	if(checkExistElement($driver->getPageSource(), $patternBtnNotNow = '#react-root > div > div:nth-child(3) > a._3m3RQ._7XMpj')){
		$btnNotNow = $driver->findElement(WebDriverBy::cssSelector($patternBtnNotNow));
		if($btnNotNow){
			$btnNotNow->click();
			sleep(rand(3,5));
		}
	}

	// // body > div.RnEpo.Yx5HN > div > div > div > div.mt3GC > button.aOOlW.HoLwm
	if(checkExistElement($driver->getPageSource(), $patternBtnCancel = 'body > div.RnEpo.Yx5HN > div > div > div > div.mt3GC > button.aOOlW.HoLwm')){
		$btnCancel = $driver->findElement(WebDriverBy::cssSelector($patternBtnCancel));
		if($btnCancel){
			$btnCancel->click();
			sleep(rand(3,5));
		}
	}		

	$driver->get('https://www.instagram.com/ilmuwebsite');
	sleep(rand(3,5));

	// #react-root > section > main > div > ul > li:nth-child(2) > a
	if(checkExistElement($driver->getPageSource(), $patternBtnFollowers = '#react-root > section > main > div > ul > li:nth-child(2) > a')){
		$btnFollowers = $driver->findElement(WebDriverBy::cssSelector($patternBtnFollowers));
		if($btnFollowers){
			$btnFollowers->click();
			sleep(rand(3,5));
		}
	}		

	$scrollFollowers = $driver->findElement(WebDriverBy::cssSelector('body'));
	if($scrollFollowers){
		for($x=0;$x<3;$x++){
			$scrollFollowers->sendKeys(WebDriverKeys::END);
			sleep(rand(2,3));
		}
	}

	// #react-root > section > main > div > ul > div > li:nth-child(2) > div > div.t2ksc > div.enpQJ > div.d7ByH > a

	$pageHTML = hQuery::fromHTML($driver->getPageSource());

	$getFollowers = $pageHTML->find('#react-root > section > main > div > ul > div > li:nth-child(2) > div > div.t2ksc > div.enpQJ > div.d7ByH > a');

	if(count($getFollowers) > 0){
		$x = 1;
		$arrayUser = [];
		foreach ($getFollowers as $followers => $f) {
			$arrayUser[] = str_replace('/','', $f->attr('href'));
		}

		$arrayUser = array_unique($arrayUser);

		foreach ($arrayUser as $user) {
			$driver->get('https://www.instagram.com/direct/inbox/');
			sleep(rand(4,6));

			// body > div.RnEpo.xpORG._9Mt7n > div > div.YkJYY > div > div:nth-child(5) > button
			if(checkExistElement($driver->getPageSource(), $patternBtnUseWebDM = 'body > div.RnEpo.xpORG._9Mt7n > div > div.YkJYY > div > div:nth-child(5) > button')){
				$btnUseWebDM = $driver->findElement(WebDriverBy::cssSelector($patternBtnFollowers));
				if($btnUseWebDM){
					$btnUseWebDM->click();
					sleep(rand(4,6));
				}
			}

			$btnSendDM = $driver->findElement(WebDriverBy::cssSelector('#react-root > section > div:nth-child(1) > header > div > div.mXkkY.KDuQp > button'));
			if($btnSendDM){
				$btnSendDM->click();
				sleep(rand(4,6));
			}

			// #react-root > section > div.IEL5I > div > div.TGYkm > div > div.BIyw3 > input
			$inputSendDM = $driver->findElement(WebDriverBy::cssSelector('#react-root > section > div.IEL5I > div > div.TGYkm > div > div.BIyw3 > input'));
			if($inputSendDM){
				$inputSendDM->sendKeys($user);
				sleep(rand(4,6));
			}

			// #react-root > section > div.IEL5I > div > div.Igw0E.IwRSH.eGOV_.vwCYk._3wFWr > div:nth-child(1) > div > div.Igw0E.rBNOH.YBx95.ybXk5._4EzTm.soMvl > button
			$pageHTML = hQuery::fromHTML($driver->getPageSource());
			if(checkExistElement($driver->getPageSource(), $patternUsernameDM = '#react-root > section > div.IEL5I > div > div.Igw0E.IwRSH.eGOV_.vwCYk._3wFWr > div:nth-child(1) > div > div.Igw0E.rBNOH.YBx95.ybXk5._4EzTm.soMvl > button')){
				$getUsernameDM = $driver->findElement(WebDriverBy::cssSelector($patternUsernameDM));
				if($getUsernameDM ){
					$getUsernameDM->click();
					sleep(rand(4,6));
				}
			}

			// #react-root > section > div:nth-child(1) > header > div > div.mXkkY.KDuQp > button
			$nextBtn = $driver->findElement(WebDriverBy::cssSelector('#react-root > section > div:nth-child(1) > header > div > div.mXkkY.KDuQp > button'));
			if($nextBtn){
				$nextBtn->click();
				sleep(rand(4,6));
			}			

			// #react-root > section > div.IEL5I > div > div > div.Igw0E.IwRSH.eGOV_._4EzTm > div > div > div > textarea
			if(checkExistElement($driver->getPageSource(), $patternTextAreaDM = '#react-root > section > div.IEL5I > div > div > div.Igw0E.IwRSH.eGOV_._4EzTm > div > div > div > textarea')){
				$textAreaDM = $driver->findElement(WebDriverBy::cssSelector($patternTextAreaDM));
				if($textAreaDM ){
					$pesan = "Halo ... ".$user."... mohon maaf mengganggu waktunya, hanya sekedar ingin mengirimkan info yang mudah-mudahan bermanfaat untuk Anda. Apakah Anda berkenan? ";
					$textAreaDM->sendKeys($pesan);
					sleep(rand(4,6));
				}
			}			

			// #react-root > section > div.IEL5I > div > div > div.Igw0E.IwRSH.eGOV_._4EzTm > div > div > div.Igw0E.IwRSH.eGOV_._4EzTm.JI_ht > button
			if(checkExistElement($driver->getPageSource(), $patternSendDM = '#react-root > section > div.IEL5I > div > div > div.Igw0E.IwRSH.eGOV_._4EzTm > div > div > div.Igw0E.IwRSH.eGOV_._4EzTm.JI_ht > button')){
				$btnSendDM = $driver->findElement(WebDriverBy::cssSelector($patternSendDM));
				if($btnSendDM ){
					$btnSendDM->click();
					sleep(rand(4,6));
				}
			}			
		}
	}



