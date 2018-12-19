<?php
/**
 * Created by Maksym Ignatchenko, Appus Studio LP on 26.11.2018
 *
 */

namespace App\Services;

use Illuminate\Support\Facades\Log;
use ReceiptValidator\iTunes\PendingRenewalInfo;
use ReceiptValidator\iTunes\Validator as iTunesValidator;
use ReceiptValidator\RunTimeException;

class ReceiptValidator
{
    /**
     * @var iTunesValidator
     */
    private $validator;

    /**
     * ReceiptValidator constructor.
     * @param $receiptBase64Data
     * @param $sharedSecret
     */
    public function __construct($receiptBase64Data, $sharedSecret = null)
    {
        $this->validator = new iTunesValidator(iTunesValidator::ENDPOINT_SANDBOX);
        $this->validator->setReceiptData($receiptBase64Data);
        $this->validator->setSharedSecret($sharedSecret);
    }

    /**
     * Get current status of user's renewable subscription.
     *
     * @return string|null
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getExpiresDate()
    {
        try {
            $response = $this->validator
                    ->validate()
                    ->getRawData();
        } catch (\Exception $e) {
            Log::error('Unable to get response from itunes server');
            return null;
        }
        return $response;
//        return $response['expires_date'] ?? null;
    }
}