<?php
// phpcs:ignoreFile -- created by SquirrelPHP library, do not alter
/*
 * THIS FILE IS AUTOMATICALLY CREATED - DO NOT EDIT, DO NOT COMMIT TO VCS
 *
 * IF YOU DELETE THE ENTITY (Squirrel\EntitiesBundle\Tests\TestEntities2\UserAddress)
 * THEN PLEASE DELETE THIS FILE - IT WILL NO LONGER BE NEEDED
 *
 * Generated by Squirrel\Entities\Generate\RepositoriesGenerateCommand,
 * this file will be overwritten when that command is executed again, if your
 * entity still exists at that time
 */
// @codeCoverageIgnoreStart

namespace Squirrel\EntitiesBundle\Tests\TestEntities2 {
    use Squirrel\Entities\RepositoryBuilderWriteableInterface;
    use Squirrel\Entities\RepositoryWriteableInterface;

    class UserAddressRepositoryWriteable extends UserAddressRepositoryReadOnly implements
        RepositoryBuilderWriteableInterface
    {
        public function __construct(private RepositoryWriteableInterface $repository)
        {
            parent::__construct($repository);
        }

        public function insert(): \Squirrel\Entities\Builder\InsertEntry
        {
            return new \Squirrel\Entities\Builder\InsertEntry($this->repository);
        }

        public function insertOrUpdate(): \Squirrel\Entities\Builder\InsertOrUpdateEntry
        {
            return new \Squirrel\Entities\Builder\InsertOrUpdateEntry($this->repository);
        }

        public function update(): \Squirrel\Entities\Builder\UpdateEntries
        {
            return new \Squirrel\Entities\Builder\UpdateEntries($this->repository);
        }

        public function delete(): \Squirrel\Entities\Builder\DeleteEntries
        {
            return new \Squirrel\Entities\Builder\DeleteEntries($this->repository);
        }
    }
}
// @codeCoverageIgnoreEnd
