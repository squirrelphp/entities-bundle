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
    use Squirrel\Entities\RepositoryBuilderReadOnlyInterface;
    use Squirrel\Entities\RepositoryReadOnlyInterface;

    class UserRepositoryReadOnly implements RepositoryBuilderReadOnlyInterface
    {
        private RepositoryReadOnlyInterface $repository;

        public function __construct(RepositoryReadOnlyInterface $repository)
        {
            $this->repository = $repository;
        }

        public function count(): \Squirrel\Entities\Action\CountEntries
        {
            return new \Squirrel\Entities\Action\CountEntries($this->repository);
        }

        public function select(): \Squirrel\Entities\Action\SquirrelEntitiesBundleTestsTestEntitiesUser\SelectEntries
        {
            return new \Squirrel\Entities\Action\SquirrelEntitiesBundleTestsTestEntitiesUser\SelectEntries($this->repository);
        }
    }
}

namespace Squirrel\Entities\Action\SquirrelEntitiesBundleTestsTestEntitiesUser {
    /**
     * This class exists to have proper type hints about the object(s) returned in the
     * getEntries and getOneEntry functions. All calls are delegated to the
     * SelectEntries class - because of the builder pattern we cannot extend SelectEntries
     * (because then returning self would return that class instead of this extended class)
     * so we instead imitate it. This way the implementation in SelectEntries can change
     * and this generated class has no ties to how it "works" or how the repository is used.
     *
     * @implements \IteratorAggregate<int,\Squirrel\EntitiesBundle\Tests\TestEntities\User>
     */
    class SelectEntries implements \Squirrel\Entities\Action\ActionInterface, \IteratorAggregate
    {
        private \Squirrel\Entities\Action\SelectEntries $selectImplementation;

        public function __construct(\Squirrel\Entities\RepositoryReadOnlyInterface $repository)
        {
            $this->selectImplementation = new \Squirrel\Entities\Action\SelectEntries($repository);
        }

        public function field(string $onlyGetThisField): self
        {
            $this->selectImplementation->field($onlyGetThisField);
            return $this;
        }

        /**
         * @param string[] $onlyGetTheseFields
         */
        public function fields(array $onlyGetTheseFields): self
        {
            $this->selectImplementation->fields($onlyGetTheseFields);
            return $this;
        }

        /**
         * @param array<int|string,mixed> $whereClauses
         */
        public function where(array $whereClauses): self
        {
            $this->selectImplementation->where($whereClauses);
            return $this;
        }

        /**
         * @param array<int|string,string>|string $orderByClauses
         */
        public function orderBy($orderByClauses): self
        {
            $this->selectImplementation->orderBy($orderByClauses);
            return $this;
        }

        public function startAt(int $startAtNumber): self
        {
            $this->selectImplementation->startAt($startAtNumber);
            return $this;
        }

        public function limitTo(int $numberOfEntries): self
        {
            $this->selectImplementation->limitTo($numberOfEntries);
            return $this;
        }

        public function blocking(bool $active = true): self
        {
            $this->selectImplementation->blocking($active);
            return $this;
        }

        /**
         * @return \Squirrel\EntitiesBundle\Tests\TestEntities\User[]
         */
        public function getAllEntries(): array
        {
            return $this->selectImplementation->getAllEntries();
        }

        public function getOneEntry(): ?\Squirrel\EntitiesBundle\Tests\TestEntities\User
        {
            $entry = $this->selectImplementation->getOneEntry();

            if ($entry instanceof \Squirrel\EntitiesBundle\Tests\TestEntities\User || $entry === null) {
                return $entry;
            }

            throw new \LogicException('Unexpected type encountered - wrong repository might be configured: ' . \get_class($entry));
        }

        /**
         * @return array<bool|int|float|string|null>
         */
        public function getFlattenedFields(): array
        {
            return $this->selectImplementation->getFlattenedFields();
        }

        public function getIterator(): SelectIterator
        {
            return new SelectIterator($this->selectImplementation->getIterator());
        }
    }

    /**
     * @implements \Iterator<int,\Squirrel\EntitiesBundle\Tests\TestEntities\User>
     */
    class SelectIterator implements \Squirrel\Entities\Action\ActionInterface, \Iterator
    {
        private \Squirrel\Entities\Action\SelectIterator $iteratorInstance;

        public function __construct(\Squirrel\Entities\Action\SelectIterator $iterator)
        {
            $this->iteratorInstance = $iterator;
        }

        public function current(): \Squirrel\EntitiesBundle\Tests\TestEntities\User
        {
            return $this->iteratorInstance->current();
        }

        public function next(): void
        {
            $this->iteratorInstance->next();
        }

        public function key(): int
        {
            return $this->iteratorInstance->key();
        }

        public function valid(): bool
        {
            return $this->iteratorInstance->valid();
        }

        public function rewind(): void
        {
            $this->iteratorInstance->rewind();
        }

        public function clear(): void
        {
            $this->iteratorInstance->clear();
        }
    }
}
// @codeCoverageIgnoreEnd
