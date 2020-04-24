<?php

namespace Squirrel\EntitiesBundle\Tests\TestEntities;

use Squirrel\Entities\Annotation as SQL;
use Squirrel\Entities\EntityConstructorTrait;

/**
 * @SQL\Entity("users")
 */
class User
{
    use EntityConstructorTrait;

    /**
     * @SQL\Field("user_id", autoincrement=true)
     */
    private int $userId = 0;

    /**
     * @SQL\Field("active")
     */
    private bool $active = false;

    /**
     * @SQL\Field("user_name")
     */
    private string $userName = '';

    /**
     * @SQL\Field("login_name_md5")
     */
    private string $loginNameMD5 = '';

    /**
     * @SQL\Field("login_password")
     */
    private string $loginPassword = '';

    /**
     * @SQL\Field("email_address")
     */
    private string $emailAddress = '';

    /**
     * @SQL\Field("balance")
     */
    private float $balance = 0;

    /**
     * @SQL\Field("location_id")
     */
    private ?int $locationId;

    /**
     * @SQL\Field("create_date")
     */
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
