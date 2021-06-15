<?php

namespace Squirrel\EntitiesBundle\Tests\TestEntities2;

use Squirrel\Entities\Attribute\Entity;
use Squirrel\Entities\Attribute\Field;
use Squirrel\Entities\PopulatePropertiesWithIterableTrait;

#[Entity("users")]
class User
{
    use PopulatePropertiesWithIterableTrait;

    #[Field("user_id", autoincrement: true)]
    private int $userId = 0;

    #[Field("active")]
    private bool $active = false;

    #[Field("user_name")]
    private string $userName = '';

    #[Field("login_name_md5")]
    private string $loginNameMD5 = '';

    #[Field("login_password")]
    private string $loginPassword = '';

    #[Field("email_address")]
    private string $emailAddress = '';

    #[Field("balance")]
    private float $balance = 0;

    #[Field("location_id")]
    private ?int $locationId;

    #[Field("create_date")]
    private int $createDate = 0;

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function getUserName(): string
    {
        return $this->userName;
    }

    public function getLoginNameMD5(): string
    {
        return $this->loginNameMD5;
    }

    public function getLoginPassword(): string
    {
        return $this->loginPassword;
    }

    public function getEmailAddress(): string
    {
        return $this->emailAddress;
    }

    public function getBalance(): float
    {
        return $this->balance;
    }

    public function getLocationId(): ?int
    {
        return $this->locationId;
    }

    public function getCreateDate(): int
    {
        return $this->createDate;
    }
}
