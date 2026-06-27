<?php

namespace App\Domain\Election\Constitution;

/**
 * Immutable snapshot of constitutional participation semantics.
 *
 * Captures only participation legitimacy rules at election creation.
 * Once created, this snapshot is frozen forever for replay determinism.
 *
 * NOT included (operational, mutable):
 * - election title, description, UI labels
 * - admin notes, banners, localization
 * - scheduling adjustments, deadline extensions
 *
 * ONLY included (constitutional, frozen):
 * - network binding strategy (IP rules)
 * - device attestation requirements
 * - verification protocols
 * - trust thresholds and elevation rules
 * - authorization protocols
 */
final readonly class ConstitutionalArticlesSnapshot
{
    public function __construct(
        public string $networkBindingStrategy,
        public int $maxVotesPerIp,
        public string $deviceBindingStrategy,
        public string $ballotAuthorizationProtocol,
        public bool $trustOverlayActive,
        public ?string $trustOverlayPriority,
        public ?string $trustOverlayReason,
    ) {}

    /**
     * Serialize to JSON for immutable storage.
     * Order matters for hash consistency.
     */
    public function toJson(): string
    {
        return json_encode(
            [
                'network_binding_strategy' => $this->networkBindingStrategy,
                'max_votes_per_ip' => $this->maxVotesPerIp,
                'device_binding_strategy' => $this->deviceBindingStrategy,
                'ballot_authorization_protocol' => $this->ballotAuthorizationProtocol,
                'trust_overlay_active' => $this->trustOverlayActive,
                'trust_overlay_priority' => $this->trustOverlayPriority,
                'trust_overlay_reason' => $this->trustOverlayReason,
            ],
            JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
        );
    }

    /**
     * Compute constitutional hash for tamper detection.
     */
    public function constitutionalHash(): string
    {
        return hash('sha256', $this->toJson());
    }

    /**
     * Reconstruct from JSON (for loading from database).
     */
    public static function fromJson(string $json): self
    {
        $data = json_decode($json, true);

        return new self(
            networkBindingStrategy: $data['network_binding_strategy'],
            maxVotesPerIp: $data['max_votes_per_ip'],
            deviceBindingStrategy: $data['device_binding_strategy'],
            ballotAuthorizationProtocol: $data['ballot_authorization_protocol'],
            trustOverlayActive: $data['trust_overlay_active'],
            trustOverlayPriority: $data['trust_overlay_priority'],
            trustOverlayReason: $data['trust_overlay_reason'],
        );
    }
}
