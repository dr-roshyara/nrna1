<?php

namespace App\Http\Middleware;

use Illuminate\Http\Middleware\TrustProxies as Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * Which proxies may tell us the client IP (PBDIGIT-287, Human PO 2026-10-13: a single reverse proxy; trust only it,
 * from configuration, never '*'). Every IP protection reads $request->ip(), so this decides what they see.
 *
 * - trustedproxy.proxies (env TRUSTED_PROXIES): comma-separated addresses or CIDR ranges. Not set → no proxy is
 *   trusted and the connecting address is the client.
 * - A wildcard ('*', '**' or Laravel's 'REMOTE_ADDR', "whoever connects") is refused with a warning and trusts no
 *   proxy; the framework would otherwise believe any sender's X-Forwarded-For.
 * - Only the X-Forwarded-For/-Host/-Port/-Proto headers are read; the standard Forwarded header is not.
 */
class TrustProxies extends Middleware
{
    private const WILDCARDS = ['*', '**', 'REMOTE_ADDR'];

    /**
     * The headers that should be used to detect proxies.
     *
     * @var int
     */
    protected $headers = Request::HEADER_X_FORWARDED_FOR | Request::HEADER_X_FORWARDED_HOST | Request::HEADER_X_FORWARDED_PORT | Request::HEADER_X_FORWARDED_PROTO;

    protected function setTrustedProxyIpAddresses(Request $request)
    {
        $this->setTrustedProxyIpAddressesToSpecificIps($request, $this->configuredProxies());
    }

    /** @return list<string> */
    private function configuredProxies(): array
    {
        $configured = config('trustedproxy.proxies');
        $entries = is_string($configured) ? explode(',', $configured) : (is_array($configured) ? $configured : []);
        $proxies = array_values(array_filter(array_map(fn ($entry) => is_string($entry) ? trim($entry) : '', $entries), fn ($entry) => $entry !== ''));

        if (array_intersect($proxies, self::WILDCARDS) !== []) {
            Log::warning('trusted_proxies_wildcard_refused');

            return [];
        }

        return $proxies;
    }
}
