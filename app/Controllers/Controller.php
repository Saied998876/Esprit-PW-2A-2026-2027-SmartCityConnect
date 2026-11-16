<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\ForumStore;

abstract class Controller
{
    public function __construct(protected ForumStore $store)
    {
    }

    protected function render(string $view, array $data = [], string $title = 'Smart City Connect') : void
    {
        extract($data, EXTR_SKIP);
        $flash = take_flash();
        $currentPage = (string) ($_GET['page'] ?? 'forum');
        $isAdminPage = $currentPage === 'admin';

        ob_start();
        require dirname(__DIR__) . '/Views/pages/' . $view . '.php';
        $content = (string) ob_get_clean();

        require dirname(__DIR__) . '/Views/layout.php';
    }

    protected function requirePost(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            exit('Method not allowed.');
        }

        if (!verify_csrf()) {
            http_response_code(419);
            exit('The form has expired. Reload the page and try again.');
        }
    }
}
