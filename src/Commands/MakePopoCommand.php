<?php

declare(strict_types=1);

namespace Scrumble\Popo\Commands;

use Illuminate\Console\GeneratorCommand;

class MakePopoCommand extends GeneratorCommand
{
    /**
     * {@inheritDoc}
     */
    protected $signature = 'make:popo {name}';

    /**
     * {@inheritDoc}
     */
    protected $description = 'Create a Popo';

    /**
     * {@inheritDoc}
     */
    protected $type = 'popo';

    /**
     * {@inheritDoc}
     */
    protected function getDefaultNamespace($rootNamespace): string
    {
        return "{$rootNamespace}/Popo";
    }

    /**
     * {@inheritDoc}
     */
    protected function replaceClass($stub, $name): string
    {
        return parent::replaceClass($stub, $this->getClassname());
    }

    /**
     * {@inheritDoc}
     */
    protected function getStub(): string
    {
        return __DIR__ . '/../stubs/popo.stub';
    }

    /**
     * @return string
     */
    private function getClassname(): string
    {
        /** @var string $name */
        $name = $this->argument('name');

        if (str_ends_with(strtolower($name), 'popo')) {
            $name = substr($name, 0, strlen($name) - 4);
        }

        return "{$name}Popo";
    }
}
