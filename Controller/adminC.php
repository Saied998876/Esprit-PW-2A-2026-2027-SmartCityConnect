<?php

class AdminController
{
    private $campaignModel;

    public function __construct($campaignModel)
    {
        $this->campaignModel = $campaignModel;
    }

    public function saveCampaign($form)
    {
        $errors = [];
        $id = inputId($form, 'id');
        $title = trim(inputString($form, 'title'));
        $description = trim(inputString($form, 'description'));
        $category = inputString($form, 'category');
        $goal = inputString($form, 'goal');
        $status = inputString($form, 'status');

        if ($title === '' || strlen($title) > 140) {
            $errors[] = 'Enter a campaign title using 140 characters or fewer.';
        }
        if ($description === '' || strlen($description) > 2000) {
            $errors[] = 'Enter a description using 2,000 characters or fewer.';
        }
        if (!in_array($category, ['green', 'energy', 'community'], true)) {
            $errors[] = 'Choose a campaign category.';
        }
        if (!is_numeric($goal) || $goal <= 0 || $goal > 10000000) {
            $errors[] = 'Enter a funding goal greater than zero and no more than 10,000,000.';
        }
        if (!in_array($status, ['active', 'closed'], true)) {
            $errors[] = 'Choose a campaign status.';
        }
        if ($id > 0 && !$this->campaignModel->campaignExists($id)) {
            $errors[] = 'This campaign could not be found.';
        }

        if (count($errors) > 0) {
            return $errors;
        }

        $campaign = [
            'id' => $id,
            'title' => $title,
            'description' => $description,
            'category' => $category,
            'goal_cents' => (int) round($goal * 100),
            'status' => $status,
        ];

        $this->campaignModel->saveCampaign($campaign);

        return [];
    }

    public function deleteCampaign($id)
    {
        $id = (int) $id;
        if ($id < 1) {
            return false;
        }

        return $this->campaignModel->deleteCampaign($id);
    }
}
