<?php

declare(strict_types=1);

namespace App\Models;

use RuntimeException;

final class ForumStore
{
    private string $dataPath;

    public function __construct(string $dataPath)
    {
        $this->dataPath = $dataPath;
        $directory = dirname($dataPath);

        if (!is_dir($directory) && !mkdir($directory, 0775, true) && !is_dir($directory)) {
            throw new RuntimeException('Unable to create the data directory.');
        }

        if (!is_file($dataPath)) {
            file_put_contents($dataPath, json_encode(['users' => [], 'posts' => [], 'comments' => []], JSON_PRETTY_PRINT));
        }
    }

    public function posts(): array
    {
        $data = $this->read();
        $users = [];
        foreach ($data['users'] as $user) {
            $users[$user['id']] = ['name' => $user['name']];
        }

        $commentsByPost = [];
        foreach ($data['comments'] as $comment) {
            $comment['author'] = ['name' => $comment['author_name'] ?? $users[$comment['user_id'] ?? 0]['name'] ?? 'Member'];
            $commentsByPost[$comment['post_id']][] = $comment;
        }

        $posts = [];
        foreach ($data['posts'] as $post) {
            $post['author'] = ['name' => $post['author_name'] ?? $users[$post['user_id'] ?? 0]['name'] ?? 'Member'];
            $post['comments'] = $commentsByPost[$post['id']] ?? [];
            $posts[] = $post;
        }

        usort($posts, static fn(array $left, array $right): int => strcmp($right['created_at'], $left['created_at']));
        return $posts;
    }

    public function post(int $postId): ?array
    {
        foreach ($this->posts() as $post) {
            if ($post['id'] === $postId) {
                return $post;
            }
        }

        return null;
    }

    public function createPost(string $authorName, string $title, string $body, string $category): void
    {
        $this->change(function (array &$data) use ($authorName, $title, $body, $category): void {
            $data['posts'][] = [
                'id' => $this->nextId($data['posts']),
                'author_name' => $authorName,
                'title' => $title,
                'body' => $body,
                'category' => $category,
                'created_at' => date(DATE_ATOM),
                'updated_at' => null,
            ];
        });
    }

    public function updatePost(int $postId, string $title, string $body, string $category): bool
    {
        return $this->change(function (array &$data) use ($postId, $title, $body, $category): bool {
            foreach ($data['posts'] as &$post) {
                if ($post['id'] === $postId) {
                    $post['title'] = $title;
                    $post['body'] = $body;
                    $post['category'] = $category;
                    $post['updated_at'] = date(DATE_ATOM);
                    return true;
                }
            }

            return false;
        });
    }

    public function deletePost(int $postId): bool
    {
        return $this->change(function (array &$data) use ($postId): bool {
            $count = count($data['posts']);
            $data['posts'] = array_values(array_filter($data['posts'], static fn(array $post): bool => $post['id'] !== $postId));
            $data['comments'] = array_values(array_filter($data['comments'], static fn(array $comment): bool => $comment['post_id'] !== $postId));

            return count($data['posts']) !== $count;
        });
    }

    public function addComment(int $postId, string $authorName, string $body): void
    {
        $this->change(function (array &$data) use ($postId, $authorName, $body): void {
            $data['comments'][] = [
                'id' => $this->nextId($data['comments']),
                'post_id' => $postId,
                'author_name' => $authorName,
                'body' => $body,
                'created_at' => date(DATE_ATOM),
            ];
        });
    }

    public function deleteComment(int $commentId): bool
    {
        return $this->change(function (array &$data) use ($commentId): bool {
            $count = count($data['comments']);
            $data['comments'] = array_values(array_filter($data['comments'], static fn(array $comment): bool => $comment['id'] !== $commentId));

            return count($data['comments']) !== $count;
        });
    }

    private function read(): array
    {
        $contents = file_get_contents($this->dataPath);
        $data = is_string($contents) ? json_decode($contents, true) : null;

        if (!is_array($data)) {
            return ['users' => [], 'posts' => [], 'comments' => []];
        }

        return array_replace(['users' => [], 'posts' => [], 'comments' => []], $data);
    }

    private function change(callable $callback): mixed
    {
        $lock = fopen($this->dataPath . '.lock', 'c');
        if ($lock === false || !flock($lock, LOCK_EX)) {
            throw new RuntimeException('Unable to lock the forum data.');
        }

        try {
            $data = $this->read();
            $result = $callback($data);
            $encoded = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);
            if (file_put_contents($this->dataPath, $encoded, LOCK_EX) === false) {
                throw new RuntimeException('Unable to save the forum data.');
            }

            return $result;
        } finally {
            flock($lock, LOCK_UN);
            fclose($lock);
        }
    }

    private function nextId(array $rows): int
    {
        return $rows === [] ? 1 : max(array_column($rows, 'id')) + 1;
    }

}
