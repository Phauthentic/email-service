<?php
require_once '../vendor/autoload.php';
require_once '../config/container.php';

use App\Application\Http\Application;

$container->get(Application::class)->run();
