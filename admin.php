<?php

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/Model/campaign.php';
require_once __DIR__ . '/Model/donation.php';
require_once __DIR__ . '/Model/user.php';
require_once __DIR__ . '/Controller/adminC.php';
require_once __DIR__ . '/Controller/userC.php';

$db = database();
$campaignModel = new CampaignModel($db);
$donationModel = new DonationModel($db);
$userController = new UserController(new UserModel($db));
$currentUser = $userController->currentUser();
$adminController = new AdminController($campaignModel);
$currentPage = 'admin';
$pageTitle = 'Back office · Smart City Connect';

if ($currentUser === null) {
    setMessage('error', 'Sign in with an administrator account to open the back office.');
    header('Location: index.php?page=login&next=admin');
    exit;
}
if ($currentUser['role'] !== 'admin') {
    setMessage('error', 'Your account does not have administrator access.');
    header('Location: index.php?page=profile');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!checkCsrfToken(isset($_POST['_csrf']) ? $_POST['_csrf'] : null)) {
        http_response_code(400);
        $message = 'Please reload the page and try again.';
        require __DIR__ . '/Views/notFound.php';
        exit;
    }

    $action = inputString($_POST, 'action');

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
        $id = inputId($_POST, 'campaign_id');

        if ($adminController->deleteCampaign($id)) {
            setMessage('notice', 'Campaign deleted.');
        } else {
            setMessage('error', 'Campaign could not be deleted.');
        }

        header('Location: admin.php#campaigns');
        exit;
    }

    if ($action === 'logout') {
        $userController->logout();
        header('Location: index.php');
        exit;
    }

    http_response_code(400);
    $message = 'This action is not available.';
    require __DIR__ . '/Views/notFound.php';
    exit;
}

$campaigns = $campaignModel->getAllCampaigns();
$donations = $donationModel->getRecentDonations();
$totals = $donationModel->getTotals();
$pageTitle = 'Campaign dashboard · Smart City Connect';

require __DIR__ . '/Views/adminDashboard.php';
