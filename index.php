<?php

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/Model/campaign.php';
require_once __DIR__ . '/Model/donation.php';
require_once __DIR__ . '/Controller/campaignC.php';
require_once __DIR__ . '/Controller/donationC.php';

$db = database();
$campaignModel = new CampaignModel($db);
$donationModel = new DonationModel($db);
$campaignController = new CampaignController($campaignModel);
$donationController = new DonationController($campaignModel, $donationModel);

$page = isset($_GET['page']) ? $_GET['page'] : 'campaigns';
$currentPage = 'campaigns';
$errors = [];
$oldInput = [];

if ($page === 'campaign') {
    $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
    $campaign = $campaignController->show($id);

    if ($campaign === null) {
        http_response_code(404);
        $pageTitle = 'Campaign not found';
        $message = 'This campaign could not be found.';
        require __DIR__ . '/Views/notFound.php';
        exit;
    }

    $pageTitle = $campaign['title'] . ' · Smart City Connect';
    require __DIR__ . '/Views/campaignDetail.php';
    exit;
}

if ($page === 'donate') {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: index.php');
        exit;
    }

    if (!checkCsrfToken(isset($_POST['_csrf']) ? $_POST['_csrf'] : null)) {
        http_response_code(400);
        $pageTitle = 'Invalid request';
        $message = 'Please go back and submit the form again.';
        require __DIR__ . '/Views/notFound.php';
        exit;
    }

    $result = $donationController->store($_POST);
    if ($result['id'] !== null) {
        header('Location: index.php?page=success&id=' . $result['id']);
        exit;
    }

    $id = isset($_POST['campaign_id']) ? (int) $_POST['campaign_id'] : 0;
    $campaign = $campaignController->show($id);
    if ($campaign === null) {
        http_response_code(404);
        $pageTitle = 'Campaign not found';
        $message = 'This campaign could not be found.';
        require __DIR__ . '/Views/notFound.php';
        exit;
    }

    http_response_code(422);
    $errors = $result['errors'];
    $oldInput = $_POST;
    $pageTitle = 'Support ' . $campaign['title'] . ' · Smart City Connect';
    require __DIR__ . '/Views/campaignDetail.php';
    exit;
}

if ($page === 'success') {
    $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
    $donation = $donationModel->getDonation($id);

    if ($donation === null) {
        http_response_code(404);
        $pageTitle = 'Donation not found';
        $message = 'This donation could not be found.';
        require __DIR__ . '/Views/notFound.php';
        exit;
    }

    $pageTitle = 'Thank you · Smart City Connect';
    require __DIR__ . '/Views/donationSuccess.php';
    exit;
}

$campaigns = $campaignController->index();
$pageTitle = 'Community campaigns · Smart City Connect';
require __DIR__ . '/Views/index.php';
