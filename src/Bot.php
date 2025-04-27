<?php

namespace App;

use TelegramBot\Api\BotApi;
use TelegramBot\Api\Types\Message;

class Bot {
    private BotApi $telegram;
    private FileStorage $storage;
    private array $userStates = [];
    private array $userData = [];

    public function __construct() {
        $this->telegram = new BotApi(BOT_TOKEN);
        $this->storage = new FileStorage();
    }

    public function handleUpdate(array $update): void {
        if (!isset($update['message'])) {
            return;
        }

        $message = $update['message'];
        $chatId = $message['chat']['id'];
        $text = $message['text'] ?? '';

        if ($text === '/start') {
            $this->startInvoiceCreation($chatId);
            return;
        }

        if (isset($this->userStates[$chatId])) {
            $this->handleUserInput($chatId, $text);
        } else {
            $this->telegram->sendMessage($chatId, "Please use /start to create a new invoice.");
        }
    }

    private function startInvoiceCreation(int $chatId): void {
        $this->userStates[$chatId] = 'awaiting_name';
        $this->userData[$chatId] = [];
        $this->telegram->sendMessage($chatId, "Let's create an invoice! Please enter the customer's first name:");
    }

    private function handleUserInput(int $chatId, string $text): void {
        switch ($this->userStates[$chatId]) {
            case 'awaiting_name':
                $this->userData[$chatId]['name'] = $text;
                $this->userStates[$chatId] = 'awaiting_lastname';
                $this->telegram->sendMessage($chatId, "Great! Now enter the customer's last name:");
                break;

            case 'awaiting_lastname':
                $this->userData[$chatId]['lastname'] = $text;
                $this->userStates[$chatId] = 'awaiting_address';
                $this->telegram->sendMessage($chatId, "Please enter the customer's address:");
                break;

            case 'awaiting_address':
                $this->userData[$chatId]['address'] = $text;
                $this->userStates[$chatId] = 'awaiting_amount';
                $this->telegram->sendMessage($chatId, "Finally, enter the invoice amount (numbers only):");
                break;

            case 'awaiting_amount':
                if (!is_numeric($text)) {
                    $this->telegram->sendMessage($chatId, "Please enter a valid number for the amount:");
                    return;
                }

                $this->userData[$chatId]['amount'] = floatval($text);
                $this->generateInvoice($chatId);
                break;
        }
    }

    private function generateInvoice(int $chatId): void {
        try {
            $invoiceFile = $this->storage->saveInvoiceData($this->userData[$chatId]);
            
            // Send the invoice file
            $this->telegram->sendDocument($chatId, new \CURLFile($invoiceFile));
            $this->telegram->sendMessage($chatId, "✅ Invoice has been generated successfully! Use /start to create another invoice.");

            // Clean up user state
            unset($this->userStates[$chatId]);
            unset($this->userData[$chatId]);

        } catch (\Exception $e) {
            $this->telegram->sendMessage($chatId, "Sorry, there was an error generating the invoice. Please try again with /start");
            // Clean up user state
            unset($this->userStates[$chatId]);
            unset($this->userData[$chatId]);
        }
    }
}
