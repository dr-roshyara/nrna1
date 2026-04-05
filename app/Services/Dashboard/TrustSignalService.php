<?php

namespace App\Services\Dashboard;

use App\DataTransferObjects\UserStateData;

/**
 * Trust Signal Service - Translate-First Architecture
 *
 * Provides contextual trust signals based on user role, action, and region.
 * Returns translation keys instead of hard-coded text - the presentation layer
 * (Vue frontend) is responsible for translating using vue-i18n.
 *
 * This separation of concerns ensures:
 * - Backend: Logic for WHICH signals to show
 * - Frontend: Responsibility for TRANSLATING and RENDERING signals
 * - Translation files: Centralized i18n management
 */
class TrustSignalService
{
    /**
     * Get applicable trust signals for a user
     *
     * Returns array of signals with translation keys instead of hard-coded text.
     * Frontend uses $t() to translate using keys like 'trust_signals.compliance.message'
     *
     * @param UserStateData $userState
     * @return array
     */
    public function getSignalsForUser(UserStateData $userState): array
    {
        $signals = [];

        // Always show basic compliance
        $signals[] = $this->getComplianceSignal($userState);

        // Add role-specific signals
        $signals = array_merge($signals, $this->getRoleSpecificSignals($userState));

        // Add state-specific signals
        $signals = array_merge($signals, $this->getStateSpecificSignals($userState));

        return array_filter($signals); // Remove nulls
    }

    /**
     * Get primary compliance signal (always shown)
     *
     * Returns translation keys for message and tooltip:
     * - message_key: 'trust_signals.compliance.message'
     * - tooltip_key: 'trust_signals.compliance.tooltip'
     */
    private function getComplianceSignal(UserStateData $userState): array
    {
        return [
            'id' => 'compliance',
            'type' => 'compliance',
            'level' => 1,
            'icon' => '✓',
            // Translation keys - Frontend will use $t() to translate
            'message_key' => 'trust_signals.compliance.message',
            'tooltip_key' => 'trust_signals.compliance.tooltip',
            'link' => '/compliance/details',
            'priority' => 1,
        ];
    }

    /**
     * Get role-specific trust signals
     *
     * Returns signals with translation keys for each role:
     * - Admin: security, audit signals
     * - Voter: encryption, verification signals
     * - Commission: transparency signals
     */
    private function getRoleSpecificSignals(UserStateData $userState): array
    {
        $signals = [];

        // Admin signals (managing elections, members)
        if (in_array('admin', $userState->roles)) {
            $signals[] = [
                'id' => 'security',
                'type' => 'security',
                'level' => 2,
                'icon' => '🔒',
                'message_key' => 'trust_signals.security.message',
                'tooltip_key' => 'trust_signals.security.tooltip',
                'link' => '/compliance/hosting',
                'priority' => 2,
            ];

            $signals[] = [
                'id' => 'audit',
                'type' => 'audit',
                'level' => 2,
                'icon' => '📋',
                'message_key' => 'trust_signals.audit.message',
                'tooltip_key' => 'trust_signals.audit.tooltip',
                'link' => '/organization/audit-log',
                'priority' => 3,
            ];
        }

        // Voter signals (voting security)
        if (in_array('voter', $userState->roles)) {
            $signals[] = [
                'id' => 'encryption',
                'type' => 'encryption',
                'level' => 2,
                'icon' => '🔐',
                'message_key' => 'trust_signals.encryption.message',
                'tooltip_key' => 'trust_signals.encryption.tooltip',
                'link' => '/security/encryption',
                'priority' => 2,
            ];

            $signals[] = [
                'id' => 'verification',
                'type' => 'verification',
                'level' => 2,
                'icon' => '✓',
                'message_key' => 'trust_signals.verification.message',
                'tooltip_key' => 'trust_signals.verification.tooltip',
                'link' => '/help/vote-verification',
                'priority' => 3,
            ];
        }

        // Commission signals
        if (in_array('commission', $userState->roles)) {
            $signals[] = [
                'id' => 'transparency',
                'type' => 'transparency',
                'level' => 2,
                'icon' => '👁️',
                'message_key' => 'trust_signals.transparency.message',
                'tooltip_key' => 'trust_signals.transparency.tooltip',
                'link' => '/help/commission',
                'priority' => 2,
            ];
        }

        return $signals;
    }

