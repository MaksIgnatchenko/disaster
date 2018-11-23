<?php
/**
 * Created by Maksym Ignatchenko, Appus Studio LP on 10.10.2018
 *
 */

namespace App\Services;

use ReceiptValidator\iTunes\PendingRenewalInfo;
use ReceiptValidator\iTunes\Validator as iTunesValidator;

class ReceiptValidator
{
//    private $validator;

//    public function __construct($receiptBase64Data, $sharedSecret)
//    {
//        $this->validator = new iTunesValidator(iTunesValidator::ENDPOINT_SANDBOX);
//        $this->validator->setReceiptData($receiptBase64Data);
//        $this->validator->setSharedSecret($sharedSecret);
//    }
//
//    public function getStatus()
//    {
//        $pendingRenewalInfo = new PendingRenewalInfo($this->validator->getRawData());
//        return $pendingRenewalInfo->getStatus();
//    }

    public function __construct($receiptBase64Data = null, $sharedSecret = null)
    {
    }

    public function getStatus()
    {
        $const = [
            'active',
            'pending',
            'expired'
        ];
        return array_rand($const);
    }
}