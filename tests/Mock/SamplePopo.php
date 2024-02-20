<?php

declare(strict_types=1);

namespace Tests\Mock;

use Scrumble\Popo\BasePopo;

class SamplePopo extends BasePopo
{
    /**
     * @var string
     */
    public string $name;

    /**
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->name = $name;
    }
}
