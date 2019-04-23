Squirrel Entities Integration for Symfony
=========================================

Integration of [squirrelphp/entities](https://github.com/squirrelphp/entities) into Symfony through bundle configuration, also needs [squirrelphp/queries-bundle](https://github.com/squirrelphp/queries-bundle).

nstallation
------------

```
composer require squirrelphp/entities-bundle
```

Configuration
-------------

Enable the bundle in your AppKernel by adding `Squirrel\EntitiesBundle\SquirrelEntitiesBundle` to the list of bundles.

Configure the directories where the bundle will look for repositories like this in Symfony / YAML:

    squirrel_entities:
        directories:
            - '%kernel.project_dir%/src'
            - '%kernel.project_dir%/possibleOtherDirectory'
            
It will go through these directories recusively, finding all entities and possible repositories and creating services for the repositories.

Look at `Squirrel\EntitiesBundle\DependencyInjection\ContainerExtension` to get more details about how it works, and check out the `squirrel_repositories_generate` bin command in [squirrelphp/entities](https://github.com/squirrelphp/entities) to generate repositories from entities which are then autoloaded by this bundle.

More documentation will follow!