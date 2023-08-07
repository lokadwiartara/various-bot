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
$host = 'http://localhost:4444/wd/hub'; // this is the default
 
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
	$password->sendKeys('...');
}

$login = $driver->findElement(WebDriverBy::cssSelector('button[data-testid="royal_login_button"]'));
if($login){
	$login->click();
}