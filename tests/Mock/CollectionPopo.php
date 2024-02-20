<?php

declare(strict_types=1);

namespace Tests\Mock;

use Scrumble\Popo\BasePopo;
use Illuminate\Support\Collection;

class CollectionPopo extends BasePopo
{
    /**
     * @var Collection<int, SamplePopo>
     */
    public Collection $samples;

    /**
     * @var int
     */
    public int $number;

    /**
     * @var null|string
     */
    public ?string $nullable = null;

    /**
     * @var array
     */
    public array $array = [];

    /**
     * @var string
     */
    private string $thisIsPrivate;

    /**
     * @param string                      $thisIsPrivate
     * @param Collection<int, SamplePopo> $samples
     * @param int                         $number
     */
    public function __construct(string $thisIsPrivate, Collection $samples, int $number)
    {
        $this->thisIsPrivate = $thisIsPrivate;
        $this->samples = $samples;
        $this->number = $number;
    }
}
