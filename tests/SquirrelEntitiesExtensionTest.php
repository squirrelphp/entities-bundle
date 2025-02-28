<?php

namespace Squirrel\EntitiesBundle\Tests;

use Squirrel\Entities\Attribute\EntityProcessor;
use Squirrel\Entities\Generate\FindClassesWithAttribute;
use Squirrel\Entities\MultiRepositoryBuilderReadOnlyInterface;
use Squirrel\Entities\MultiRepositoryBuilderWriteableInterface;
use Squirrel\Entities\MultiRepositoryReadOnlyInterface;
use Squirrel\Entities\MultiRepositoryWriteableInterface;
use Squirrel\Entities\RepositoryConfig;
use Squirrel\Entities\RepositoryReadOnly;
use Squirrel\Entities\RepositoryWriteable;
use Squirrel\Entities\TransactionInterface;
use Squirrel\EntitiesBundle\DependencyInjection\SquirrelEntitiesExtension;
use Squirrel\EntitiesBundle\Tests\TestEntities\User;
use Squirrel\EntitiesBundle\Tests\TestEntities\UserAddress;
use Squirrel\EntitiesBundle\Tests\TestEntities\UserAddressRepositoryReadOnly;
use Squirrel\EntitiesBundle\Tests\TestEntities\UserAddressRepositoryWriteable;
use Squirrel\EntitiesBundle\Tests\TestEntities\UserRepositoryReadOnly;
use Squirrel\EntitiesBundle\Tests\TestEntities\UserRepositoryWriteable;
use Squirrel\EntitiesBundle\Tests\TestEntities2\UserRepositoryReadOnly as UserRepositoryReadOnly2;
use Squirrel\Queries\DBInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

final class SquirrelEntitiesExtensionTest extends \PHPUnit\Framework\TestCase
{
    public function testNoConfiguration(): void
    {
        $container = new ContainerBuilder();
        $configuration = [];

        $this->loadExtension($container, $configuration);

        $this->assertTrue($container->hasDefinition(MultiRepositoryReadOnlyInterface::class));
        $this->assertTrue($container->hasDefinition(MultiRepositoryWriteableInterface::class));
        $this->assertTrue($container->hasDefinition(MultiRepositoryBuilderReadOnlyInterface::class));
        $this->assertTrue($container->hasDefinition(MultiRepositoryBuilderWriteableInterface::class));
        $this->assertEquals(5, \count($container->getDefinitions())); // + service container
    }

    public function testSimpleConfiguration(): void
    {
        $container = new ContainerBuilder();
        $configuration = [
            0 =>
                [
                    'directories' =>
                        [
                            0 => __DIR__ . '/TestEntities',
                            1 => __DIR__ . '/../src',
                        ],
                ],
        ];

        $this->loadExtension($container, $configuration);

        $this->assertTrue($container->hasDefinition(MultiRepositoryReadOnlyInterface::class));
        $this->assertTrue($container->hasDefinition(MultiRepositoryWriteableInterface::class));
        $this->assertTrue($container->hasDefinition(MultiRepositoryBuilderReadOnlyInterface::class));
        $this->assertTrue($container->hasDefinition(MultiRepositoryBuilderWriteableInterface::class));
        $this->assertTrue($container->hasDefinition(TransactionInterface::class));
        $this->assertTrue($container->hasDefinition(UserRepositoryReadOnly::class));
        $this->assertTrue($container->hasDefinition(UserRepositoryWriteable::class));
        $this->assertTrue($container->hasDefinition(UserAddressRepositoryReadOnly::class));
        $this->assertTrue($container->hasDefinition(UserAddressRepositoryWriteable::class));
        $this->assertEquals(10, \count($container->getDefinitions())); // + service container

        // Make sure the services are correctly wired
        $repository = $container->getDefinition(UserRepositoryReadOnly::class);
        $this->assertEquals(UserRepositoryReadOnly::class, $repository->getClass());

        $arguments = $repository->getArguments();
        $this->assertEquals(1, \count($arguments));

        $baseRepository = $arguments[0];
        $this->assertEquals(RepositoryReadOnly::class, $baseRepository->getClass());

        $baseArguments = $baseRepository->getArguments();
        $this->assertEquals(2, \count($baseArguments));
        $this->assertEquals(new Reference(DBInterface::class), $baseArguments[0]);
        $this->assertEquals(
            new Definition(RepositoryConfig::class, $this->getUserRepositoryConfig()),
            $baseArguments[1],
        );
    }

    public function testNoWriteRepository(): void
    {
        $container = new ContainerBuilder();
        $configuration = [
            0 =>
                [
                    'directories' =>
                        [
                            0 => __DIR__ . '/TestEntities2',
                            1 => __DIR__ . '/../src',
                        ],
                ],
        ];

        $this->loadExtension($container, $configuration);

        $this->assertTrue($container->hasDefinition(MultiRepositoryReadOnlyInterface::class));
        $this->assertTrue($container->hasDefinition(MultiRepositoryWriteableInterface::class));
        $this->assertTrue($container->hasDefinition(MultiRepositoryBuilderReadOnlyInterface::class));
        $this->assertTrue($container->hasDefinition(MultiRepositoryBuilderWriteableInterface::class));
        $this->assertTrue($container->hasDefinition(TransactionInterface::class));
        $this->assertTrue($container->hasDefinition(UserRepositoryReadOnly2::class));
        $this->assertEquals(7, \count($container->getDefinitions())); // + service container
    }

