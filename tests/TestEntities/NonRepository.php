<?php

namespace Squirrel\EntitiesBundle\Tests\TestEntities;

class NonRepository
{
    private int $userId = 0;

    public function getUserId(): int
    {
        return $this->userId;
    }
}
