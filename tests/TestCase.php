<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use RuntimeException;

abstract class TestCase extends BaseTestCase
{
    protected function refreshApplication(): void
    {
        parent::refreshApplication();

        $this->guardAgainstUnsafeTestingDatabase();
    }

    private function guardAgainstUnsafeTestingDatabase(): void
    {
        if (! $this->app->environment('testing')) {
            return;
        }

        $connection = config('database.default');
        $database = config("database.connections.{$connection}.database");

        if ($connection !== 'sqlite' || $database !== ':memory:') {
            throw new RuntimeException(
                'Unsafe test database configuration detected. Tests must use sqlite :memory: to avoid wiping local MySQL data.'
            );
        }
    }
}
