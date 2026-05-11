<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee\Constitutional;

final readonly class ConstitutionalArbitrationTrace
{
    /**
     * @param string[]                                      $evaluatedNodeIds
     * @param string[]                                      $doctrineRulesApplied
     * @param array<array{nodeId: string, reason: string}>  $rejectionReasons
     */
    public function __construct(
        public array   $evaluatedNodeIds,
        public ?string $selectedNodeId,
        public string  $precedenceReason,
        public array   $doctrineRulesApplied,
        public array   $rejectionReasons = [],
    ) {}

    public function hasSelectedNode(): bool
    {
        return $this->selectedNodeId !== null;
    }
}
