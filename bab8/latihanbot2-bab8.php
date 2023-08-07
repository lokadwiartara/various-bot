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

// Use the remote addr to locate where javaw is running
$host = 'http://localhost:4444'; // this is the default
 
// See all the capabilities here: https://github.com/SeleniumHQ/selenium/wiki/DesiredCapabilities
$prefs['profile']['default_content_setting_values']['notifications'] = 2;

$options = new ChromeOptions();

$options->addArguments(array('--start-maximized', "--disable-infobars", "--disable-extensions"));
$options->setExperimentalOption('prefs', $prefs);

$caps = DesiredCapabilities::chrome();

$caps->setCapability(ChromeOptions::CAPABILITY, $options);

$driver = RemoteWebDriver::create($host, $caps);

$driver->get("https://www.facebook.com");

$email = $driver->findElement(WebDriverBy::cssSelector('#email'));
if($email){
	$email->sendKeys('');
}

$password = $driver->findElement(WebDriverBy::cssSelector('#pass'));
if($password){
	$password->sendKeys('');
}

$login = $driver->findElement(WebDriverBy::cssSelector('#u_0_b'));
if($login){
	$login->click();
}

sleep(7);

$driver->get('https://www.facebook.com/search/people/?q=ciapus');

sleep(7);

$htmlPeopleList = $driver->getPageSource();

$htmlserp = hQuery::fromHTML($htmlPeopleList);

$getListLink = $htmlserp->find('div.nc684nl6 a.oajrlxb2');

if(count($getListLink) > 0){
	$x = 0;
	$linkfriends = array();
	foreach($getListLink as $data => $link){
		$linkHTML = hQuery::fromHTML($link);
		$linkText = $linkHTML->find('span');		
		// echo $x.'. '.$linkText. ' '.$link->attr('href')."\n";
		$nameoffriend[$x] = $linkText;
		$linkfriends[$x] = $link->attr('href');
		$x++;
	}
}

$x = 0;
foreach($linkfriends as $linkfriend){
	$driver->get($linkfriend);
	sleep(rand(7,15));

	echo "Meminta pertemanan ".$nameoffriend[$x]." : ...";
	$addFriend = $driver->findElement(WebDriverBy::cssSelector('* > .oajrlxb2.tdjehn4e.gcieejh5.bn081pho.aot14ch1.kzx2olss.cbu4d94t.taijpn5t.ni8dbmo4.stjgntxs.k4urcfbm.tv7at329'));
	if($addFriend){
		$addFriend->click();
		echo " selesai\n";
	}

	$x++;
}
