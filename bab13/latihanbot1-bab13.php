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

function checkExistElement($getPageSource,$pattern){
	$pageSource = hQuery::fromHTML($getPageSource);
    $elementPattern = $pageSource->find($pattern);
    if($elementPattern != NULL && count($elementPattern) > 0){
    	return TRUE;
    }
    else{
    	return FALSE;
    }
}

$sessionSaved = json_decode(file_get_contents('session.json'),TRUE);

if(isset($sessionSaved['sessionID'])){
	$driver = RemoteWebDriver::createBySessionID($sessionSaved['sessionID']);
}
else{
	$driver = openBrowserSaveSession();
}

try {
    $driver->get('https://www.instagram.com/accounts/login/?source=auth_switcher');
    sleep(rand(2,4));
} catch (Throwable $e) {
	$driver = openBrowserSaveSession();
	$driver->get('https://www.instagram.com/accounts/login/?source=auth_switcher');
	sleep(rand(2,4));
}

$username = '';
$password = '';

if(checkExistElement($driver->getPageSource(), $patternInputUsername='input[name="username"]')){
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

if(checkExistElement($driver->getPageSource(), $patternBtnLogin = '#loginForm > div.Igw0E.IwRSH.eGOV_._4EzTm.kEKum > div:nth-child(6) > button > div' )){
	$buttonLogin = $driver->findElement(WebDriverBy::cssSelector($patternBtnLogin));
	if($buttonLogin){
	    $buttonLogin->click();
	    sleep(rand(5,7));
	}	
}

$driver->get("https://www.instagram.com/accounts/onetap/?next=%2F");
sleep(rand(2,4)); 

if(checkExistElement($driver->getPageSource(), $patternbuttonNotNow = '#react-root > section > main > div > div > div > button')){
	$buttonNotNow = $driver->findElement(WebDriverBy::cssSelector($patternbuttonNotNow));
	if($buttonNotNow){
        $buttonNotNow->click();
        sleep(rand(3,5));
    }
}				            

if(checkExistElement($driver->getPageSource(), $patternCancelHome = 'body > div.RnEpo.Yx5HN > div > div > div > div.mt3GC > button.aOOlW.HoLwm')){
	$buttonCancelHome = $driver->findElement(WebDriverBy::cssSelector($patternCancelHome));
    if($buttonCancelHome){
        $buttonCancelHome->click();
        sleep(rand(3,5));
    } 
}


if(checkExistElement($driver->getPageSource(), $patternPostNew = '#react-root > section > nav.NXc7H.f11OC > div > div > div.KGiwt > div > div > div.q02Nz._0TPg')){
	$upload = $driver->findElement(WebDriverBy::cssSelector($patternPostNew));
	if($upload){
		echo "Uploading ... ";
		$upload->click();
		sleep(3);
		shell_exec('D:\My Bot\bab13\upload-image.exe');
	}	
}

sleep(3);

if(checkExistElement($driver->getPageSource(), $patternBtnNext1 = '#react-root > section > div.Scmby > header > div > div.mXkkY.KDuQp > button.UP43G')){
	$btnNext1 = $driver->findElement(WebDriverBy::cssSelector($patternBtnNext1));
	if($btnNext1){
		$btnNext1->click();
		sleep(3);
	}
}

if(checkExistElement($driver->getPageSource(), $patternCaption = '#react-root > section > div.A9bvI > section.IpSxo > div.NfvXc > textarea')){
	$inputCaption = $driver->findElement(WebDriverBy::cssSelector($patternCaption));
	if($inputCaption){
		$inputCaption->sendKeys('testing bot uploader... working');
		sleep(3);
	}
}

if(checkExistElement($driver->getPageSource(), $patternBtnShare = '#react-root > section > div.Scmby > header > div > div.mXkkY.KDuQp > button')){
	$btnShare = $driver->findElement(WebDriverBy::cssSelector($patternBtnShare));
	if($btnShare){
		$btnShare->click();
		echo "selesai!! ";
		sleep(10);
	}	
}