    public function testOverwriteTableNameAndConnection(): void
    {
        $container = new ContainerBuilder();
        $configuration = [
            0 =>
                [
                    'directories' =>
                        [
                            0 => __DIR__ . '/TestEntities',
                            1 => __DIR__ . '/../src',
                        ],
                    'table_names' =>
                        [
                            User::class => 'database.sometable',
                        ],
                    'connection_names' =>
                        [
                            UserAddress::class => 'custom_connection',
                        ],
                ],
        ];

        $this->loadExtension($container, $configuration);

        $this->assertTrue($container->hasDefinition(MultiRepositoryReadOnlyInterface::class));
        $this->assertTrue($container->hasDefinition(MultiRepositoryWriteableInterface::class));
        $this->assertTrue($container->hasDefinition(MultiRepositoryBuilderReadOnlyInterface::class));
        $this->assertTrue($container->hasDefinition(MultiRepositoryBuilderWriteableInterface::class));
        $this->assertTrue($container->hasDefinition(TransactionInterface::class));
        $this->assertTrue($container->hasDefinition('squirrel.transaction.custom_connection'));
        $this->assertTrue($container->hasDefinition(UserRepositoryReadOnly::class));
        $this->assertTrue($container->hasDefinition(UserRepositoryWriteable::class));
        $this->assertTrue($container->hasDefinition(UserAddressRepositoryReadOnly::class));
        $this->assertTrue($container->hasDefinition(UserAddressRepositoryWriteable::class));
        $this->assertEquals(11, \count($container->getDefinitions())); // + service container

        // Make sure the services are correctly wired
        $repository = $container->getDefinition(UserRepositoryReadOnly::class);
        $this->assertEquals(UserRepositoryReadOnly::class, $repository->getClass());

        $arguments = $repository->getArguments();
        $this->assertEquals(1, \count($arguments));

        $baseRepository = $arguments[0];
        $this->assertEquals(RepositoryReadOnly::class, $baseRepository->getClass());

        $userConfig = $this->getUserRepositoryConfig();
        $userConfig[1] = 'database.sometable';

        $baseArguments = $baseRepository->getArguments();
        $this->assertEquals(2, \count($baseArguments));
        $this->assertEquals(new Reference(DBInterface::class), $baseArguments[0]);
        $this->assertEquals(
            new Definition(RepositoryConfig::class, $userConfig),
            $baseArguments[1],
        );

        // Make sure the services are correctly wired
        $repository = $container->getDefinition(UserAddressRepositoryWriteable::class);
        $this->assertEquals(UserAddressRepositoryWriteable::class, $repository->getClass());

        $arguments = $repository->getArguments();
        $this->assertEquals(1, \count($arguments));

        $baseRepository = $arguments[0];
        $this->assertEquals(RepositoryWriteable::class, $baseRepository->getClass());

        $addressConfig = $this->getUserAddressRepositoryConfig();
        $addressConfig[0] = 'custom_connection';

        $baseArguments = $baseRepository->getArguments();
        $this->assertEquals(2, \count($baseArguments));
        $this->assertEquals(new Reference('squirrel.connection.custom_connection'), $baseArguments[0]);
        $this->assertEquals(
            new Definition(RepositoryConfig::class, $addressConfig),
            $baseArguments[1],
        );
    }

    protected function loadExtension(ContainerBuilder $container, array $configs): void
    {
        $extension = new SquirrelEntitiesExtension(
            new EntityProcessor(),
            new FindClassesWithAttribute(),
        );

        $extension->load($configs, $container);
    }

    protected function getUserRepositoryConfig(): array
    {
        return [
            '',
            'users',
            [
                'user_id' => 'userId',
                'active' => 'active',
                'user_name' => 'userName',
                'login_name_md5' => 'loginNameMD5',
                'login_password' => 'loginPassword',
                'email_address' => 'emailAddress',
                'balance' => 'balance',
                'location_id' => 'locationId',
                'create_date' => 'createDate',
            ],
            [
                'userId' => 'user_id',
                'active' => 'active',
                'userName' => 'user_name',
                'loginNameMD5' => 'login_name_md5',
                'loginPassword' => 'login_password',
                'emailAddress' => 'email_address',
                'balance' => 'balance',
                'locationId' => 'location_id',
                'createDate' => 'create_date',
            ],
            User::class,
            [
                'userId' => 'int',
                'active' => 'bool',
                'userName' => 'string',
                'loginNameMD5' => 'string',
                'loginPassword' => 'string',
                'emailAddress' => 'string',
                'balance' => 'float',
                'locationId' => 'int',
                'createDate' => 'int',
            ],
            [
                'userId' => false,
                'active' => false,
                'userName' => false,
                'loginNameMD5' => false,
                'loginPassword' => false,
                'emailAddress' => false,
                'balance' => false,
                'locationId' => true,
                'createDate' => false,
            ],
            'user_id',
        ];
    }

    protected function getUserAddressRepositoryConfig(): array
    {
        return [
            '',
            'users_address',
            [
                'user_id' => 'userId',
                'at_home' => 'atHome',
                'street_name' => 'streetName',
                'street_number' => 'streetNumber',
                'city' => 'city',
                'picture' => 'picture',
            ],
            [
                'userId' => 'user_id',
                'atHome' => 'at_home',
                'streetName' => 'street_name',
                'streetNumber' => 'street_number',
                'city' => 'city',
                'picture' => 'picture',
            ],
            UserAddress::class,
            [
                'userId' => 'int',
                'atHome' => 'bool',
                'streetName' => 'string',
                'streetNumber' => 'string',
                'city' => 'string',
                'picture' => 'blob',
            ],
            [
                'userId' => false,
                'atHome' => false,
                'streetName' => false,
                'streetNumber' => false,
                'city' => false,
                'picture' => false,
            ],
            '',
        ];
    }
}
