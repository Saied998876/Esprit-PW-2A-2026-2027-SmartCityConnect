<?php

class DonationController
{
    private $campaignModel;
    private $donationModel;

    public function __construct($campaignModel, $donationModel)
    {
        $this->campaignModel = $campaignModel;
        $this->donationModel = $donationModel;
    }

    public function store($form)
    {
        $errors = [];
        $campaignId = isset($form['campaign_id']) ? (int) $form['campaign_id'] : 0;
        $name = trim(isset($form['donor_name']) ? (string) $form['donor_name'] : '');
        $email = trim(isset($form['donor_email']) ? (string) $form['donor_email'] : '');
        $amount = isset($form['amount']) ? (string) $form['amount'] : '';
        $message = trim(isset($form['message']) ? (string) $form['message'] : '');

        if ($this->campaignModel->getCampaign($campaignId) === null) {
            $errors[] = 'Choose a campaign that is still accepting donations.';
        }

        if ($name === '' || strlen($name) > 120) {
            $errors[] = 'Enter your name using 120 characters or fewer.';
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 254) {
            $errors[] = 'Please enter a valid email address.';
        }

        if (!is_numeric($amount) || $amount <= 0 || $amount > 1000000
            || !preg_match('/^\d+(\.\d{1,2})?$/', $amount)) {
            $errors[] = 'Enter an amount greater than zero and no more than 1,000,000.';
        }

        if (strlen($message) > 500) {
            $errors[] = 'Keep your note to 500 characters or fewer.';
        }

        if (count($errors) > 0) {
            return ['id' => null, 'errors' => $errors];
        }

        $donation = [
            'campaign_id' => $campaignId,
            'donor_name' => $name,
            'donor_email' => $email,
            'amount_cents' => (int) round($amount * 100),
            'message' => $message,
        ];

        return [
            'id' => $this->donationModel->addDonation($donation),
            'errors' => [],
        ];
    }
}
