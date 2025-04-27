# Telegram Invoice Bot

A Telegram bot for Jaxxy that creates invoices based on user input. The bot collects customer name, last name, address, and amount to generate professional HTML invoices.

## Features

- Interactive invoice creation through Telegram
- Professional HTML invoice generation
- Automatic invoice numbering
- Data storage in JSON format
- Clean and responsive invoice design

## Setup Instructions

1. Install dependencies:
```bash
composer install
```

2. Set up webhook:
Visit the following URL in your browser (replace with your domain):
```
https://api.telegram.org/bot7881415407:AAGIdmTKBBeeMjA_lI9DCxq6l-VNIuXxg0o/setWebhook?url=https://your-domain.com/webhook.php
```

3. Ensure the following directories are writable:
- `data/invoices/`
- `templates/`

## Usage

1. Start a chat with the bot on Telegram
2. Send `/start` to begin creating an invoice
3. Follow the bot's prompts to enter:
   - Customer's first name
   - Customer's last name
   - Customer's address
   - Invoice amount

The bot will generate and send back an HTML invoice file.

## Directory Structure

```
telegram-invoice-bot/
├── config/
│   └── config.php         # Configuration file
├── data/
│   └── invoices/          # Generated invoices storage
├── templates/
│   └── invoice.html       # Invoice HTML template
├── src/
│   ├── Bot.php           # Main bot logic
│   └── FileStorage.php    # File storage handling
├── webhook.php           # Webhook entry point
├── composer.json         # Dependencies
└── README.md            # Documentation
```

## Security Notes

- Ensure your webhook is using HTTPS
- Set proper file permissions on the data directory
- Validate all user inputs
- Use rate limiting in production

## Requirements

- PHP 7.4 or higher
- SSL certificate for webhook
- Composer
