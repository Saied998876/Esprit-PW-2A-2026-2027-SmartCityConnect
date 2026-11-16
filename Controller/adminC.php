<?php

class AdminController
{
    private $campaignModel;
    private $donationModel;

    public function __construct($campaignModel, $donationModel)
    {
        $this->campaignModel = $campaignModel;
        $this->donationModel = $donationModel;
    }

    public function isLoggedIn()
    {
        return isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true;
    }

    public function login($password)
    {
        $correctPassword = getenv('ADMIN_PASSWORD');
        if (!$correctPassword) {
            $correctPassword = 'admin123';
        }

        if (!hash_equals($correctPassword, $password)) {
            return false;
        }

        session_regenerate_id(true);
        $_SESSION['is_admin'] = true;

        return true;
    }

    public function logout()
    {
        unset($_SESSION['is_admin']);
        session_regenerate_id(true);
    }

    public function saveCampaign($form)
    {
        $errors = [];
        $id = isset($form['id']) && $form['id'] !== '' ? (int) $form['id'] : 0;
        $title = trim(isset($form['title']) ? (string) $form['title'] : '');
        $description = trim(isset($form['description']) ? (string) $form['description'] : '');
        $category = isset($form['category']) ? $form['category'] : '';
        $goal = isset($form['goal']) ? $form['goal'] : '';
        $status = isset($form['status']) ? $form['status'] : 'active';

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
