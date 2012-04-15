<?php

use Nette\Application\Routers\Route;


// Load libraries
require __DIR__ . '/app/libs/nette.min.php';
require __DIR__ . '/app/libs/feed.class.php';
require __DIR__ . '/app/libs/twitter.class.php';
require __DIR__ . '/app/libs/texy.min.php';


$configurator = new Nette\Config\Configurator;

// Enable Nette Debugger for error visualisation & logging
$configurator->enableDebugger(__DIR__ . '/app/log');

// Configure libraries
Twitter::$cacheDir = Feed::$cacheDir = __DIR__ . '/app/temp/cache';
$configurator->setTempDirectory(__DIR__ . '/app/temp');

// Create Dependency Injection container from config.neon file
$configurator->addConfig(__DIR__ . '/app/config.neon');
$container = $configurator->createContainer();


// Setup routes
$container->router[] = new Route('', function($presenter) use ($container) {

	// create template
	$template = $presenter->createTemplate()
		->setFile(__DIR__ . '/app/index.latte');

	// register template helpers like {$foo|date}
	$template->registerHelper('date', function($date) use ($lang) {
		if ($lang === 'en') {
			return date('F j, Y', (int) $date);
		}
	});

	$template->registerHelper('tweet', function($s) {
		return Twitter::clickable($s);
	});

	$template->registerHelper('rss', function($path) {
		return Feed::loadRss($path);
	});

	$template->registerHelper('texy', array(new Texy, 'process'));
	return $template;
});


// Run the application!
$container->application->run();
