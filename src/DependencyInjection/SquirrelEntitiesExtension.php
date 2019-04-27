<?php

namespace Squirrel\EntitiesBundle\DependencyInjection;

use Squirrel\Entities\Annotation\EntityProcessor;
use Squirrel\Entities\Generate\FindClassesWithAnnotation;
use Squirrel\Entities\MultiRepositoryBuilderReadOnly;
use Squirrel\Entities\MultiRepositoryBuilderReadOnlyInterface;
use Squirrel\Entities\MultiRepositoryBuilderWriteable;
use Squirrel\Entities\MultiRepositoryBuilderWriteableInterface;
use Squirrel\Entities\MultiRepositoryReadOnly;
use Squirrel\Entities\MultiRepositoryReadOnlyInterface;
use Squirrel\Entities\MultiRepositoryWriteable;
use Squirrel\Entities\MultiRepositoryWriteableInterface;
use Squirrel\Entities\RepositoryConfig;
use Squirrel\Entities\RepositoryReadOnly;
use Squirrel\Entities\RepositoryWriteable;
use Squirrel\Entities\Transaction;
use Squirrel\Entities\TransactionInterface;
use Squirrel\Queries\DBInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\Finder\Finder;

/**
 * Loads bundle configuration and auto-configures repositories as services
 */
class SquirrelEntitiesExtension extends Extension
{
    /**
     * @var EntityProcessor
     */
    private $entityProcessor;

    /**
     * @var FindClassesWithAnnotation
     */
    private $identifyEntityClasses;

    public function __construct(
        EntityProcessor $entityProcessor,
        FindClassesWithAnnotation $findClassesWithAnnotation
    ) {
        // Entity annotation processor to find repository config
        $this->entityProcessor = $entityProcessor;

        // Looks through a PHP file to find possible entity classes
        $this->identifyEntityClasses = $findClassesWithAnnotation;
    }

    /**
     * @inheritdoc
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        // Load and merge configuration
        $configuration = $this->getConfiguration([], $container);
        $config = $this->processConfiguration($configuration, $configs);

        // Looks for entities so we can add their repositories and create transaction services
        $this->findEntitiesAndProcess($container, $config);

        // Add multi repositories services to container
        $this->createMultiRepositoryServices($container);
    }

    /**
     * @inheritdoc
     */
    public function getConfiguration(array $config, ContainerBuilder $container)
    {
        return new Configuration($this->getAlias());
    }

    /**
     * @inheritdoc
     */
    public function getAlias()
    {
        return 'squirrel_entities';
    }

    private function findEntitiesAndProcess(ContainerBuilder $container, $config)
    {
        // No directories defined - this is mandatory
        if (\count($config['directories'] ?? []) === 0) {
            return;
        }

        // Collect used connections - to create transaction services further down
        $connectionNames = [];

        // Get all possible entity classes with our annotation
        $classes = $this->findAllEntityClassesInFilesystem($config['directories']);

        // Go through the possible entity classes
        foreach ($classes as $class) {
            // Divvy up the namespace and the class name
            $namespace = $class[0];
            $className = $class[1];
            $fullClassName = $namespace . '\\' . $className;

            // Get repository config as object
            $repositoryConfig = $this->entityProcessor->process($fullClassName);

            // Repository config found - this is an entity
            if (isset($repositoryConfig)) {
                $connectionNames[] = $this->createRepositoryServicesForEntity(
                    $container,
                    $repositoryConfig,
                    $config['connection_names'][$fullClassName] ?? null,
                    $config['table_names'][$fullClassName] ?? null
                );
            }
        }

        // Create a transaction service for each connection
        $this->createTransactionServices($container, $connectionNames);
    }

    private function findAllEntityClassesInFilesystem(array $directories)
    {
        $entityClasses = [];

        // Go through directories
        foreach ($directories as $directory) {
            // Find the files in the directory
            $sourceFinder = new Finder();
            $sourceFinder->in($directory)->files()->name('*.php');

            // Go through files which were found
            foreach ($sourceFinder as $file) {
                // Safety check because Finder can return false if the file was not found
                if ($file->getRealPath()===false) {
                    throw new \InvalidArgumentException('File in source directory not found');
                }

                // Get file contents
                $fileContents = \file_get_contents($file->getRealPath());

                // Another safety check because file_get_contents can return false if the file was not found
                if ($fileContents===false) {
                    throw new \InvalidArgumentException('File in source directory could not be retrieved');
                }

                // Get all possible entity classes with our annotation
                $entityClasses = \array_merge(
                    $entityClasses,
                    $this->identifyEntityClasses->__invoke($fileContents)
                );
            }
        }

        return $entityClasses;
    }

