Squirrel Entities Integration for Symfony
=========================================

[![Build Status](https://img.shields.io/travis/com/squirrelphp/entities-bundle.svg)](https://travis-ci.com/squirrelphp/entities-bundle) [![Test Coverage](https://api.codeclimate.com/v1/badges/a1673a906f5c334125c1/test_coverage)](https://codeclimate.com/github/squirrelphp/entities-bundle/test_coverage) ![PHPStan](https://img.shields.io/badge/style-level%208-success.svg?style=flat-round&label=phpstan) [![Packagist Version](https://img.shields.io/packagist/v/squirrelphp/entities-bundle.svg?style=flat-round)](https://packagist.org/packages/squirrelphp/entities-bundle) [![PHP Version](https://img.shields.io/packagist/php-v/squirrelphp/entities-bundle.svg)](https://packagist.org/packages/squirrelphp/entities-bundle) [![Software License](https://img.shields.io/badge/license-MIT-success.svg?style=flat-round)](LICENSE)

Integration of [squirrelphp/entities](https://github.com/squirrelphp/entities) into Symfony through bundle configuration, also needs [squirrelphp/queries-bundle](https://github.com/squirrelphp/queries-bundle) as a basis for connecting to databases and executing queries.

Installation
------------

```
composer require squirrelphp/entities-bundle
```

Configuration
-------------

Enable the bundle in your Symfony configuration by adding `Squirrel\EntitiesBundle\SquirrelEntitiesBundle` to the list of bundles, and make sure to enable `Squirrel\QueriesBundle\SquirrelQueriesBundle` and configure connections through QueriesBundle, which is the basis for this bundle.

Configure the directories where the bundle will look for repositories like this in Symfony / YAML:

    squirrel_entities:
        directories:
            - '%kernel.project_dir%/src'
            - '%kernel.project_dir%/possibleOtherDirectory'

It will go through these directories recusively, finding all entities and generated repositories and creating services for the repositories. The details on how to work with the repositories can be found in the documentation for the underlying library [squirrelphp/entities](https://github.com/squirrelphp/entities).

Overriding table names and connection names
-------------------------------------------

If you are reusing entities from other projects and only want to change the connection name and/or the table name of an entity, you can override it through configuration:

    squirrel_entities:
        connection_names:
            Application\Entity\User: 'postgres_connection'
            Application\Entity\Session: 'sqlite_connection'
        table_names:
            Application\Entity\User: 'differentdatabase.users'
            Application\Entity\Session: 'sessions_table'

This can also come in handy if you want to change connection names and table names for testing or development, while the annotation values are for the production system. Just use the fully qualified entity class name as the key. If you specify an empty string as connection name the default connection is used (if a default connection was defined through `QueriesBundle`).

Workflow with entities and repositories
---------------------------------------

At this point you should have already configured [squirrelphp/queries-bundle](https://github.com/squirrelphp/queries-bundle) with your database(s).

### While developing

1. Create annotated entities as explained in [squirrelphp/entities](https://github.com/squirrelphp/entities)
2. Run `vendor/bin/squirrel_repositories_generate --source-dir=src` to generate the repositories for your entities - those will be gitignored (add or change the --source-dir entries depending on where your entities are)
3. Add the directories of your entities to the `squirrel_entities` configuration so repositories are found and initialized automatically when the Symfony service container is compiled.
4. Use the generated repository classes as type hints in your code and let Symfony autowire these services.
5. Add `vendor/bin/squirrel_repositories_generate --source-dir=src` to your composer.json in `scripts` for `post-install-cmd` and `post-update-cmd` (at the top of these lists). Whenever you do an install or update the repositories are recreated.

### In production

If you run `composer install` in production, just make sure you have added `squirrel_repositories_generate` to your composer.json as described in point number 5 in `While developing`, so every time you deploy the repositories are recreated.

If you have some specific deployment workflow, you can call `vendor/bin/squirrel_repositories_generate --source-dir=src` explicitely to generate the repositories. Just make sure you do it before the Symfony container is compiled (or before clearing it with cache:clear or cache:warmup), otherwise the repository classes will be missing and autowiring code with the repository classes will not work.