    /**
     * Get state-specific trust signals based on onboarding progress
     *
     * Returns signals with translation keys based on user state:
     * - New users: support signals
     * - Setup in progress: data protection signals
     * - Setup complete: ready signals
     */
    private function getStateSpecificSignals(UserStateData $userState): array
    {
        $signals = [];

        // New user - emphasize getting started safely
        if ($userState->is_new_user) {
            $signals[] = [
                'id' => 'support',
                'type' => 'support',
                'level' => 1,
                'icon' => '👥',
                'message_key' => 'trust_signals.support.message',
                'tooltip_key' => 'trust_signals.support.tooltip',
                'link' => '/help/contact',
                'priority' => 4,
            ];
        }

        // Setup in progress - emphasize data protection during setup
        if (in_array('admin', $userState->roles) && $userState->onboarding_step >= 2 && $userState->onboarding_step < 5) {
            $signals[] = [
                'id' => 'data_protection',
                'type' => 'data_protection',
                'level' => 2,
                'icon' => '🛡️',
                'message_key' => 'trust_signals.data_protection.message',
                'tooltip_key' => 'trust_signals.data_protection.tooltip',
                'link' => '/compliance/member-data',
                'priority' => 3,
            ];
        }

        // Setup complete - emphasize ready for elections
        if (in_array('admin', $userState->roles) && $userState->onboarding_step === 5) {
            $signals[] = [
                'id' => 'ready',
                'type' => 'ready',
                'level' => 3,
                'icon' => '✅',
                'message_key' => 'trust_signals.ready.message',
                'tooltip_key' => 'trust_signals.ready.tooltip',
                'link' => '/organization/elections',
                'priority' => 1,
            ];
        }

        return $signals;
    }

    /**
     * Get trust score (1-5) indicating overall trust level
     *
     * @param UserStateData $userState
     * @return int
     */
    public function calculateTrustScore(UserStateData $userState): int
    {
        $score = 3; // Default: medium trust

        // Increase for established users
        if ($userState->confidence_score > 70) {
            $score = 5;
        } elseif ($userState->confidence_score > 40) {
            $score = 4;
        }

        // Increase for multi-role users (experienced with platform)
        if ($userState->has_multiple_roles) {
            $score = min(5, $score + 1);
        }

        return min(5, max(1, $score));
    }

    /**
     * Get trust badge for display
     *
     * Returns trust badge with translation keys instead of hard-coded text.
     * Frontend uses $t() to translate using keys like 'trust_signals.badge.maximum.message'
     *
     * @param UserStateData $userState
     * @return array
     */
    public function getTrustBadge(UserStateData $userState): array
    {
        $score = $this->calculateTrustScore($userState);

        return match ($score) {
            5 => [
                'level' => 'maximum',
                'icon' => '✓✓✓',
                'message_key' => 'trust_signals.badge.maximum.message',
                'color' => 'green',
            ],
            4 => [
                'level' => 'high',
                'icon' => '✓✓',
                'message_key' => 'trust_signals.badge.high.message',
                'color' => 'green',
            ],
            3 => [
                'level' => 'medium',
                'icon' => '✓',
                'message_key' => 'trust_signals.badge.medium.message',
                'color' => 'blue',
            ],
            2 => [
                'level' => 'low',
                'icon' => '○',
                'message_key' => 'trust_signals.badge.low.message',
                'color' => 'gray',
            ],
            default => [
                'level' => 'minimal',
                'icon' => '?',
                'message_key' => 'trust_signals.badge.minimal.message',
                'color' => 'gray',
            ],
        };
    }
}
