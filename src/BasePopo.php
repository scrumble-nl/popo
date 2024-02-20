<?php

declare(strict_types=1);

namespace Scrumble\Popo;

use UnitEnum;
use JsonSerializable;
use Illuminate\Contracts\Support\Arrayable;

/**
 * @implements Arrayable<string, mixed>
 */
class BasePopo implements JsonSerializable, Arrayable
{
    /**
     * @return static
     */
    public function jsonSerialize(): static
    {
        return $this;
    }

    /**
     * @return array<string, mixed>
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
     * @return array
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
