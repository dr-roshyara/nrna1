<?php

declare(strict_types=1);

namespace App\Contexts\Adjudication\Infrastructure\Transaction;

use App\Contexts\Adjudication\Application\Port\TransactionManager;
use Illuminate\Support\Facades\DB;

final class LaravelTransactionManager implements TransactionManager
{
    public function transactional(callable $work): mixed
    {
        return DB::transaction(static fn () => $work());
    }
}
