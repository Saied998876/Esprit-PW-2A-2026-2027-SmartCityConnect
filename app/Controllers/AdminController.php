<?php

declare(strict_types=1);

namespace App\Controllers;

final class AdminController extends Controller
{
    public function index(): void
    {
        $posts = $this->store->posts();
        $commentCount = array_sum(array_map(static fn(array $post): int => count($post['comments']), $posts));
        $this->render('admin', [
            'posts' => $posts,
            'postCount' => count($posts),
            'commentCount' => $commentCount,
        ], 'Moderation | Smart City Connect');
    }

    public function deletePost(): void
    {
        $this->requirePost();
        $postId = (int) ($_POST['post_id'] ?? 0);
        if (!$this->store->deletePost($postId)) {
            set_flash('error', 'This post no longer exists.');
        } else {
            set_flash('success', 'The post and its comments have been deleted.');
        }

        redirect_to(page_url('admin'));
    }

    public function deleteComment(): void
    {
        $this->requirePost();
        $commentId = (int) ($_POST['comment_id'] ?? 0);
        if (!$this->store->deleteComment($commentId)) {
            set_flash('error', 'This comment no longer exists.');
        } else {
            set_flash('success', 'The comment has been deleted.');
        }

        redirect_to(page_url('admin'));
    }
}
