<?php

declare(strict_types=1);

namespace App\Controllers;

final class ForumController extends Controller
{
    private const CATEGORIES = ['Discussion', 'Question', 'Idea', 'Alert'];

    public function index(): void
    {
        $this->render('forum', [
            'posts' => $this->store->posts(),
            'categories' => self::CATEGORIES,
        ], 'Forum | Smart City Connect');
    }

    public function createPost(): void
    {
        $this->requirePost();

        $authorName = trim((string) ($_POST['author'] ?? ''));
        $title = trim((string) ($_POST['title'] ?? ''));
        $body = trim((string) ($_POST['body'] ?? ''));
        $category = trim((string) ($_POST['category'] ?? 'Discussion'));
        if ($authorName === '' || mb_strlen($authorName) > 80 || $title === '' || mb_strlen($title) > 140 || $body === '' || mb_strlen($body) > 5000 || !in_array($category, self::CATEGORIES, true)) {
            set_flash('error', 'Please check your name, title, category, and post content.');
            redirect_to(page_url('forum'));
        }

        $this->store->createPost($authorName, $title, $body, $category);
        set_flash('success', 'Your post has been published.');
        redirect_to(page_url('forum'));
    }

    public function editPost(): void
    {
        $post = $this->findPost((int) ($_GET['id'] ?? 0));
        if ($post === null) {
            http_response_code(404);
            $this->render('not_found', [], 'Post not found | Smart City Connect');
            return;
        }
        $this->render('edit_post', ['post' => $post, 'categories' => self::CATEGORIES], 'Edit post | Smart City Connect');
    }

    public function updatePost(): void
    {
        $this->requirePost();
        $postId = (int) ($_POST['post_id'] ?? 0);
        if ($this->findPost($postId) === null) {
            http_response_code(404);
            exit('Post not found.');
        }

        $title = trim((string) ($_POST['title'] ?? ''));
        $body = trim((string) ($_POST['body'] ?? ''));
        $category = trim((string) ($_POST['category'] ?? ''));
        if ($title === '' || mb_strlen($title) > 140 || $body === '' || mb_strlen($body) > 5000 || !in_array($category, self::CATEGORIES, true)) {
            set_flash('error', 'Please check the title, category, and post content.');
            redirect_to(page_url('edit-post', ['id' => $postId]));
        }

        $this->store->updatePost($postId, $title, $body, $category);
        set_flash('success', 'Your post has been updated.');
        redirect_to(page_url('forum', ['post' => $postId]) . '#post-' . $postId);
    }

    public function deletePost(): void
    {
        $this->requirePost();
        $postId = (int) ($_POST['post_id'] ?? 0);
        if ($this->findPost($postId) === null) {
            http_response_code(404);
            exit('Post not found.');
        }

        $this->store->deletePost($postId);
        set_flash('success', 'The post and its comments have been deleted.');
        redirect_to(page_url('forum'));
    }

    public function addComment(): void
    {
        $this->requirePost();
        $postId = (int) ($_POST['post_id'] ?? 0);
        $post = $this->findPost($postId);
        $authorName = trim((string) ($_POST['author'] ?? ''));
        $body = trim((string) ($_POST['body'] ?? ''));
        if ($post === null) {
            http_response_code(404);
            exit('Post not found.');
        }
        if ($authorName === '' || mb_strlen($authorName) > 80 || $body === '' || mb_strlen($body) > 1000) {
            set_flash('error', 'Please check your name and comment (maximum 1,000 characters).');
            redirect_to(page_url('forum') . '#post-' . $postId);
        }

        $this->store->addComment($postId, $authorName, $body);
        set_flash('success', 'Your comment has been added.');
        redirect_to(page_url('forum') . '#post-' . $postId);
    }

    public function deleteComment(): void
    {
        $this->requirePost();
        $commentId = (int) ($_POST['comment_id'] ?? 0);
        if (!$this->store->deleteComment($commentId)) {
            http_response_code(404);
            exit('Comment not found.');
        }
        set_flash('success', 'The comment has been deleted.');
        redirect_to(page_url('forum'));
    }

    private function findPost(int $postId): ?array
    {
        return $postId > 0 ? $this->store->post($postId) : null;
    }

}
