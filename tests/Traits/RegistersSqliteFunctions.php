<?php

namespace Tests\Traits;

use Illuminate\Support\Facades\DB;

trait RegistersSqliteFunctions
{
    /**
     * Register MySQL-compatible functions for SQLite testing.
     * Call this in setUp() for any test that may trigger MySQL-specific SQL functions.
     */
    protected function registerSqliteFunctions(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            $pdo = DB::connection()->getPdo();
            $pdo->sqliteCreateFunction('YEAR', function ($date) {
                return $date ? date('Y', strtotime($date)) : null;
            }, 1);
            $pdo->sqliteCreateFunction('MONTH', function ($date) {
                return $date ? date('m', strtotime($date)) : null;
            }, 1);
        }
    }
}
