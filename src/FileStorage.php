<?php

namespace App;

class FileStorage {
    private string $dataDir;
    private string $templatesDir;

    public function __construct() {
        $this->dataDir = DATA_DIR;
        $this->templatesDir = TEMPLATES_DIR;
    }

    public function saveInvoiceData(array $data): string {
        $invoiceNumber = $this->generateInvoiceNumber();
        $data['invoice_number'] = $invoiceNumber;
        $data['date'] = date('Y-m-d');

        // Save JSON data
        $jsonFile = $this->dataDir . $invoiceNumber . '.json';
        file_put_contents($jsonFile, json_encode($data, JSON_PRETTY_PRINT));

        // Generate HTML invoice
        $htmlContent = $this->generateHtmlInvoice($data);
        $htmlFile = $this->dataDir . $invoiceNumber . '.html';
        file_put_contents($htmlFile, $htmlContent);

        return $htmlFile;
    }

    private function generateInvoiceNumber(): string {
        return 'INV-' . date('Ymd') . '-' . sprintf('%04d', rand(1, 9999));
    }

    private function generateHtmlInvoice(array $data): string {
        $template = file_get_contents($this->templatesDir . 'invoice.html');
        
        $replacements = [
            '{{INVOICE_NUMBER}}' => $data['invoice_number'],
            '{{DATE}}' => $data['date'],
            '{{COMPANY_NAME}}' => COMPANY_NAME,
            '{{CUSTOMER_NAME}}' => $data['name'],
            '{{CUSTOMER_LASTNAME}}' => $data['lastname'],
            '{{CUSTOMER_ADDRESS}}' => $data['address'],
            '{{AMOUNT}}' => '$' . number_format($data['amount'], 2)
        ];

        return str_replace(
            array_keys($replacements),
            array_values($replacements),
            $template
        );
    }

    public function getInvoicePath(string $invoiceNumber): ?string {
        $htmlFile = $this->dataDir . $invoiceNumber . '.html';
        return file_exists($htmlFile) ? $htmlFile : null;
    }
}
