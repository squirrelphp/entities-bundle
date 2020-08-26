<?php

namespace Squirrel\EntitiesBundle;

use Doctrine\Common\Annotations\AnnotationReader;
use Squirrel\Entities\Annotation\EntityProcessor;
use Squirrel\Entities\Generate\FindClassesWithAnnotation;
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
            new EntityProcessor(new AnnotationReader()),
            new FindClassesWithAnnotation(),
        );
    }
}
