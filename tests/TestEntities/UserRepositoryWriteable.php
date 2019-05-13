<?php
// phpcs:ignoreFile -- created by SquirrelPHP library, do not alter
/*
 * THIS FILE IS AUTOMATICALLY CREATED - DO NOT EDIT, DO NOT COMMIT TO VCS
 *
 * IF YOU DELETE THE ENTITY (Squirrel\EntitiesBundle\Tests\TestEntities\User)
 * THEN PLEASE DELETE THIS FILE - IT WILL NO LONGER BE NEEDED
 *
 * Generated by Squirrel\Entities\Generate\RepositoriesGenerateCommand,
 * this file will be overwritten when that command is executed again, if your
 * entity still exists at that time
 */
// @codeCoverageIgnoreStart

namespace Squirrel\EntitiesBundle\Tests\TestEntities {
    use Squirrel\Entities\RepositoryBuilderWriteableInterface;
    use Squirrel\Entities\RepositoryWriteableInterface;

    class UserRepositoryWriteable extends UserRepositoryReadOnly implements
        RepositoryBuilderWriteableInterface
    {
        /**
         * @var RepositoryWriteableInterface
         */
        private $repository;

        public function __construct(RepositoryWriteableInterface $repository)
        {
            $this->repository = $repository;
            parent::__construct($repository);
        }

        public function insert(): \Squirrel\Entities\Action\InsertEntry
        {
            return new \Squirrel\Entities\Action\InsertEntry($this->repository);
        }

        public function insertOrUpdate(): \Squirrel\Entities\Action\InsertOrUpdateEntry
        {
            return new \Squirrel\Entities\Action\InsertOrUpdateEntry($this->repository);
        }

        public function update(): \Squirrel\Entities\Action\UpdateEntries
        {
            return new \Squirrel\Entities\Action\UpdateEntries($this->repository);
        }

        public function delete(): \Squirrel\Entities\Action\DeleteEntries
        {
            return new \Squirrel\Entities\Action\DeleteEntries($this->repository);
        }
    }
}
// @codeCoverageIgnoreEnd
