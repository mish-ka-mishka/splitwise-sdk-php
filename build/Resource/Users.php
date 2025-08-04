<?php

declare(strict_types=1);

namespace MKaverin\SplitwiseSDK\Resource;

use MKaverin\SplitwiseSDK\Requests\Users\GetInformationAboutAnotherUser;
use MKaverin\SplitwiseSDK\Requests\Users\GetInformationAboutTheCurrentUser;
use MKaverin\SplitwiseSDK\Requests\Users\UpdateUser;
use MKaverin\SplitwiseSDK\Resource;
use Saloon\Http\Response;

class Users extends Resource
{
    public function getInformationAboutTheCurrentUser(): Response
    {
        return $this->connector->send(new GetInformationAboutTheCurrentUser());
    }

    public function getInformationAboutAnotherUser(int $id): Response
    {
        return $this->connector->send(new GetInformationAboutAnotherUser($id));
    }

    public function updateUser(
        int $id,
        ?string $firstName = null,
        ?string $lastName = null,
        ?string $email = null,
        ?string $password = null,
        ?string $locale = null,
        ?string $defaultCurrency = null,
    ): Response {
        return $this->connector->send(new UpdateUser($id, $firstName, $lastName, $email, $password, $locale, $defaultCurrency));
    }
}
