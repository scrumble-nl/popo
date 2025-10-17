<?php

declare(strict_types=1);

namespace Scrumble\Popo\Traits;

use BackedEnum;
use Illuminate\Support\Arr;

trait ToSnakeCaseArray
{
    /**
     * @return array<array-key, mixed>
     */
    public function toSnakeCaseArray(): array
    {
        $array = $this->toArray();
        $result = [];

        foreach ($array as $key => $value) {
            $result[snake_case($key)] = $this->parseToArrayValue($value);
        }

        return $result;
    }

    /**
     * @param  mixed|array $value
     * @return mixed
     */
    private function parseToArrayValue(mixed $value): mixed
    {
        if (is_array($value)) {
            return Arr::mapWithKeys($value, fn ($item, $key) => [snake_case($key) => $this->parseToArrayValue($item)]);
        }

        return $value;
    }
}
