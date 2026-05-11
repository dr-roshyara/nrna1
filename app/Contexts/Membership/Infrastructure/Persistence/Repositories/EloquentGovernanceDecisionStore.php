<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Infrastructure\Persistence\Repositories;

use App\Contexts\Membership\Domain\Committee\Constitutional\GovernanceDecisionSnapshot;
use App\Contexts\Membership\Domain\Committee\Constitutional\GovernanceDecisionStore;
use App\Contexts\Membership\Domain\Committee\Constitutional\SnapshotMetadata;
use App\Contexts\Membership\Domain\Committee\Geo\Kernel\GovernanceDecisionId;
use App\Contexts\Membership\Infrastructure\Persistence\Models\GovernanceDecisionSnapshotModel;

final class EloquentGovernanceDecisionStore implements GovernanceDecisionStore
{
    public function store(GovernanceDecisionSnapshot $snapshot): void
    {
        GovernanceDecisionSnapshotModel::updateOrCreate(
            ['id' => $snapshot->decisionId],
            [
                'decided_at'                 => $snapshot->decidedAt->format('Y-m-d H:i:s'),
                'capability_type'            => $snapshot->capabilityType,
                'winning_authority_id'       => $snapshot->winningAuthorityId,
                'legitimacy'                 => $snapshot->legitimacy,
                'constitutional_scope'       => $snapshot->constitutionalScope,
                'constitutional_reason_json' => $snapshot->constitutionalReasonJson,
                'arbitration_trace_json'     => $snapshot->arbitrationTraceJson,
                'schema_version'             => $snapshot->metadata->schemaVersion,
                'doctrine_version'           => $snapshot->metadata->doctrineVersion,
                'legitimacy_policy_version'  => $snapshot->metadata->legitimacyPolicyVersion,
                'arbitration_policy_version' => $snapshot->metadata->arbitrationPolicyVersion,
                'replay_engine_version'      => $snapshot->metadata->replayEngineVersion,
                'replay_compatibility_version' => $snapshot->metadata->replayCompatibilityVersion,
                'metadata_generated_at'      => $snapshot->metadata->generatedAt->format('Y-m-d H:i:s'),
                'integrity_hash'             => $snapshot->integrityHash,
            ]
        );
    }

    public function findById(GovernanceDecisionId $id): ?GovernanceDecisionSnapshot
    {
        $model = GovernanceDecisionSnapshotModel::find($id->toString());

        if ($model === null) {
            return null;
        }

        return $this->mapToDomain($model);
    }

    private function mapToDomain(GovernanceDecisionSnapshotModel $model): GovernanceDecisionSnapshot
    {
        $metadata = new SnapshotMetadata(
            schemaVersion:              $model->schema_version,
            doctrineVersion:            $model->doctrine_version,
            legitimacyPolicyVersion:    $model->legitimacy_policy_version,
            arbitrationPolicyVersion:   $model->arbitration_policy_version,
            replayEngineVersion:        $model->replay_engine_version,
            replayCompatibilityVersion: $model->replay_compatibility_version,
            generatedAt:                $model->metadata_generated_at->toDateTimeImmutable(),
        );

        return new GovernanceDecisionSnapshot(
            decisionId:               $model->id,
            decidedAt:                $model->decided_at->toDateTimeImmutable(),
            capabilityType:           $model->capability_type,
            winningAuthorityId:       $model->winning_authority_id,
            legitimacy:               $model->legitimacy,
            constitutionalReasonJson: $model->constitutional_reason_json,
            arbitrationTraceJson:     $model->arbitration_trace_json,
            metadata:                 $metadata,
            constitutionalScope:      $model->constitutional_scope,
            integrityHash:            $model->integrity_hash,
        );
    }
}
