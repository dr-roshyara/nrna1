<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee\Constitutional;

use App\Contexts\Membership\Domain\Committee\Geo\Kernel\CapabilityType;
use App\Contexts\Membership\Domain\Committee\Constitutional\Snapshot\SnapshotIdentity;
use App\Contexts\Membership\Domain\Committee\Constitutional\Snapshot\SnapshotDecisionData;
use App\Contexts\Membership\Domain\Committee\Constitutional\Snapshot\SnapshotTraceData;
use App\Contexts\Membership\Domain\Committee\Constitutional\Snapshot\SnapshotIntegrityData;

final readonly class GovernanceDecisionSnapshot
{
    public const SCHEMA_VERSION = '1.0';

    public function __construct(
        public string             $decisionId,
        public \DateTimeImmutable $decidedAt,
        public string             $capabilityType,
        public ?string            $winningAuthorityId,
        public string             $legitimacy,
        public string             $constitutionalReasonJson,
        public string             $arbitrationTraceJson,
        public SnapshotMetadata   $metadata,
        public ?string            $constitutionalScope,
        public string             $integrityHash,
    ) {}

    public static function fromDecision(
        ConstitutionalGovernanceDecision $decision,
        CapabilityType                   $capability,
        \DateTimeImmutable               $persistedAt,
        ?SnapshotMetadata                $metadata = null,
        ?string                          $constitutionalScope = null,
    ): self {
        $metadata ??= new SnapshotMetadata(
            schemaVersion:              self::SCHEMA_VERSION,
            doctrineVersion:            '1.0',
            legitimacyPolicyVersion:    '1.0',
            arbitrationPolicyVersion:   '1.0',
            replayEngineVersion:        SnapshotMetadata::REPLAY_ENGINE_VERSION,
            replayCompatibilityVersion: '3.2',
            generatedAt:                $persistedAt,
        );

        $reason = $decision->constitutionalDecision->reason;
        $trace  = $decision->constitutionalDecision->trace;

        // Canonical ordering before hashing — identical constitutional meaning = identical fingerprint
        $articleCodes = $reason->articleCodes;
        sort($articleCodes);
        $evaluatedNodeIds = $trace->evaluatedNodeIds;
        sort($evaluatedNodeIds);
        $doctrineRules = $trace->doctrineRulesApplied;
        sort($doctrineRules);

        $flags = JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES;

        $reasonJson = json_encode([
            'code'             => $reason->code,
            'summary'          => $reason->summary,
            'explanation'      => $reason->explanation,
            'articleCodes'     => $articleCodes,
            'severity'         => $reason->severity,
            'legitimacyImpact' => $reason->legitimacyImpact,
        ], $flags);

        $traceJson = json_encode([
            'evaluatedNodeIds'     => $evaluatedNodeIds,
            'selectedNodeId'       => $trace->selectedNodeId,
            'precedenceReason'     => $trace->precedenceReason,
            'doctrineRulesApplied' => $doctrineRules,
            'rejectionReasons'     => $trace->rejectionReasons,
        ], $flags);

        $hash = hash('sha256', implode('|', [
            $decision->id()->toString(),
            $decision->decidedAt()->format(\DateTimeInterface::ATOM),
            $capability->value,
            $decision->winner()?->id ?? 'null',
            $decision->legitimacy()->value,
            $reasonJson,
            $traceJson,
            $metadata->schemaVersion,
            $metadata->doctrineVersion,
            $metadata->legitimacyPolicyVersion,
            $metadata->arbitrationPolicyVersion,
        ]));

        return new self(
            decisionId:               $decision->id()->toString(),
            decidedAt:                $decision->decidedAt(),
            capabilityType:           $capability->value,
            winningAuthorityId:       $decision->winner()?->id,
            legitimacy:               $decision->legitimacy()->value,
            constitutionalReasonJson: $reasonJson,
            arbitrationTraceJson:     $traceJson,
            metadata:                 $metadata,
            constitutionalScope:      $constitutionalScope,
            integrityHash:            $hash,
        );
    }

    public function identity(): SnapshotIdentity
    {
        return new SnapshotIdentity($this->decisionId, $this->capabilityType, $this->constitutionalScope);
    }

    public function decisionData(): SnapshotDecisionData
    {
        return new SnapshotDecisionData($this->winningAuthorityId, $this->legitimacy, $this->constitutionalReasonJson);
    }

    public function traceData(): SnapshotTraceData
    {
        return new SnapshotTraceData(
            $this->arbitrationTraceJson,
            $this->metadata->doctrineVersion,
            $this->metadata->arbitrationPolicyVersion,
        );
    }

    public function integrityData(): SnapshotIntegrityData
    {
        return new SnapshotIntegrityData(
            $this->integrityHash,
            $this->metadata->schemaVersion,
            $this->metadata->replayEngineVersion,
            $this->metadata->replayCompatibilityVersion,
        );
    }

    public function verifyIntegrity(): bool
    {
        $recomputed = hash('sha256', implode('|', [
            $this->decisionId,
            $this->decidedAt->format(\DateTimeInterface::ATOM),
            $this->capabilityType,
            $this->winningAuthorityId ?? 'null',
            $this->legitimacy,
            $this->constitutionalReasonJson,
            $this->arbitrationTraceJson,
            $this->metadata->schemaVersion,
            $this->metadata->doctrineVersion,
            $this->metadata->legitimacyPolicyVersion,
            $this->metadata->arbitrationPolicyVersion,
        ]));
        return $recomputed === $this->integrityHash;
    }
}
