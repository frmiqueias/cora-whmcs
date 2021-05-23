<?php

// Require libraries needed for gateway module functions.
require_once __DIR__ . '/../../../init.php';
App::load_function('gateway');
App::load_function('invoice');

require_once('coraConfigurations.php');

try {
    (new CoraWebhook(getallheaders()))->init();
} catch (\Exception $ex) {
    echo '[' . $ex->getCode() . '] Message: ' . $ex->getMessage();
    exit;
}

class CoraWebhook
{
    private $domainBase = CORA_API_DOMAIN_BASE;
    private $headers = [];
    private $gatewayParams = [];
    private $gatewayModuleName = '';

    public function __construct(array $headers)
    {
        $this->gatewayModuleName = basename(__FILE__, '.php');
        $this->headers = $headers;
        $this->gatewayParams = getGatewayVariables($this->gatewayModuleName);
        if (!$this->gatewayParams['type']) {
            throw new \Exception("Module Not Activated");
        }
        $invoiceId = checkCbInvoiceID(
            $this->headers['webhook-resource-code'],
            $this->gatewayParams['name']
        );
        if (!$invoiceId) {
            throw new \Exception("Failed to check invoice");
        }
        if (!isset($this->headers['webhook-resource-id']) || !$this->headers['webhook-resource-id']) {
            throw new \Exception("Cora invoice ID not found");
        }
        
    }

    public function init()
    {
        $response = $this->clientPost(['divoxKey' => $this->gatewayParams['cora_license_key']]);
        if(!isset($response->status) || !$response->status){
            throw new \Exception("Status not found");
        }
        logTransaction($this->gatewayParams['name'], $response, $response->status);
        checkCbTransID($response->id);
        if($response->status == 'PAID'){
            addInvoicePayment(
                $this->headers['webhook-resource-code'],
                $response->id,
                $response->total_paid,
                0,
                $this->gatewayModuleName
            );
        }
        return $response;
    }

    private function clientPost($post = [])
    {
        if (!function_exists('curl_init')) {
            throw new \Exception("Curl function not found");
        }
        $url = $this->domainBase . '/api/cora/invoices/view/' . $this->headers['webhook-resource-id'] . '.json';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if (curl_errno($ch)) {
            throw new \Exception('Error:' . curl_error($ch));
        }
        if ($httpcode != 200) {
            throw new \Exception('Error:', $httpcode);
        }
        curl_close($ch);
        return $result ? json_decode($result) : $result;
    }
}