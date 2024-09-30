<?php

declare(strict_types=1);

namespace Scrumble\Popo\Contracts;

interface SnakeCaseArrayable
{
    /**
     * @return array<array-key, mixed>
     */
    public function toSnakeCaseArray(): array;
}
