<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee\Constitutional;

final readonly class SnapshotMetadata
{
    public const REPLAY_ENGINE_VERSION = '3.2';

    public function __construct(
        public string             $schemaVersion,
        public string             $doctrineVersion,
        public string             $legitimacyPolicyVersion,
        public string             $arbitrationPolicyVersion,
        public string             $replayEngineVersion,
        public string             $replayCompatibilityVersion,
        public \DateTimeImmutable $generatedAt,
    ) {}
}
