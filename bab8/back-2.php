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

	$host = 'http://localhost:4444';

	$prefs['profile']['default_content_setting_values']['notifications'] = 2;

	$options = new ChromeOptions();
	// https://php-webdriver.github.io/php-webdriver/latest/Facebook/WebDriver/Chrome/ChromeOptions.html

	$options->addArguments(['--start-maximized']);
	$options->setExperimentalOption('prefs', $prefs);
	// https://peter.sh/experiments/chromium-command-line-switches/

	$caps = DesiredCapabilities::chrome();

	$caps->setCapability(ChromeOptions::CAPABILITY, $options);

	$driver = RemoteWebDriver::create($host, $caps);

	$driver->get('https://www.facebook.com');

	$email = $driver->findElement(WebDriverBy::cssSelector('#email'));

	if($email){
		$email->sendKeys('ilmuwebsiteid@gmail.com');
	}

	$password = $driver->findElement(WebDriverBy::cssSelector('#pass'));

	if($password){
		$password->sendKeys('10j8YY,(!k4T09O#3@-__"*#)#!$j8YY,90p@');
	}

	$login = $driver->findElement(WebDriverBy::cssSelector('#u_0_b'));

	if($login){
		$login->click();
	}

	sleep(rand(3,5));

	// 
	// div.nc684nl6 a.oajrlxb2
	$driver->get('https://www.facebook.com/search/people?q=ciapus');

	sleep(rand(5,7));

	$htmlPeopleList = $driver->getPageSource();

	$htmlserp = hQuery::fromHTML($htmlPeopleList);

	$getListLink = $htmlserp->find('div.nc684nl6 a.oajrlxb2');

	// echo 'sebelum di scroll '.count($getListLink)."\n";

	// div.j83agx80 div.dp1hu0rb div.k4urcfbm div.fjf4s8hc

	$scrollPeople = $driver->findElement(WebDriverBy::cssSelector('body'));

	if($scrollPeople){

		$scrollPeople->sendKeys(WebDriverKeys::PAGE_DOWN);
		sleep(3);

		$scrollPeople->sendKeys(WebDriverKeys::PAGE_DOWN);
		sleep(3);

		$scrollPeople->sendKeys(WebDriverKeys::PAGE_DOWN);
		sleep(3);
		// https://github.com/php-webdriver/php-webdriver/blob/main/lib/WebDriverKeys.php
	}

	$htmlPeopleList = $driver->getPageSource();

	$htmlserp = hQuery::fromHTML($htmlPeopleList);

	$getListLink = $htmlserp->find('div.nc684nl6 a.oajrlxb2');

	// echo 'sesudah di scroll '.count($getListLink);

	if(count($getListLink) > 0){
		$x = 0;
		$linkFriends = [];
		$nameOfFriends = [];
		foreach($getListLink as $data => $link){
			$linkFriends[$x] = $link->attr('href');

			$linkHTML = hQuery::fromHTML($link);
			$linkText = $linkHTML->find('span');
			$nameOfFriends[$x] = $linkText->text();

			$x++;
		}

		// print_r($linkFriends);
		// print_r($nameOfFriends);
	}

	$x = 0;
	foreach($linkFriends as $linkFriend){
		$driver->get($linkFriend);
		sleep(rand(7,10));

		echo "Meminta pertemanan ".$nameOfFriends[$x]."...\n";
		$addFriend = $driver->findElement(WebDriverBy::cssSelector('* > div.oajrlxb2.s1i5eluu.gcieejh5.bn081pho.humdl8nn.izx4hr6d.rq0escxv.nhd2j8a9.j83agx80.p7hjln8o.kvgmc6g5.cxmmr5t8.oygrvhab.hcukyx3x.jb3vyjys.d1544ag0.qt6c0cv9.tw6a2znq.i1ao9s8h.esuyzwwr.f1sip0of.lzcic4wl.l9j0dhe7.abiwlrkh.p8dawk7l.beltcj47.p86d2i9g.aot14ch1.kzx2olss.cbu4d94t.taijpn5t.ni8dbmo4.stjgntxs.k4urcfbm.tv7at329'));

		if($addFriend){
			$addFriend->click();
		}

		$x++;
	}


























