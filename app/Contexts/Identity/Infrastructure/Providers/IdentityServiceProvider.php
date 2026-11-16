<?php

declare(strict_types=1);

namespace App\Contexts\Identity\Infrastructure\Providers;

use App\Contexts\Identity\Application\Ports\AccountDirectory;
use App\Contexts\Identity\Application\Ports\ContextPersonRefs;
use App\Contexts\Identity\Application\Ports\CurrentAccount;
use App\Contexts\Identity\Application\Ports\ClaimCodes;
use App\Contexts\Identity\Application\Ports\ClaimStore;
use App\Contexts\Identity\Application\Ports\IssuedReferences;
use App\Contexts\Identity\Application\Ports\RequestTime;
use App\Contexts\Identity\Application\Ports\RedemptionThrottle;
use App\Contexts\Identity\Application\Ports\SessionActorRefs;
use App\Contexts\Identity\Infrastructure\LaravelCurrentAccount;
use App\Contexts\Identity\Infrastructure\Outbox\BindingRefusedHydrator;
use App\Contexts\Identity\Infrastructure\Outbox\ClaimRedeemedHydrator;
use App\Contexts\Identity\Infrastructure\PostgresRedemptionThrottle;
use App\Contexts\Shared\Infrastructure\Outbox\EventHydratorRegistry;
use App\Contexts\Identity\Infrastructure\NoActingRefYet;
use App\Contexts\Identity\Infrastructure\PostgresClaims;
use App\Contexts\Identity\Infrastructure\PostgresIssuedReferences;
use App\Contexts\Identity\Infrastructure\RandomClaimCodes;
use App\Contexts\Identity\Infrastructure\SystemRequestTime;
use App\Contexts\Identity\Infrastructure\PostgresAccountDirectory;
use App\Contexts\Identity\Infrastructure\PostgresContextPersonRefs;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;

/**
 * Identity's ports (D-225 IG-1 … IG-3; bound in PBDIGIT-346 G-4f, deferred from G-0). The CENTRAL (default)
 * connection: Identity is central (D-222: Laravel's default connection stays central).
 */
final class IdentityServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(CurrentAccount::class, static fn (): LaravelCurrentAccount => new LaravelCurrentAccount(Auth::guard()));
        $this->app->bind(AccountDirectory::class, static fn (): PostgresAccountDirectory => new PostgresAccountDirectory(DB::connection()));
        $this->app->bind(ContextPersonRefs::class, static fn (): PostgresContextPersonRefs => new PostgresContextPersonRefs(DB::connection()));
        // PBDIGIT-349 I-1 (I-TDD-02 §4; P-I2 = L1)
        $this->app->bind(IssuedReferences::class, static fn (): PostgresIssuedReferences => new PostgresIssuedReferences(DB::connection()));
        // PBDIGIT-349 I-2 (I-TDD-02 §6.2, §10.1). R-1 does not exist before I-4: NoActingRefYet refuses every claim
        // operation in production (fail closed). Replace that ONE binding with Identity's R-1 adapter in I-4.
        $this->app->bind(ClaimStore::class, static fn (): PostgresClaims => new PostgresClaims(DB::connection()));
        $this->app->bind(ClaimCodes::class, RandomClaimCodes::class);
        $this->app->bind(RequestTime::class, SystemRequestTime::class);
        $this->app->bind(SessionActorRefs::class, NoActingRefYet::class);
        // PBDIGIT-349 I-3 (I-TDD-02 §14, INV-ID-9). Limits from config/identity.php (values [TU], to be confirmed).
        $this->app->bind(RedemptionThrottle::class, static fn (): PostgresRedemptionThrottle => new PostgresRedemptionThrottle(DB::connection(),
            (int) config('identity.redemption_throttle.per_code'), (int) config('identity.redemption_throttle.per_client'),
            (int) config('identity.redemption_throttle.window_seconds'), self::throttleSecret()));
    }

    /**
     * Review R-2: the client-key HMAC secret. The dedicated setting if present, else derived from APP_KEY (a separate,
     * purpose-bound key: HMAC of a fixed label under APP_KEY, so APP_KEY itself is never used directly). Empty if
     * neither exists: the throttle then refuses every redemption (fail closed).
     */
    private static function throttleSecret(): string
    {
        $dedicated = (string) config('identity.redemption_throttle.client_key_secret');
        if ($dedicated !== '') {
            return $dedicated;
        }
        $appKey = (string) config('app.key');

        return $appKey === '' ? '' : hash_hmac('sha256', 'pbdigit349-identity-redemption-throttle-client-key', $appKey);
    }

    public function boot(): void
    {
        // PBDIGIT-349 I-3: the published events Identity writes to its own outbox, identity_outbox_events (C-I3, C-I6)
        $registry = $this->app->make(EventHydratorRegistry::class);
        $registry->register(new ClaimRedeemedHydrator());
        $registry->register(new BindingRefusedHydrator());
    }
}
