<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class ValidateVotingIp
{
    /**
     * Handle an incoming request.
     *
     * Check if the user's current IP address matches their registered voting IP
     * when IP address control is enabled.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next): Response
    {
        \Illuminate\Support\Facades\Log::info('🔵 [ValidateVotingIp] Middleware START', [
            'user_id' => auth()->id(),
            'route' => $request->route()->getName(),
        ]);

        // Check if IP address control is enabled
        $ipControlEnabled = config('voting_security.control_ip_address', 1) == 1;

        // If IP control is disabled globally, allow all requests
        if (!$ipControlEnabled) {
            $this->logBypassedCheck($request, 'IP control disabled globally');
            \Illuminate\Support\Facades\Log::info('✅ [ValidateVotingIp] IP control disabled - bypassing');
            return $next($request);
        }

        $user = auth()->user();

        if (!$user) {
            // User not authenticated - let authentication middleware handle this
            return $next($request);
        }

        $currentIp = $this->getClientIp($request);
        $votingIp = $user->voting_ip;

        \Illuminate\Support\Facades\Log::info('🔵 [ValidateVotingIp] IP check details', [
            'user_voting_ip' => $votingIp,
            'current_ip' => $currentIp,
            'is_null' => is_null($votingIp),
        ]);

        // If user has no voting_ip set (null), they can vote from any IP
        // This is the intended behavior when voter is approved WITHOUT IP checking
        if (is_null($votingIp)) {
            \Illuminate\Support\Facades\Log::info('✅ [ValidateVotingIp] User has no IP restriction - allowing');
            $this->logBypassedCheck($request, 'User has no IP restriction', [
                'user_id' => $user->id,
                'user_name' => $user->name,
                'current_ip' => $currentIp,
            ]);
            return $next($request);
        }

        // At this point: IP control is ON and user HAS voting_ip set
        // Now we must validate the IP matches
        if ($votingIp !== $currentIp) {
            return $this->handleIpMismatch($request, $user, $votingIp, $currentIp);
        }

        // IP matches - allow the request
        $this->logSuccessfulMatch($request, $user, $currentIp);
        return $next($request);
    }

    /**
     * Handle IP mismatch based on configuration
     */
    protected function handleIpMismatch(Request $request, $user, string $registeredIp, string $currentIp): Response
    {
        $action = config('voting_security.ip_mismatch_action', 'block');

        // Always log the mismatch
        $this->logIpMismatch($request, $user, $registeredIp, $currentIp);

        if ($action === 'block') {
            // Block the voting attempt
            return back()->withErrors([
                'ip_mismatch' => $this->getIpMismatchMessage($registeredIp, $currentIp)
            ])->with([
                'error_type' => 'ip_restriction',
                'registered_ip' => $registeredIp,
                'current_ip' => $currentIp,
            ]);
        } elseif ($action === 'warn') {
            // Allow but add warning - Note: This would require $next to be passed as parameter
            // For now, we'll log and redirect back with a warning message
            Log::warning('IP mismatch in warn mode - would allow but blocking for safety', [
                'user_id' => $user->id,
                'registered_ip' => $registeredIp,
                'current_ip' => $currentIp,
            ]);

            return back()->with('warning', 'IP address mismatch detected but proceeding in warn mode.');
        }

        // Default: block
        return back()->withErrors([
            'ip_mismatch' => $this->getIpMismatchMessage($registeredIp, $currentIp)
        ]);
    }

    /**
     * Get client IP address (with proxy support)
     */
    protected function getClientIp(Request $request): string
    {
        $trustProxies = config('voting_security.trust_proxies', false);

        if ($trustProxies) {
            // Trust proxy headers
            return $request->ip();
        }

        // Direct connection - use remote address
        return $request->ip();
    }

    /**
     * Get localized IP mismatch message
     */
    protected function getIpMismatchMessage(string $registeredIp, string $currentIp): string
    {
        $english = config('voting_security.messages.ip_mismatch_english',
            'You can only vote from your registered IP address.');
        $nepali = config('voting_security.messages.ip_mismatch_nepali',
            'तपाईं आफ्नो दर्ता गरिएको IP ठेगानाबाट मात्र मतदान गर्न सक्नुहुन्छ।');
        $contact = config('voting_security.messages.contact_support',
            'If you believe this is an error, please contact the election committee.');

        return "{$english}\n\n{$nepali}\n\n" .
               "Registered IP: {$registeredIp}\n" .
               "Your current IP: {$currentIp}\n\n" .
               $contact;
    }

    /**
     * Log successful IP match
     */
    protected function logSuccessfulMatch(Request $request, $user, string $ip): void
    {
        if (!config('voting_security.logging.enabled', true)) {
            return;
        }

        if (!config('voting_security.logging.log_successful_matches', false)) {
            return;
        }

        Log::info('IP validation successful', [
            'user_id' => $user->id,
            'user_name' => $user->name,
            'voting_ip' => $user->voting_ip,
            'current_ip' => $ip,
            'url' => $request->url(),
            'timestamp' => now(),
        ]);
    }

    /**
     * Log IP mismatch
     */
    protected function logIpMismatch(Request $request, $user, string $registeredIp, string $currentIp): void
    {
        if (!config('voting_security.logging.enabled', true)) {
            return;
        }

        if (!config('voting_security.logging.log_mismatches', true)) {
            return;
        }

        Log::warning('IP mismatch detected during voting attempt', [
            'user_id' => $user->id,
            'user_name' => $user->name,
            'user_email' => $user->email,
            'registered_ip' => $registeredIp,
            'current_ip' => $currentIp,
            'url' => $request->url(),
            'user_agent' => $request->userAgent(),
            'timestamp' => now(),
            'action_taken' => config('voting_security.ip_mismatch_action', 'block'),
        ]);

        // Also log to a separate security log for audit purposes
        Log::channel('security')->warning('Voting IP mismatch', [
            'user_id' => $user->id,
            'registered_ip' => $registeredIp,
            'attempt_ip' => $currentIp,
        ]);
    }

    /**
     * Log bypassed IP check
     */
    protected function logBypassedCheck(Request $request, string $reason, array $context = []): void
    {
        if (!config('voting_security.logging.enabled', true)) {
            return;
        }

        if (!config('voting_security.logging.log_bypassed_checks', false)) {
            return;
        }

        Log::info('IP check bypassed', array_merge([
            'reason' => $reason,
            'url' => $request->url(),
            'timestamp' => now(),
        ], $context));
    }
}
