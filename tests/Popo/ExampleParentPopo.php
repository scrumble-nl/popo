<?php

declare(strict_types=1);

namespace Tests\Popo;

use Scrumble\Popo\BasePopo;
use Scrumble\Popo\HasPopoFactory;
use Tests\Factory\ExampleParentPopoFactory;

class ExampleParentPopo extends BasePopo
{
    use HasPopoFactory;

    /**
     * @var string
     */
    public string $name;

    /**
     * @var ExamplePopo
     */
    public ExamplePopo $popo;

    public function __construct(string $name, ExamplePopo $popo)
    {
        $this->name = $name;
        $this->popo = $popo;
    }

    /**
     * @return string
     */
    public static function popoFactory(): string
    {
        return ExampleParentPopoFactory::class;
    }
}
