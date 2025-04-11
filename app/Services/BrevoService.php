<?php

namespace App\Services;

use SendinBlue\Client\Configuration;
use SendinBlue\Client\Api\TransactionalEmailsApi;
use GuzzleHttp\Client;
use SendinBlue\Client\Model\SendSmtpEmail;

class BrevoService
{
    protected $apiInstance;

    public function __construct()
    {
        $BrevoKey = getenv('BRAVEO_API_KEY');
        $config = Configuration::getDefaultConfiguration()->setApiKey('api-key',$BrevoKey);
        $this->apiInstance = new TransactionalEmailsApi(new Client(), $config);
    }

    public function sendEmail($toEmail, $toName, $subject, $content)
    {
        $sendSmtpEmail = new SendSmtpEmail([
            'subject' => $subject,
            'htmlContent' => $content,
            'sender' => ['name' => 'Uplifty App', 'email' => 'upliftyapp@gmail.com'],
            'to' => [['email' => $toEmail, 'name' => $toName]]
        ]);

        try {
            // Send the email
            $result = $this->apiInstance->sendTransacEmail($sendSmtpEmail);

            // Check if the result contains a messageId
            if (isset($result['messageId'])) {
                // Email sent successfully
                return ['status' => 'success', 'messageId' => $result['messageId']];
            } else {
                // Response doesn't contain a messageId, assume failure
                return ['status' => 'error', 'message' => 'Email not sent.'];
            }
        } catch (\Exception $e) {
            // Catch and return any exceptions that occur during the API call
            return ['status' => 'error', 'message' => 'Exception when sending email: ' . $e->getMessage()];
        }
    }

}
