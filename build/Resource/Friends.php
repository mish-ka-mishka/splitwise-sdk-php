<?php

declare(strict_types=1);

namespace MKaverin\SplitwiseSDK\Resource;

use MKaverin\SplitwiseSDK\Requests\Friends\AddFriend;
use MKaverin\SplitwiseSDK\Requests\Friends\AddFriends;
use MKaverin\SplitwiseSDK\Requests\Friends\DeleteFriendship;
use MKaverin\SplitwiseSDK\Requests\Friends\GetDetailsAboutFriend;
use MKaverin\SplitwiseSDK\Requests\Friends\ListCurrentUserFriends;
use MKaverin\SplitwiseSDK\Resource;
use Saloon\Http\Response;

class Friends extends Resource
{
    public function listCurrentUserFriends(): Response
    {
        return $this->connector->send(new ListCurrentUserFriends());
    }

    /**
     * @param int $id User ID of the friend
     */
    public function getDetailsAboutFriend(int $id): Response
    {
        return $this->connector->send(new GetDetailsAboutFriend($id));
    }

    public function addFriend(
        ?string $userEmail = null,
        ?string $userFirstName = null,
        ?string $userLastName = null,
    ): Response {
        return $this->connector->send(new AddFriend($userEmail, $userFirstName, $userLastName));
    }

    public function addFriends(): Response
    {
        return $this->connector->send(new AddFriends());
    }

    /**
     * @param int $id User ID of the friend
     */
    public function deleteFriendship(int $id): Response
    {
        return $this->connector->send(new DeleteFriendship($id));
    }
}
