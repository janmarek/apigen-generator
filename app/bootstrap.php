<?php

/**
 * My Application bootstrap file.
 */
use Nette\Application\Routers\Route;


// Load Nette Framework
require LIBS_DIR . '/Nette/loader.php';

define('REPOS_DIR',          realpath(APP_DIR . '/../repos'));
define('DOC_PROCESSING_DIR', realpath(APP_DIR . '/../doc-progress'));
define('DOC_FINAL_DIR',      realpath(APP_DIR . '/../doc-final'));


// Configure application
$configurator = new Nette\Config\Configurator;

if(file_exists(__DIR__ . '/config/environment.php')) {
	require_once __DIR__ . '/config/environment.php';
}

// Enable Nette Debugger for error visualisation & logging
//$configurator->setProductionMode($configurator::AUTO);
$configurator->enableDebugger(__DIR__ . '/../log');

// Enable RobotLoader - this will load all classes automatically
$configurator->setTempDirectory(__DIR__ . '/../temp');
$configurator->createRobotLoader()
	->addDirectory(APP_DIR)
	->addDirectory(LIBS_DIR)
	->register();

// Create Dependency Injection container from config.neon file
$configurator
	->addConfig(__DIR__ . '/config/config.neon', defined('ENVIRONMENT_NAME') ? ENVIRONMENT_NAME : null)
	->addConfig(__DIR__ . '/config/config.local.neon', defined('ENVIRONMENT_NAME') ? ENVIRONMENT_NAME : null);
$container = $configurator->createContainer();

// Setup router
$container->router[] = new \Nette\Application\Routers\CliRouter;
$container->router[] = new Route('index.php', 'Homepage:default', Route::ONE_WAY);
$container->router[] = new Route('<presenter>/<action>[/<id>]', 'Homepage:default');


// Configure and run the application!
$container->application->run();
