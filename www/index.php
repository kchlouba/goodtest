<?php

declare(strict_types=1);

header("Access-Control-Allow-Origin: *");

require __DIR__ . '/../vendor/autoload.php';

App\Bootstrap::boot()
	->createContainer()
	->getByType(Nette\Application\Application::class)
	->run();
