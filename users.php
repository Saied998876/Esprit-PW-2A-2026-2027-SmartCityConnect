<?php

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/Model/user.php';
require_once __DIR__ . '/Controller/userC.php';

$db = database();
$userController = new UserController(new UserModel($db));
$currentUser = $userController->currentUser();
$currentPage = 'users';
$pageTitle = 'User management · Smart City Connect';

if ($currentUser === null) {
    setMessage('error', 'Only administrators can manage user accounts.');
    header('Location: index.php?page=login&next=admin');
    exit;
}
if ($currentUser['role'] !== 'admin') {
    setMessage('error', 'Only administrators can manage user accounts.');
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
    $id = inputId($_POST, 'id');

    if ($action === 'create_user') {
        $errors = $userController->createByAdmin($_POST);
    } elseif ($action === 'update_user') {
        $errors = $userController->updateByAdmin(
            $id,
            $_POST,
            (int) $currentUser['id']
        );
    } elseif ($action === 'delete_user') {
        $errors = $userController->deleteByAdmin(
            $id,
            (int) $currentUser['id']
        );
    } else {
        http_response_code(400);
        $message = 'This action is not available.';
        require __DIR__ . '/Views/notFound.php';
        exit;
    }

    if ($errors === []) {
        setMessage('notice', 'User account updated.');
    } else {
        setMessage('error', implode(' ', $errors));
    }
    header('Location: users.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    header('Allow: GET, POST');
    exit;
}

$users = (new UserModel($db))->getAll();
require __DIR__ . '/Views/userManagement.php';
