<?php

require_once __DIR__ . '/../libs/autoload.php';

$configurator = new Nette\Config\Configurator;

// $configurator->setDebugMode('127.0.0.1');
$configurator->enableDebugger( __DIR__ . '/../log' );
$configurator->setTempDirectory( __DIR__ . '/../temp' );

$configurator->createRobotLoader()
		->addDirectory( __DIR__ )
		->register();

$configurator->addConfig( __DIR__ . '/config/config.neon', $configurator::NONE )
		->addConfig( __DIR__ . '/config/config.local.neon', $configurator::NONE );

return $configurator->createContainer();
