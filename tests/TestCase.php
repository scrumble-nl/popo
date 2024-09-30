<?php

declare(strict_types=1);

namespace Tests;

use Scrumble\Popo\PopoServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

/**
 * @internal
 */
class TestCase extends OrchestraTestCase
{
    /**
     * {@inheritDoc}
     */
    public function seed($class = 'DatabaseSeeder')
    {
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    protected function getPackageProviders($app): array
    {
        return [
            PopoServiceProvider::class,
        ];
    }
}
