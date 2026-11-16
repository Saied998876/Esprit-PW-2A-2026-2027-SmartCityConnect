<?php

declare(strict_types=1);

use App\Controllers\AdminController;
use App\Controllers\ForumController;

return [
    'forum' => [ForumController::class, 'index'],
    'create-post' => [ForumController::class, 'createPost'],
    'edit-post' => [ForumController::class, 'editPost'],
    'update-post' => [ForumController::class, 'updatePost'],
    'delete-post' => [ForumController::class, 'deletePost'],
    'add-comment' => [ForumController::class, 'addComment'],
    'delete-comment' => [ForumController::class, 'deleteComment'],
    'admin' => [AdminController::class, 'index'],
    'admin-delete-post' => [AdminController::class, 'deletePost'],
    'admin-delete-comment' => [AdminController::class, 'deleteComment'],
];
