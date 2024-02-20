<?php

declare(strict_types=1);

namespace Tests\Mock;

use Scrumble\Popo\BasePopo;

class ArrayPopo extends BasePopo
{
    /**
     * @var string
     */
    public string $firstName;

    /**
     * @param string $firstName
     */
    public function __construct(string $firstName)
    {
        $this->firstName = $firstName;
    }
}
