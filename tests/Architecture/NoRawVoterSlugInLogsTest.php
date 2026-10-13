<?php

declare(strict_types=1);

namespace Tests\Architecture;

use PHPUnit\Framework\TestCase;

/**
 * PBDIGIT-274 S9c (plan section 12, O-7 = B, L-11): no raw voting link in logs.
 *
 * A voter's link /v/{vslug}/… is a bearer secret. In the files that handle voting links (the section 12.2
 * inventory, docs/publicdigit/election/evidence/2026-10-13-pbdigit274-s9c-log-inventory/), no log call may carry
 * - a voter-slug value ($voterSlug->slug, $vslug->slug, $slug->slug, …) or route('vslug') / the route parameters;
 * - a request URL or path (url(), fullUrl(), path(), getRequestUri()) or a redirect target (getTargetUrl());
 * - a variable holding a voting URL ($agreementUrl, $voteUrl, $codeCreateUrl, …) or a route('slug.…') URL.
 * Log the slug's database id, the route name or the election id instead. A context array built in a variable is
 * covered by the second rule: no `'url' => $request->url()`-style entry anywhere in these files.
 *
 * Static and limited by design: it reads log statements (balanced parentheses, multi-line). The behaviour of the
 * real paths is proven by tests/Isolated/Pbdigit274/Database/RawSlugLoggingTest.php.
 */
final class NoRawVoterSlugInLogsTest extends TestCase
{
    /** The files with live sites in the section 12.2 inventory. */
    public const FILES = [
        'bootstrap/app.php',
        'app/Http/Controllers/Demo/DemoCodeController.php',
        'app/Http/Controllers/Demo/DemoVoteController.php',
        'app/Http/Controllers/Election/ElectionManagementController.php',
        'app/Http/Controllers/VoterSlugController.php',
        'app/Http/Controllers/CodeController.php',
        'app/Http/Controllers/VoteController.php',
        'app/Http/Middleware/EnsureVoterStepOrder.php',
        'app/Http/Middleware/ValidateVotingIp.php',
        'app/Http/Middleware/EnsureRealVoteOrganisation.php',
        'app/Traits/EnsuresVoterMembership.php',
        'app/Http/Middleware/CheckVotingWindow.php',
    ];

    private const FORBIDDEN_IN_LOG_CALLS = [
        'voter-slug value' => '/\$\w*slug\w*\??->slug\b/i',
        'route(\'vslug\')' => '/route\(\s*[\'"]vslug[\'"]\s*\)/',
        'route parameters' => '/route\(\)\??->parameters\(/',
        'request URL / path' => '/(->|\brequest\(\)->)(url|fullUrl|path|getRequestUri)\(\)/',
        'redirect target' => '/getTargetUrl\(\)/',
        'voting-URL variable' => '/\$\w*Url\b/',
        'slug route URL' => '/route\(\s*[\'"]slug\./',
        'vslug key' => '/[\'"]vslug[\'"]\s*=>/',
    ];

    /** Context arrays are often built before the log call (e.g. ValidateVotingIp's $divergence). */
    private const FORBIDDEN_ANYWHERE = [
        "'url' entry from the request" => '/[\'"](url|target_url|full_url|path)[\'"]\s*=>\s*(\$request|request\(\))->(url|fullUrl|path|getRequestUri)\(\)/',
    ];

    public function test_no_log_call_in_the_voting_link_files_carries_a_raw_link(): void
    {
        $root = dirname(__DIR__, 2);
        $violations = [];

        foreach (self::FILES as $relative) {
            $source = file_get_contents($root.'/'.$relative);
            $this->assertIsString($source, $relative);

            foreach (self::logCalls($source) as [$line, $statement]) {
                foreach (self::FORBIDDEN_IN_LOG_CALLS as $what => $pattern) {
                    if (preg_match($pattern, $statement)) {
                        $violations[] = "{$relative}:{$line} {$what}";
                    }
                }
            }
            foreach (self::FORBIDDEN_ANYWHERE as $what => $pattern) {
                if (preg_match_all($pattern, $source, $m, PREG_OFFSET_CAPTURE)) {
                    foreach ($m[0] as [, $offset]) {
                        $violations[] = $relative.':'.(substr_count(substr($source, 0, $offset), "\n") + 1)." {$what}";
                    }
                }
            }
        }

        $this->assertSame([], array_values(array_unique($violations)), count($violations).' raw-link log site(s)');
    }

    /** @return list<array{int, string}> [line, full statement] of every Log::/\Log::/logger(/->log( call */
    public static function logCalls(string $source): array
    {
        $calls = [];
        preg_match_all('/(\\\\?\bLog::(?:channel\([^)]*\)->)?\w+|\blogger\(|->log\()/', $source, $m, PREG_OFFSET_CAPTURE);
        foreach ($m[0] as [$token, $offset]) {
            $open = strpos($source, '(', $offset + strlen($token) - 1);
            if ($open === false) {
                continue;
            }
            $depth = 0;
            for ($i = $open, $n = strlen($source); $i < $n; $i++) {
                if ($source[$i] === '(') {
                    $depth++;
                } elseif ($source[$i] === ')' && --$depth === 0) {
                    break;
                }
            }
            $calls[] = [substr_count(substr($source, 0, $offset), "\n") + 1, substr($source, $offset, $i - $offset + 1)];
        }

        return $calls;
    }
}
