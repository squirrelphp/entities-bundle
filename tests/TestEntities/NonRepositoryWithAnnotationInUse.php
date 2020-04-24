<?php

namespace Squirrel\EntitiesBundle\Tests\TestEntities;

use Squirrel\Entities\Annotation as SQL;

class NonRepositoryWithAnnotationInUse
{
    private int $userId = 0;

    public function getUserId(): int
    {
        return $this->userId;
    }
}
