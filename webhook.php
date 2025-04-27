<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/config/config.php';

use App\Bot;

try {
    // Get the incoming update from Telegram
    $update = json_decode(file_get_contents('php://input'), true);
    
    // Initialize and run the bot
    $bot = new Bot();
    $bot->handleUpdate($update);

} catch (Exception $e) {
    // Log error (in a production environment, you should use proper error logging)
    error_log("Telegram Bot Error: " . $e->getMessage());
}
