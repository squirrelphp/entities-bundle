<?php

namespace Squirrel\EntitiesBundle\Tests\TestEntities;

use Squirrel\Entities\Attribute as SQL;

class NonRepositoryWithAttributeInUse
{
    private int $userId = 0;

    public function getUserId(): int
    {
        return $this->userId;
    }
}
