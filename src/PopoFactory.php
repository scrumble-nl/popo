<?php

declare(strict_types=1);

namespace Scrumble\Popo;

use ReflectionClass;
use ReflectionMethod;
use ReflectionException;
use ReflectionParameter;
use InvalidArgumentException;
use Illuminate\Support\Collection;
use Illuminate\Foundation\Testing\WithFaker;
use Scrumble\Popo\Exception\ClassNotDefinedException;
use Scrumble\Popo\Exception\InvalidPopoClassException;

abstract class PopoFactory
{
    use WithFaker;

    /**
     * @var null|string
     */
    public ?string $popoClass = null;

    /**
     * @var int
     */
    private int $count = 1;

    /**
     * @var array<array-key, mixed>
     */
    private array $sequence = [];

    /**
     * @var int
     */
    private int $sequenceIndex = 0;

    /**
     * @var array<string, BasePopo|Collection<int, BasePopo>|string>
     */
    private array $attributes = [];

    /**
     * @var array<array-key, mixed>
     */
    private array $state = [];

    /**
     * @var bool
     */
    private bool $isInMultiple = false;

    /**
     * {@internal}.
     */
    public function __construct()
    {
        $this->setUpFaker();
    }

    /**
     * @param  array<string, mixed>      $attributes
     * @throws ClassNotDefinedException
     * @throws InvalidPopoClassException
     * @throws ReflectionException
     * @return mixed
     */
    public function create(array $attributes = []): mixed
    {
        $popoClass = $this->popoClass;

        if (!$popoClass) {
            $className = self::class;

            throw new ClassNotDefinedException("The \$popoClass property could not be found in the factory {$className}");
        }

        if ($this->isMultiple() && !$this->isInMultiple) {
            return $this->createMultiple($attributes);
        }

        return new $popoClass(...array_values($this->getAttributes($attributes)));
    }

    /**
     * @param  array<string, mixed>                          $attributes
     * @throws InvalidPopoClassException|ReflectionException
     * @return array<int|string, mixed>
     */
    public function raw(array $attributes = []): array
    {
        if ($this->isMultiple() && !$this->isInMultiple) {
            return $this->rawMultiple();
        }

        return $this->getAttributes($attributes);
    }

    /**
     * @param  int   $count
     * @return $this
     */
    public function count(int $count): PopoFactory
    {
        $this->count = $count;

        return $this;
    }

    /**
     * @param  array<array-key, array<array-key, mixed>> ...$arrays
     * @return $this
     */
    public function sequence(array ...$arrays): PopoFactory
    {
        $this->sequence = $arrays;

        return $this;
    }

    /**
     * @param  array<array-key, mixed>|callable $callback
     * @return $this
     */
    public function state(array|callable $callback): PopoFactory
    {
        if (is_array($callback)) {
            $this->state = $callback;
        } else {
            $this->state = $callback($this->attributes);
        }

        return $this;
    }

    /**
     * @return array<string, mixed>
     */
    abstract public function definition(): array;

    /**
     * @param array<array-key, mixed> $attributes
     * @return Collection<int, mixed>
     * @throws ClassNotDefinedException
     * @throws InvalidPopoClassException
     * @throws ReflectionException
     */
    private function createMultiple(array $attributes = []): Collection
    {
        $this->isInMultiple = true;
        $collection = Collection::make();

        for ($i = 0, $total = $this->count; $i < $total; ++$i) {
            $collection->push($this->create($attributes));
        }

        $this->isInMultiple = false;

        return $collection;
    }

    /**
     * @throws InvalidPopoClassException
     * @throws ReflectionException
     * @return array<int, array<string, mixed>>|array<string, mixed>
     */
    private function rawMultiple(): array
    {
        $this->isInMultiple = true;
        $array = [];

        for ($i = 0, $total = $this->count; $i < $total; ++$i) {
            $array[] = $this->raw();
        }

        $this->isInMultiple = false;

        return $array;
    }

    /**
     * @param  array<array-key, mixed>                     $attributes
     * @throws InvalidPopoClassException
     * @throws ReflectionException
     * @return array<array-key, mixed>
     */
    private function getAttributes(array $attributes): array
    {
        $className = self::class;

        if (!$this->popoClass) {
            throw new InvalidPopoClassException("Could not find the popo class for {$className}");
        }

        /** @var class-string<BasePopo> $popoClass */
        $popoClass = $this->popoClass;
        $reflection = new ReflectionClass($popoClass);
        $constructor = $reflection->getConstructor();

        if (!$constructor) {
            throw new InvalidPopoClassException("Could not build a popo from {$className}");
        }

        $defaults = $this->getDefaults($constructor, $attributes);
        $this->attributes = $defaults;

        if ($this->sequence) {
            ++$this->sequenceIndex;
        }

        return $defaults;
    }

    /**
     * @param  ReflectionParameter       $parameter
     * @param  array<array-key, mixed>                     $attributes
     * @throws ClassNotDefinedException
     * @throws InvalidPopoClassException
     * @throws ReflectionException
     * @return mixed
     */
    private function getParameterDefault(ReflectionParameter $parameter, array $attributes): mixed
    {
        $parameterName = $parameter->getName();

        if (array_key_exists($parameterName, $this->state)) {
            return $this->state[$parameterName];
        }

        if (array_key_exists($parameterName, $attributes)) {
            return $attributes[$parameterName];
        }

        if ($this->hasSequence($parameterName)) {
            return $this->sequence[$this->sequenceIndex][$parameterName];
        }

        if (array_key_exists($parameterName, $this->definition())) {
            $definition = $this->definition()[$parameterName];

            if ($definition instanceof PopoFactory) {
                return $definition->create();
            }

            return $definition;
        }

        if ($parameter->isDefaultValueAvailable()) {
            return $parameter->getDefaultValue();
        }

        throw new InvalidArgumentException("Missing required attribute '{$parameter->getName()}'");
    }

    /**
     * @param  ReflectionMethod          $constructor
     * @param  array<array-key, mixed>                     $attributes
     * @throws InvalidPopoClassException
     * @throws ReflectionException
     * @return array<array-key, mixed>
     */
    private function getDefaults(ReflectionMethod $constructor, array $attributes): array
    {
        $parameters = $constructor->getParameters();
        $defaults = [];

        foreach ($parameters as $parameter) {
            $defaults[$parameter->getName()] = $this->getParameterDefault($parameter, $attributes);
        }

        return $defaults;
    }

    /**
     * @param  null|string $sequenceKey
     * @return bool
     */
    private function hasSequence(?string $sequenceKey = null): bool
    {
        $sequenceKeyExists = ($sequenceKey && (!empty($this->sequence[$this->sequenceIndex])
            && array_key_exists($sequenceKey, $this->sequence[$this->sequenceIndex])));

        return $this->sequence && $sequenceKeyExists;
    }

    /**
     * @return bool
     */
    private function isMultiple(): bool
    {
        return $this->count > 1;
    }
}
