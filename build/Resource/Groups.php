<?php

declare(strict_types=1);

namespace MKaverin\SplitwiseSDK\Resource;

use MKaverin\SplitwiseSDK\Requests\Groups\AddUserToGroup;
use MKaverin\SplitwiseSDK\Requests\Groups\CreateGroup;
use MKaverin\SplitwiseSDK\Requests\Groups\DeleteGroup;
use MKaverin\SplitwiseSDK\Requests\Groups\GetInformationAboutGroup;
use MKaverin\SplitwiseSDK\Requests\Groups\ListTheCurrentUserGroups;
use MKaverin\SplitwiseSDK\Requests\Groups\RemoveUserFromGroup;
use MKaverin\SplitwiseSDK\Requests\Groups\RestoreGroup;
use MKaverin\SplitwiseSDK\Resource;
use Saloon\Http\Response;

class Groups extends Resource
{
    public function listTheCurrentUserGroups(): Response
    {
        return $this->connector->send(new ListTheCurrentUserGroups());
    }

    public function getInformationAboutGroup(int $id): Response
    {
        return $this->connector->send(new GetInformationAboutGroup($id));
    }

    /**
     * @param string $groupType what is the group used for?
     *
     * **Note**: It is recommended to use `home` in place of `house` or `apartment`
     * @param bool $simplifyByDefault Turn on simplify debts?
     */
    public function createGroup(string $name, ?string $groupType = null, ?bool $simplifyByDefault = null): Response
    {
        return $this->connector->send(new CreateGroup($name, $groupType, $simplifyByDefault));
    }

    public function deleteGroup(int $id): Response
    {
        return $this->connector->send(new DeleteGroup($id));
    }

    public function restoreGroup(int $id): Response
    {
        return $this->connector->send(new RestoreGroup($id));
    }

    public function addUserToGroup(
        int $groupId,
        ?int $userId = null,
        ?string $firstName = null,
        ?string $lastName = null,
        ?string $email = null,
    ): Response {
        return $this->connector->send(new AddUserToGroup($groupId, $userId, $firstName, $lastName, $email));
    }

    public function removeUserFromGroup(int $groupId, int $userId): Response
    {
        return $this->connector->send(new RemoveUserFromGroup($groupId, $userId));
    }
}
