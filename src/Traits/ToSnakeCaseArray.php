<?php

declare(strict_types=1);

namespace Scrumble\Popo\Traits;

use UnitEnum;
use Illuminate\Contracts\Support\Arrayable;
use Scrumble\Popo\Contracts\SnakeCaseArrayable;

trait ToSnakeCaseArray
{
    /**
     * @return array<array-key, mixed>
     */
    public function toSnakeCaseArray(): array
    {
        $array = get_object_vars($this);
        $result = [];

        foreach ($array as $key => $value) {
            $result[snake_case($key)] = $this->parseToArrayValue($value);
        }

        return $result;
    }

    /**
     * @param  mixed $value
     * @return mixed
     */
    private function parseToArrayValue(mixed $value): mixed
    {
        if ($value instanceof UnitEnum) {
            // @phpstan-ignore-next-line
            return $value->value;
        }

        if (!is_scalar($value) && null !== $value && !is_array($value)) {
            return $this->getSnakeCaseArrayableItems($value);
        }

        return $value;
    }

    /**
     * @param  mixed       $value
     * @return array|mixed
     */
    private function getSnakeCaseArrayableItems(mixed $value): mixed
    {
        if ($value instanceof SnakeCaseArrayable) {
            return $value->toSnakeCaseArray();
        }

        if ($value instanceof Arrayable) {
            return $value->toArray();
        }

        return $value;
    }
}
