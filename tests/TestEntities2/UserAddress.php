<?php

namespace Squirrel\EntitiesBundle\Tests\TestEntities2;

use Squirrel\Entities\Annotation\Entity;
use Squirrel\Entities\Annotation\Field;
use Squirrel\Entities\PopulatePropertiesWithIterableTrait;

/**
 * @Entity("users_address")
 */
class UserAddress
{
    use PopulatePropertiesWithIterableTrait;

    /**
     * @Field("user_id")
     */
    private int $userId = 0;

    /**
     * @Field("at_home")
     */
    private bool $atHome = false;

    /**
     * @Field("street_name")
     */
    private string $streetName = '';

    /**
     * @Field("street_number")
     */
    private string $streetNumber = '';

    /**
     * @Field("city")
     */
    private string $city = '';

    /**
     * @Field("picture", blob=true)
     */
    private string $picture = '';

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function isAtHome(): bool
    {
        return $this->atHome;
    }

    public function getStreetName(): string
    {
        return $this->streetName;
    }

    public function getStreetNumber(): string
    {
        return $this->streetNumber;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function getPicture(): string
    {
        return $this->picture;
    }
}
