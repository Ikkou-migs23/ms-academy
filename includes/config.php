<?php

require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

// URLs do projeto
define('BASE_URL', '/ms-academy/');
define('ADMIN_URL', BASE_URL . 'admin/');