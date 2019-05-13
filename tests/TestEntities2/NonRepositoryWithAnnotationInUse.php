<?php

namespace Squirrel\EntitiesBundle\Tests\TestEntities2;

use Squirrel\Entities\Annotation as SQL;

class NonRepositoryWithAnnotationInUse
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
