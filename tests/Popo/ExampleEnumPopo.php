<?php

declare(strict_types=1);

namespace Tests\Popo;

use Scrumble\Popo\BasePopo;
use Scrumble\Popo\HasPopoFactory;
use Tests\Factory\ExamplePopoFactory;

class ExampleEnumPopo extends BasePopo
{
    use HasPopoFactory;


    /**
     * @param string $name
     * @param string $content
     */
    public function __construct(
        public ExampleEnum $a,
        public ExampleEnum $b,
    ) {
    }

    /**
     * @return string
     */
    public static function popoFactory(): string
    {
        return ExamplePopoFactory::class;
    }
}
