<?php

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/Model/campaign.php';
require_once __DIR__ . '/Model/donation.php';
require_once __DIR__ . '/Model/user.php';
require_once __DIR__ . '/Controller/campaignC.php';
require_once __DIR__ . '/Controller/donationC.php';
require_once __DIR__ . '/Controller/userC.php';

$db = database();
$campaignModel = new CampaignModel($db);
$donationModel = new DonationModel($db);
$userController = new UserController(new UserModel($db));
$currentUser = $userController->currentUser();
$campaignController = new CampaignController($campaignModel);
$donationController = new DonationController($campaignModel, $donationModel);

$page = inputString($_GET, 'page');
if ($page === '') {
    $page = 'campaigns';
}
$currentPage = 'campaigns';
$errors = [];
$oldInput = [];

if ($page === 'login') {
    $currentPage = 'login';
    $next = inputString($_REQUEST, 'next') === 'admin' ? 'admin' : '';

    if ($currentUser !== null) {
        header('Location: ' . ($currentUser['role'] === 'admin' ? 'admin.php' : 'index.php?page=profile'));
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!checkCsrfToken(isset($_POST['_csrf']) ? $_POST['_csrf'] : null)) {
            http_response_code(400);
            $pageTitle = 'Invalid request';
            $message = 'Please reload the page and try again.';
            require __DIR__ . '/Views/notFound.php';
            exit;
        }

        $email = inputString($_POST, 'email');
        $password = inputString($_POST, 'password');
        if ($userController->authenticate($email, $password)) {
            $currentUser = $userController->currentUser();
            if ($next === 'admin' && $currentUser['role'] !== 'admin') {
                $userController->logout();
                setMessage('error', 'Your account does not have administrator access.');
                header('Location: index.php?page=login&next=admin');
                exit;
            }

            header('Location: ' . ($currentUser['role'] === 'admin'
                ? 'admin.php'
                : 'index.php?page=profile'));
            exit;
        }

        http_response_code(422);
        $loginError = 'The email or password is incorrect.';
        $oldInput['email'] = $email;
    } elseif ($_SERVER['REQUEST_METHOD'] !== 'GET') {
        http_response_code(405);
        header('Allow: GET, POST');
        exit;
    }

    $pageTitle = 'Sign in · Smart City Connect';
    require __DIR__ . '/Views/userLogin.php';
    exit;
}

if ($page === 'register') {
    $currentPage = 'register';
    if ($currentUser !== null) {
        header('Location: index.php?page=profile');
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!checkCsrfToken(isset($_POST['_csrf']) ? $_POST['_csrf'] : null)) {
            http_response_code(400);
            $pageTitle = 'Invalid request';
            $message = 'Please reload the page and try again.';
            require __DIR__ . '/Views/notFound.php';
            exit;
        }

        $errors = $userController->register($_POST);
        $oldInput = $_POST;
        if ($errors === []) {
            setMessage('notice', 'Your account is ready. Sign in to continue.');
            header('Location: index.php?page=login');
            exit;
        }
        http_response_code(422);
    } elseif ($_SERVER['REQUEST_METHOD'] !== 'GET') {
        http_response_code(405);
        header('Allow: GET, POST');
        exit;
    }

    $pageTitle = 'Create an account · Smart City Connect';
    require __DIR__ . '/Views/userRegister.php';
    exit;
}

if ($page === 'profile') {
    $currentPage = 'profile';
    if ($currentUser === null) {
        header('Location: index.php?page=login');
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!checkCsrfToken(isset($_POST['_csrf']) ? $_POST['_csrf'] : null)) {
            http_response_code(400);
            $pageTitle = 'Invalid request';
            $message = 'Please reload the page and try again.';
            require __DIR__ . '/Views/notFound.php';
            exit;
        }

        $errors = $userController->updateProfile((int) $currentUser['id'], $_POST);
        $oldInput = $_POST;
        if ($errors === []) {
            setMessage('notice', 'Your profile has been updated.');
            header('Location: index.php?page=profile');
            exit;
        }
        http_response_code(422);
    } elseif ($_SERVER['REQUEST_METHOD'] !== 'GET') {
        http_response_code(405);
        header('Allow: GET, POST');
        exit;
    }

    $currentUser = $userController->currentUser();
    $pageTitle = 'My account · Smart City Connect';
    require __DIR__ . '/Views/userProfile.php';
    exit;
}

if ($page === 'logout') {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        header('Allow: POST');
        exit;
    }
    if (!checkCsrfToken(isset($_POST['_csrf']) ? $_POST['_csrf'] : null)) {
        http_response_code(400);
        $pageTitle = 'Invalid request';
        $message = 'Please reload the page and try again.';
        require __DIR__ . '/Views/notFound.php';
        exit;
    }

    $userController->logout();
    header('Location: index.php');
    exit;
}

if ($page === 'campaign') {
    $id = inputId($_GET, 'id');
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

    $id = inputId($_POST, 'campaign_id');
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
    $id = inputId($_GET, 'id');
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
