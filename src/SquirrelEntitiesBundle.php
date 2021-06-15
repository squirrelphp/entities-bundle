<?php

namespace Squirrel\EntitiesBundle;

use Squirrel\Entities\Attribute\EntityProcessor;
use Squirrel\Entities\Generate\FindClassesWithAttribute;
use Squirrel\EntitiesBundle\DependencyInjection\SquirrelEntitiesExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * @codeCoverageIgnore Just adds the extension, there is nothing to test
 */
class SquirrelEntitiesBundle extends Bundle
{
    public function getContainerExtension()
    {
        return new SquirrelEntitiesExtension(
            new EntityProcessor(),
            new FindClassesWithAttribute(),
        );
    }
}
