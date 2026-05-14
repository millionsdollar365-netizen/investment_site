<?php
/**
 * PRIMEAXIS INVESTMENT PLATFORM
 * Bootstrap: load environment config from /config, then database.
 *
 * Production: copy config/config.example.php to config/config.php and tune.
 * This file is safe to commit; secrets live in .env and config/config.php (gitignored).
 */

$config_dir = dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR;
if (is_file($config_dir . 'config.php')) {
    require_once $config_dir . 'config.php';
} else {
    require_once $config_dir . 'config.example.php';
}

require_once __DIR__ . '/db.php';
require_once __DIR__ . '/functions.php';
