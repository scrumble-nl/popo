<?php

declare(strict_types=1);

namespace Scrumble\Popo;

use UnitEnum;
use JsonSerializable;
use Scrumble\Popo\Traits\ToSnakeCaseArray;
use Illuminate\Contracts\Support\Arrayable;
use Scrumble\Popo\Contracts\SnakeCaseArrayable;

/**
 * @implements Arrayable<string, mixed>
 */
class BasePopo implements JsonSerializable, Arrayable, SnakeCaseArrayable
{
    use ToSnakeCaseArray;

    /**
     * @return static
     */
    public function jsonSerialize(): static
    {
        return $this;
    }

    /**
     * @return array<array-key, mixed>
     */
    public function toArray(): array
    {
        $array = get_object_vars($this);

        foreach ($array as $property => $value) {
            if ($value instanceof UnitEnum) {
                // @phpstan-ignore-next-line
                $array[$property] = $value->value;

                continue;
            }

            if (!is_scalar($value) && null !== $value && !is_array($value) && method_exists($value, 'toArray')) {
                $array[$property] = $value->toArray();
            }
        }

        return $array;
    }

    /**
     * @return array<array-key, mixed>
     * @deprecated Use toSnakeCaseArray instead
     */
    public function toTestArray(): array
    {
        $properties = [];

        foreach ($this->toArray() as $property => $value) {
            $properties[snake_case($property)] = $value;
        }

        return $properties;
    }
}
