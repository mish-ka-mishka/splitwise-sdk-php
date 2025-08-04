<?php

declare(strict_types=1);

namespace MKaverin\SplitwiseSDK\Resource;

use MKaverin\SplitwiseSDK\Requests\Comments\CreateComment;
use MKaverin\SplitwiseSDK\Requests\Comments\DeleteComment;
use MKaverin\SplitwiseSDK\Requests\Comments\GetExpenseComments;
use MKaverin\SplitwiseSDK\Resource;
use Saloon\Http\Response;

class Comments extends Resource
{
    public function getExpenseComments(int $expenseId): Response
    {
        return $this->connector->send(new GetExpenseComments($expenseId));
    }

    public function createComment(?int $expenseId = null, ?string $content = null): Response
    {
        return $this->connector->send(new CreateComment($expenseId, $content));
    }

    public function deleteComment(int $id): Response
    {
        return $this->connector->send(new DeleteComment($id));
    }
}
