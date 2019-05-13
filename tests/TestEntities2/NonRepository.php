<?php

namespace Squirrel\EntitiesBundle\Tests\TestEntities2;

class NonRepository
{
    /**
     * @var int
     */
    private $userId = 0;

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }
}
