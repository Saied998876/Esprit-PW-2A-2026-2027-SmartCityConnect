<?php

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/Model/campaign.php';
require_once __DIR__ . '/Model/donation.php';
require_once __DIR__ . '/Controller/adminC.php';

$db = database();
$campaignModel = new CampaignModel($db);
$donationModel = new DonationModel($db);
$adminController = new AdminController($campaignModel, $donationModel);
$currentPage = 'admin';
$pageTitle = 'Back office · Smart City Connect';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!checkCsrfToken(isset($_POST['_csrf']) ? $_POST['_csrf'] : null)) {
        http_response_code(400);
        $message = 'Please reload the page and try again.';
        require __DIR__ . '/Views/notFound.php';
        exit;
    }

    $action = isset($_POST['action']) ? $_POST['action'] : '';

    if ($action === 'login') {
        if ($adminController->login(isset($_POST['password']) ? $_POST['password'] : '')) {
            header('Location: admin.php');
            exit;
        }

        $loginError = 'That password did not match. Please try again.';
        require __DIR__ . '/Views/adminLogin.php';
        exit;
    }

    if (!$adminController->isLoggedIn()) {
        http_response_code(403);
        header('Location: admin.php');
        exit;
    }

    if ($action === 'logout') {
        $adminController->logout();
        header('Location: admin.php');
        exit;
    }

    if ($action === 'save_campaign') {
        $errors = $adminController->saveCampaign($_POST);

        if (count($errors) === 0) {
            setMessage('notice', 'Campaign saved.');
        } else {
            setMessage('error', implode(' ', $errors));
        }

        header('Location: admin.php#campaigns');
        exit;
    }

    if ($action === 'delete_campaign') {
        $id = isset($_POST['campaign_id']) ? $_POST['campaign_id'] : '';

        if ($adminController->deleteCampaign($id)) {
            setMessage('notice', 'Campaign deleted.');
        } else {
            setMessage('error', 'Campaign could not be deleted.');
        }

        header('Location: admin.php#campaigns');
        exit;
    }

    http_response_code(400);
    $message = 'This action is not available.';
    require __DIR__ . '/Views/notFound.php';
    exit;
}

if (!$adminController->isLoggedIn()) {
    require __DIR__ . '/Views/adminLogin.php';
    exit;
}

$campaigns = $campaignModel->getAllCampaigns();
$donations = $donationModel->getRecentDonations();
$totals = $donationModel->getTotals();
$pageTitle = 'Campaign dashboard · Smart City Connect';

require __DIR__ . '/Views/adminDashboard.php';
