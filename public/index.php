<?php
use App\Core\App;

require_once __DIR__ . '/../vendor/autoload.php';

try {
    $app = App::appInstance();
    $app->run();
} catch (ReflectionException $e) {
    echo $e->getMessage();
}
