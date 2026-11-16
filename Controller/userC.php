<?php

class UserController
{
    private $userModel;

    public function __construct($userModel)
    {
        $this->userModel = $userModel;
    }

    public function currentUser()
    {
        if (!isset($_SESSION['user_id'])) {
            return null;
        }

        $user = $this->userModel->findById((int) $_SESSION['user_id']);
        if ($user === null) {
            unset($_SESSION['user_id']);
            return null;
        }

        return $user;
    }

    public function authenticate($email, $password)
    {
        $email = is_string($email) ? strtolower(trim($email)) : '';
        $password = is_string($password) ? $password : '';
        if (strlen($password) > 72) {
            return false;
        }
        $user = $this->userModel->findByEmail($email);
        if ($user === null || !password_verify($password, $user['password_hash'])) {
            return false;
        }

        session_regenerate_id(true);
        $_SESSION['user_id'] = (int) $user['id'];
        $_SESSION['created_at'] = time();
        $_SESSION['last_id_change'] = time();
        $_SESSION['last_activity'] = time();
        unset($_SESSION['csrf_token']);

        return true;
    }

    public function logout()
    {
        $_SESSION = [];
        session_regenerate_id(true);
        $_SESSION['created_at'] = time();
        $_SESSION['last_id_change'] = time();
        $_SESSION['last_activity'] = time();
        unset($_SESSION['csrf_token']);
    }

    public function register($form)
    {
        $user = $this->readUserFields($form);
        $errors = $this->validateUser($user, 0);
        $password = inputString($form, 'password');
        $confirmation = inputString($form, 'password_confirmation');

        if (!$this->validPassword($password)) {
            $errors[] = 'Use a password between 12 and 72 characters.';
        }
        if ($password !== $confirmation) {
            $errors[] = 'The password confirmation does not match.';
        }

        if ($errors !== []) {
            return $errors;
        }

        $this->userModel->create([
            'name' => $user['name'],
            'email' => $user['email'],
            'password_hash' => password_hash($password, PASSWORD_DEFAULT),
            'role' => 'citizen',
        ]);

        return [];
    }

    public function updateProfile($id, $form)
    {
        $user = $this->readUserFields($form);
        $errors = $this->validateUser($user, $id);
        $currentPassword = inputString($form, 'current_password');
        $newPassword = inputString($form, 'new_password');
        $confirmation = inputString($form, 'password_confirmation');

        $currentUser = $this->userModel->findById($id);
        if ($currentUser === null) {
            return ['Your account could not be found.'];
        }

        if ($newPassword !== '') {
            $account = $this->userModel->findByEmail($currentUser['email']);
            if ($account === null || !password_verify($currentPassword, $account['password_hash'])) {
                $errors[] = 'Enter your current password to set a new password.';
            }
            if (!$this->validPassword($newPassword)) {
                $errors[] = 'Use a new password between 12 and 72 characters.';
            }
            if ($newPassword !== $confirmation) {
                $errors[] = 'The new password confirmation does not match.';
            }
        } elseif ($currentPassword !== '' || $confirmation !== '') {
            $errors[] = 'Enter a new password and confirmation together.';
        }

        if ($errors !== []) {
            return $errors;
        }

        $user['role'] = $currentUser['role'];
        $this->userModel->update($id, $user);
        if ($newPassword !== '') {
            $this->userModel->updatePassword(
                $id,
                password_hash($newPassword, PASSWORD_DEFAULT)
            );
            session_regenerate_id(true);
            $_SESSION['last_id_change'] = time();
            unset($_SESSION['csrf_token']);
        }

        return [];
    }

    public function createByAdmin($form)
    {
        $user = $this->readUserFields($form);
        $user['role'] = inputString($form, 'role');
        $errors = $this->validateUser($user, 0);
        $password = inputString($form, 'password');

        if (!$this->validPassword($password)) {
            $errors[] = 'Use a password between 12 and 72 characters.';
        }
        if (!in_array($user['role'], ['citizen', 'admin'], true)) {
            $errors[] = 'Choose a valid user role.';
        }

        if ($errors !== []) {
            return $errors;
        }

        $this->userModel->create([
            'name' => $user['name'],
            'email' => $user['email'],
            'password_hash' => password_hash($password, PASSWORD_DEFAULT),
            'role' => $user['role'],
        ]);

        return [];
    }

    public function updateByAdmin($id, $form, $currentAdminId)
    {
        $user = $this->readUserFields($form);
        $user['role'] = inputString($form, 'role');
        $target = $this->userModel->findById($id);

        if ($target === null) {
            return ['The selected account could not be found.'];
        }

        $errors = $this->validateUser($user, $id);
        $password = inputString($form, 'password');

        if (!in_array($user['role'], ['citizen', 'admin'], true)) {
            $errors[] = 'Choose a valid user role.';
        }
        if ($user['role'] !== $target['role'] && $id === $currentAdminId) {
            $errors[] = 'You cannot change your own administrator role.';
        }
        if ($target['role'] === 'admin' && $user['role'] !== 'admin'
            && $this->userModel->countAdmins() < 2) {
            $errors[] = 'Keep at least one administrator account.';
        }
        if ($password !== '' && !$this->validPassword($password)) {
            $errors[] = 'Use a password between 12 and 72 characters.';
        }

        if ($errors !== []) {
            return $errors;
        }

        $this->userModel->update($id, $user);
        if ($password !== '') {
            $this->userModel->updatePassword(
                $id,
                password_hash($password, PASSWORD_DEFAULT)
            );
        }

        return [];
    }

    public function deleteByAdmin($id, $currentAdminId)
    {
        $target = $this->userModel->findById($id);
        if ($target === null) {
            return ['The selected account could not be found.'];
        }
        if ($id === $currentAdminId) {
            return ['You cannot delete the account you are using.'];
        }
        if ($target['role'] === 'admin' && $this->userModel->countAdmins() < 2) {
            return ['Keep at least one administrator account.'];
        }

        $this->userModel->delete($id);
        return [];
    }

    private function readUserFields($form)
    {
        return [
            'name' => trim(inputString($form, 'name')),
            'email' => strtolower(trim(inputString($form, 'email'))),
        ];
    }

    private function validateUser($user, $exceptId)
    {
        $errors = [];
        if ($user['name'] === '' || strlen($user['name']) > 100) {
            $errors[] = 'Enter a name using 100 characters or fewer.';
        }
        if (!filter_var($user['email'], FILTER_VALIDATE_EMAIL)
            || strlen($user['email']) > 254) {
            $errors[] = 'Enter a valid email address.';
        } elseif ($this->userModel->emailExists($user['email'], $exceptId)) {
            $errors[] = 'An account already uses this email address.';
        }

        return $errors;
    }

    private function validPassword($password)
    {
        return strlen($password) >= 12 && strlen($password) <= 72;
    }
}
