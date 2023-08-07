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

// chromedriver --port=4444 --url-base=/wd/hub

$driver->get("https://www.instagram.com/ilmuwebsite/followers/");
sleep(rand(3,5));

if(checkExistElement($driver->getPageSource(), $patternBtnFollower = '#react-root > section > main > div > ul > li:nth-child(2) > a')){
	$btnFollower = $driver->findElement(WebDriverBy::cssSelector($patternBtnFollower));
	if($btnFollower){
		$btnFollower->click();
		sleep(rand(3,5));
	}
}

$scrollPeople = $driver->findElement(WebDriverBy::cssSelector('body'));

if($scrollPeople){
	for($x=0;$x<3;$x++){
		$scrollPeople->sendKeys(WebDriverKeys::END);
		sleep(3);		
	}
}

$pageHTML = hQuery::fromHTML($driver->getPageSource()); 

$getUser = $pageHTML->find('#react-root > section > main > div > ul > div > li > div > div.t2ksc > div.enpQJ > div.d7ByH > a');

if(count($getUser) > 0){
	$x = 1;
	$arrayUser = [];
    foreach($getUser as $post => $a){
    	$arrayUser[] = str_replace('/', '', $a->attr('href'));
        // echo $x.'. '.$a->attr('href')."\n";
        $x++;
    }

    $arrayUser = array_unique($arrayUser);

    foreach($arrayUser as $user){
        $driver->get('https://www.instagram.com/direct/inbox/');
        sleep(rand(4,6));

        if(checkExistElement($driver->getPageSource(), $btnuseAppPattern = 'body > div.RnEpo.xpORG._9Mt7n > div > div.YkJYY > div > div:nth-child(5) > button')){
            $btnuseApp = $driver->findElement(WebDriverBy::cssSelector($btnuseAppPattern));
            if($btnuseApp){ 
                $btnuseApp->click();
                sleep(rand(4,6));
            }
        } 

        $btnSendDM = $driver->findElement(WebDriverBy::cssSelector('#react-root > section > div:nth-child(1) > header > div > div.mXkkY.KDuQp > button'));
        if($btnSendDM){ 
            $btnSendDM->click();
            sleep(rand(4,6));
        }

        $inputSendDM = $driver->findElement(WebDriverBy::cssSelector('#react-root > section > div.IEL5I > div > div.TGYkm > div > div.BIyw3 > input'));
        if($inputSendDM){ 
            $inputSendDM->sendKeys($user);
            sleep(rand(4,6));
        }
        
        $pageHTML = hQuery::fromHTML($driver->getPageSource()); 
        $patternUsernameDM = '#react-root > section > div.IEL5I > div > div.Igw0E.IwRSH.eGOV_.vwCYk._3wFWr > div:nth-child(1) > div';
        $getUsernameDM = $pageHTML->find($patternUsernameDM );
        if(count($getUsernameDM) > 0){
            $liSendDM = $driver->findElement(WebDriverBy::cssSelector($patternUsernameDM));
            if($liSendDM){ 
                $liSendDM->click();
                sleep(rand(4,6));
            }                                            
        }

        // next button 
        $nextBtn = $driver->findElement(WebDriverBy::cssSelector('#react-root > section > div:nth-child(1) > header > div > div.mXkkY.KDuQp > button'));
        if($nextBtn){ 
            $nextBtn->click();
            sleep(rand(4,6));
        }    
        
        $patternInputDMMessage = '#react-root > section > div.IEL5I > div > div > div.Igw0E.IwRSH.eGOV_._4EzTm > div > div > div > textarea';        	
        if(checkExistElement($driver->getPageSource(), $patternInputDMMessage)){
        	$inputDMMessage = $driver->findElement(WebDriverBy::cssSelector($patternInputDMMessage));
			if($inputDMMessage){ 
				$pesan = "Halo ".$user."... mohon maaf mengganggu waktunya, hanya sekedar ingin menginfokan ... \n";
			    $inputDMMessage->sendKeys($pesan);
			}         	
        }

        $patternBtnSend = '#react-root > section > div.IEL5I > div > div > div.Igw0E.IwRSH.eGOV_._4EzTm > div > div > div.Igw0E.IwRSH.eGOV_._4EzTm.JI_ht > button';
        if(checkExistElement($driver->getPageSource(), $patternBtnSend)){
        	$btnSendMessage = $driver->findElement(WebDriverBy::cssSelector($patternBtnSend));
			if($btnSendMessage){ 
			    $btnSendMessage->click();
			}         	
        }        

    }
} 