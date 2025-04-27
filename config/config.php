<?php

define('BOT_TOKEN', '7881415407:AAGIdmTKBBeeMjA_lI9DCxq6l-VNIuXxg0o');
define('COMPANY_NAME', 'Jaxxy');
define('DATA_DIR', __DIR__ . '/../data/invoices/');
define('TEMPLATES_DIR', __DIR__ . '/../templates/');

// Create required directories if they don't exist
$directories = [DATA_DIR, TEMPLATES_DIR];
foreach ($directories as $dir) {
    if (!file_exists($dir)) {
        mkdir($dir, 0777, true);
    }
}
