<?php
// filename: index.php
require 'system/System.php';

System::load(__DIR__ . '/app');
$app = new BlogApplication;
$app->run();


