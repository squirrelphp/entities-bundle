<?php

namespace Squirrel\EntitiesBundle;

use Squirrel\EntitiesBundle\DependencyInjection\ContainerExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class SquirrelEntitiesBundle extends Bundle
{
    public function getContainerExtension()
    {
        return new ContainerExtension();
    }
}
