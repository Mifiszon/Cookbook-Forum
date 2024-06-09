<?php

namespace App\Service;

use App\Entity\Comment;

interface CommentServiceInterface
{
    public function add(Comment $comment): void;

    public function delete(Comment $comment): void;
}