    private function createRepositoryServicesForEntity(
        ContainerBuilder $container,
        RepositoryConfig $repositoryConfig,
        ?string $customConnectionName,
        ?string $customTableName
    ): ?string {
        // Connection can be overwritten in configuration
        $connectionName = isset($customConnectionName)
            ? $customConnectionName
            : $repositoryConfig->getConnectionName();

        // No ReadOnly repository - exit early
        if (!\class_exists($repositoryConfig->getObjectClass() . 'RepositoryReadOnly')) {
            return $connectionName;
        }

        // Table name can be overwritten in configuration
        $tableName = isset($customTableName)
            ? $customTableName
            : $repositoryConfig->getTableName();

        // Create repository config definition
        $repositoryConfigDef = new Definition(
            RepositoryConfig::class,
            [
                $connectionName,
                $tableName,
                $repositoryConfig->getTableToObjectFields(),
                $repositoryConfig->getObjectToTableFields(),
                $repositoryConfig->getObjectClass(),
                $repositoryConfig->getObjectTypes(),
                $repositoryConfig->getObjectTypesNullable(),
            ]
        );

        // Convention for squirrel connection services
        $dbReference = 'squirrel.connection.' . $connectionName;

        // No connection name set - use default class type hint
        if (\strlen($connectionName) === 0) {
            $dbReference = DBInterface::class;
        }

        $container->setDefinition(
            $repositoryConfig->getObjectClass() . 'RepositoryReadOnly',
            new Definition(
                $repositoryConfig->getObjectClass() . 'RepositoryReadOnly',
                [
                    new Definition(RepositoryReadOnly::class, [
                        new Reference($dbReference),
                        $repositoryConfigDef,
                    ]),
                ]
            )
        );

        // No writeable repository exists
        if (!\class_exists($repositoryConfig->getObjectClass() . 'RepositoryWriteable')) {
            return $connectionName;
        }

        $container->setDefinition(
            $repositoryConfig->getObjectClass() . 'RepositoryWriteable',
            new Definition(
                $repositoryConfig->getObjectClass() . 'RepositoryWriteable',
                [
                    new Definition(RepositoryWriteable::class, [
                        new Reference($dbReference),
                        $repositoryConfigDef,
                    ]),
                ]
            )
        );

        return $connectionName;
    }

    private function createTransactionServices(ContainerBuilder $container, array $connectionNames)
    {
        foreach ($connectionNames as $connectionName) {
            // Skip connections where no repositories were created
            if (\is_null($connectionName)) {
                continue;
            }

            // Default service name conventions
            $serviceName = 'squirrel.transaction.' . $connectionName;
            $connectionService = 'squirrel.connection.' . $connectionName;

            // No connection name means it is the default connection
            if (\strlen($connectionName) === 0) {
                $serviceName = TransactionInterface::class;
                $connectionService = DBInterface::class;
            }

            $container->setDefinition(
                $serviceName,
                new Definition(Transaction::class, [new Reference($connectionService)])
            );
        }
    }

    private function createMultiRepositoryServices(ContainerBuilder $container)
    {
        // Base multi repository services
        $container->setDefinition(
            MultiRepositoryReadOnlyInterface::class,
            new Definition(MultiRepositoryReadOnly::class)
        );
        $container->setDefinition(
            MultiRepositoryWriteableInterface::class,
            new Definition(MultiRepositoryWriteable::class)
        );

        // Builder multi repository services
        $container->setDefinition(
            MultiRepositoryBuilderReadOnlyInterface::class,
            new Definition(
                MultiRepositoryBuilderReadOnly::class,
                [new Reference(MultiRepositoryReadOnlyInterface::class)]
            )
        );
        $container->setDefinition(
            MultiRepositoryBuilderWriteableInterface::class,
            new Definition(
                MultiRepositoryBuilderWriteable::class,
                [new Reference(MultiRepositoryWriteableInterface::class)]
            )
        );
    }
}
