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

	function handleElement($driver,$pattern,$event,$key=null,$minTime=3,$maxTime=5){
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

	$sessionSaved = json_decode(file_get_contents('session.json'),TRUE);

	if(isset($sessionSaved['sessionID'])){
		$driver = RemoteWebDriver::createBySessionID($sessionSaved['sessionID']);
	}
	else{
		$driver = openBrowserSaveSession();
	}

	try {
	    $driver->get('https://m.bukalapak.com');
	    sleep(rand(2,4));
	} catch (Throwable $e) {
		$driver = openBrowserSaveSession();
		$driver->get('https://m.bukalapak.com');
		sleep(rand(2,4));
	}
	
	$driver->get('https://m.bukalapak.com');
	sleep(rand(5,7));

	handleElement($driver, $patternPromo = '#branch-banner-close1', 'click');
	sleep(rand(5,7));	

	$driver->get('https://m.bukalapak.com/login');
	
	if(checkExistElement($driver,$patternInputUsername = '#user_session_username')){
		handleElement($driver, $patternInputUsername = '#user_session_username', 'sendkey', $email);

		handleElement($driver, $patternInputPassword = '#user_session_password', 'sendkey', $password);

		handleElement($driver, $patternButtonLogin = '#new_user_session > div > div > input.btn.btn--red.btn--medium.btn--block.js-btn-menu-login.js-tfa-required-button', 'click');		
	}

	$driver->get('https://m.bukalapak.com/u/portal_grosir_herbal/feedback');
	sleep(rand(5,7));

	if(checkExistElement($driver,$patternToko = 'body')){
		for($x=0;$x<3;$x++){
			$bodyScroll = $driver->findElement(WebDriverBy::cssSelector('body'));
			if($bodyScroll){
				$bodyScroll->sendKeys(WebDriverKeys::END);
				sleep(rand(7,15));
			}
		}
	}	

	$listBuyerPS = hQuery::fromHTML($driver->getPageSource());

	$getListLinkBuyer = $listBuyerPS->find('body > div.quest > div.bl-tab-content.content-list > div > div.bl-flex-item.u-flex--1 > p:nth-child(2) > a');

	if(count($getListLinkBuyer) > 0){
		$x = 0;
		$linkBuyers = [];
		$nameBuyers = [];
		foreach($getListLinkBuyer as $data => $link){
			$linkBuyers[$x] = $link->attr('href');
			$nameBuyers[$x] = trim($link->text());
			$x++;
		}

		$linkBuyers = array_values(array_unique($linkBuyers));
		$nameBuyers = array_values(array_unique($nameBuyers));

		$x = 0;
		foreach($linkBuyers as $linkBuyer){
			$no = $x + 1;
			echo $no.". "." Proses mem-Follow up buyer ".$nameBuyers[$x]." => ".$linkBuyer." ... ";

			$driver->get('https://m.bukalapak.com'.$linkBuyer);
			sleep(rand(3,5));

			handleElement($driver, $patternBtnChat = '#merchant-page > div > section > section > div.bl-flex-container.u-margin-top--0.u-bg--white.u-border-top--1--sand-dark.u-overflow--hidden > div:nth-child(1) > a', 'click');

			if(checkExistElement($driver,$patternInputTextChat = '#new_messages_message > div.message-chat-textarea.js-has-hint > textarea')){
				$pesan = 'Halo '.$nameBuyers[$x].', mohon maaf mengganggu waktunya...' ; 

				$inputTextChat = $driver->findElement(WebDriverBy::cssSelector($patternInputTextChat));

				$inputTextChat->sendKeys($pesan);

				sleep(rand(3,5));

				if(checkExistElement($driver, $patternOngkir = '#branch-banner-iframe')){

						$frameBanner = $driver->findElement(WebDriverBy::id('branch-banner-iframe'));
						$driver->switchTo()->frame($frameBanner);
						
						$patternOngkir = '.branch-banner-left > .branch-banner-close.close';
						$btnClosePromo = $driver->findElement(WebDriverBy::cssSelector($patternOngkir));
						$btnClosePromo->click();						
						$driver->switchTo()->defaultContent();
				}

				handleElement($driver, $patternBtnKirim = '#new_messages_message > div.sticky-wrapper.message-chat-wrapper > div > input', 'click');
				sleep(rand(7,10));							
			}

			echo "SELESAI!\n";

			$x++;	
		}
	}