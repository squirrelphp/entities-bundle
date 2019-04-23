<?php

namespace Squirrel\EntitiesBundle\DependencyInjection;

use Doctrine\Common\Annotations\AnnotationReader;
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
class ContainerExtension extends Extension
{
    /**
     * @inheritdoc
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        // Load and merge configuration
        $configuration = $this->getConfiguration([], $container);
        $config = $this->processConfiguration($configuration, $configs);

        // Load explicitely configured directories
        $directories = $config['directories'] ?? [];

        if (count($directories) > 0) {
            // Initialize entity processor to find repository config
            $entityProcessor = new EntityProcessor(new AnnotationReader());

            // Looks through a PHP file to find possible entity classes
            $findEntityClasses = new FindClassesWithAnnotation();

            // Collect used connections - to create transaction services further down
            $connectionNames = [];

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
                    $classes = $findEntityClasses->__invoke($fileContents);

                    // Go through the possible entity classes
                    foreach ($classes as $class) {
                        // Divvy up the namespace and the class name
                        $namespace = $class[0];
                        $className = $class[1];

                        // Get repository config as object
                        $repositoryConfig = $entityProcessor->process($namespace . '\\' . $className);

                        // Repository config found - this is an entity
                        if (isset($repositoryConfig)) {
                            // Connection can be overwritten in configuration
                            $connectionName =
                                isset($config['connection_names'][$className]) ?
                                    $config['connection_names'][$className] :
                                    $repositoryConfig->getConnectionName();

                            // Table name can be overwritten in configuration
                            $tableName =
                                isset($config['table_names'][$className]) ?
                                    $config['table_names'][$className] :
                                    $repositoryConfig->getTableName();

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
                                    true,
                                ]
                            );

                            // Specific connection set for this repository
                            if (strlen($connectionName) > 0) {
                                $dbReference = 'squirrel.connection.' . $connectionName;
                                $connectionNames[] = $connectionName;
                            } else { // No connection set - use default connection
                                $dbReference = DBInterface::class;
                                $connectionNames[] = DBInterface::class;
                            }

                            // ReadOnly builder repository exists
                            if (class_exists($className . 'RepositoryReadOnly')) {
                                $builderRepositoryReadOnlyDefinition = new Definition(
                                    $className . 'RepositoryReadOnly',
                                    [
                                        new Definition(RepositoryReadOnly::class, [
                                            new Reference($dbReference),
                                            $repositoryConfigDef,
                                        ]),
                                    ]
                                );

                                $container->setDefinition(
                                    $className . 'RepositoryReadOnly',
                                    $builderRepositoryReadOnlyDefinition
                                );

                                // Writeable builder repository exists
                                if (class_exists($className . 'RepositoryWriteable')) {
                                    $builderRepositoryWriteableDefinition = new Definition(
                                        $className . 'RepositoryWriteable',
                                        [
                                            new Definition(RepositoryWriteable::class, [
                                                new Reference($dbReference),
                                                $repositoryConfigDef,
                                            ]),
                                        ]
                                    );

                                    $container->setDefinition(
                                        $className . 'RepositoryWriteable',
                                        $builderRepositoryWriteableDefinition
                                    );
                                }
                            }
                        }
                    }
                }
            }

            // Create a transaction service for each connection
            foreach ($connectionNames as $connectionName) {
                if ($connectionName !== DBInterface::class) {
                    $serviceName = 'squirrel.transaction.' . $connectionName;
                    $connectionService = 'squirrel.connection.' . $connectionName;
                } else {
                    $serviceName = TransactionInterface::class;
                    $connectionService = DBInterface::class;
                }

                $container->setDefinition(
                    $serviceName,
                    new Definition(Transaction::class, [new Reference($connectionService)])
                );
            }

            // Query handler read-only definition
            $rawMultiRepositoryReadOnlyDef = new Definition(MultiRepositoryReadOnly::class);
            $rawMultiRepositoryReadOnlyDef->setPublic(false);

            // Query handler read-and-write definition
            $rawMultiRepositoryReadWriteDef = new Definition(MultiRepositoryWriteable::class);
            $rawMultiRepositoryReadWriteDef->setPublic(false);

            // Make query handler available through raw query interface names
            $container->setDefinition(MultiRepositoryReadOnlyInterface::class, $rawMultiRepositoryReadOnlyDef);
            $container->setDefinition(MultiRepositoryWriteableInterface::class, $rawMultiRepositoryReadWriteDef);

            // Multi-repository read-only query handler definition
            $multiRepositoryReadOnlyDef = new Definition(
                MultiRepositoryBuilderReadOnly::class,
                [$rawMultiRepositoryReadOnlyDef]
            );
            $multiRepositoryReadOnlyDef->setPublic(false);

            // Make multi-repository read-only query handler available through interface
            $container->setDefinition(MultiRepositoryBuilderReadOnlyInterface::class, $multiRepositoryReadOnlyDef);

            // Multi-repository read-write query handler definition
            $multiRepositoryReadWriteDef = new Definition(
                MultiRepositoryBuilderWriteable::class,
                [$rawMultiRepositoryReadWriteDef]
            );
            $multiRepositoryReadWriteDef->setPublic(false);

            // Make multi-repository read-write query handler available through interface
            $container->setDefinition(MultiRepositoryBuilderWriteableInterface::class, $multiRepositoryReadWriteDef);
        }
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
}
