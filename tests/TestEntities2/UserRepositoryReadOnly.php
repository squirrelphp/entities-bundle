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

namespace Squirrel\EntitiesBundle\Tests\TestEntities2 {
    use Squirrel\Entities\RepositoryBuilderReadOnlyInterface;
    use Squirrel\Entities\RepositoryReadOnlyInterface;

    class UserRepositoryReadOnly implements RepositoryBuilderReadOnlyInterface
    {
        public function __construct(private RepositoryReadOnlyInterface $repository)
        {
        }

        public function count(): \Squirrel\Entities\Builder\CountEntries
        {
            return new \Squirrel\Entities\Builder\CountEntries($this->repository);
        }

        public function select(): \Squirrel\Entities\Builder\SquirrelEntitiesBundleTestsTestEntities2User\SelectEntries
        {
            return new \Squirrel\Entities\Builder\SquirrelEntitiesBundleTestsTestEntities2User\SelectEntries($this->repository);
        }
    }
}

namespace Squirrel\Entities\Builder\SquirrelEntitiesBundleTestsTestEntities2User {
    /**
     * @implements \Iterator<int,\Squirrel\EntitiesBundle\Tests\TestEntities\User>
     */
    class SelectIterator implements \Squirrel\Queries\Builder\BuilderInterface, \Iterator
    {
        private \Squirrel\Entities\Builder\SelectIterator $iteratorInstance;

        public function __construct(\Squirrel\Entities\Builder\SelectIterator $iterator)
        {
            $this->iteratorInstance = $iterator;
        }

        public function current(): \Squirrel\EntitiesBundle\Tests\TestEntities\User
        {
            $entry = $this->iteratorInstance->current();

            if ($entry instanceof \Squirrel\EntitiesBundle\Tests\TestEntities\User) {
                return $entry;
            }

            throw new \LogicException('Unexpected type encountered - wrong repository might be configured: ' . \get_class($entry));
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
    class SelectEntries implements \Squirrel\Queries\Builder\BuilderInterface, \IteratorAggregate
    {
        private \Squirrel\Entities\Builder\SelectEntries $selectImplementation;

        public function __construct(\Squirrel\Entities\RepositoryReadOnlyInterface $repository)
        {
            $this->selectImplementation = new \Squirrel\Entities\Builder\SelectEntries($repository);
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
        public function orderBy(array|string $orderByClauses): self
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

        /**
         * @return int[]
         */
        public function getFlattenedIntegerFields(): array
        {
            return $this->selectImplementation->getFlattenedIntegerFields();
        }

        /**
         * @return float[]
         */
        public function getFlattenedFloatFields(): array
        {
            return $this->selectImplementation->getFlattenedFloatFields();
        }

        /**
         * @return string[]
         */
        public function getFlattenedStringFields(): array
        {
            return $this->selectImplementation->getFlattenedStringFields();
        }

        /**
         * @return bool[]
         */
        public function getFlattenedBooleanFields(): array
        {
            return $this->selectImplementation->getFlattenedBooleanFields();
        }

        public function getIterator(): SelectIterator
        {
            return new SelectIterator($this->selectImplementation->getIterator());
        }
    }
}
// @codeCoverageIgnoreEnd
