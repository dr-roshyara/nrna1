<?php

declare(strict_types=1);

namespace App\Contexts\Contestation\Infrastructure\Transaction;

use App\Contexts\Contestation\Application\Port\TransactionManager;
use Illuminate\Support\Facades\DB;

final class LaravelTransactionManager implements TransactionManager
{
    public function transactional(callable $work): mixed
    {
        return DB::transaction(static fn () => $work());
    }
}
