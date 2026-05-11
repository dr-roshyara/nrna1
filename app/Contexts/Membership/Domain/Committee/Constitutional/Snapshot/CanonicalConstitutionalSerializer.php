<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee\Constitutional\Snapshot;

use App\Contexts\Membership\Domain\Committee\Constitutional\ConstitutionalReason;
use App\Contexts\Membership\Domain\Committee\Constitutional\ConstitutionalArbitrationTrace;

final class CanonicalConstitutionalSerializer
{
    private const FLAGS = JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES;

    public function serializeReason(ConstitutionalReason $reason): string
    {
        $articleCodes = $reason->articleCodes;
        sort($articleCodes);

        return json_encode([
            'code'             => $reason->code,
            'summary'          => $reason->summary,
            'explanation'      => $reason->explanation,
            'articleCodes'     => $articleCodes,
            'severity'         => $reason->severity->value,
            'legitimacyImpact' => $reason->legitimacyImpact->value,
        ], self::FLAGS);
    }

    public function serializeTrace(ConstitutionalArbitrationTrace $trace): string
    {
        $evaluatedNodeIds = $trace->evaluatedNodeIds;
        sort($evaluatedNodeIds);
        $doctrineRules = $trace->doctrineRulesApplied;
        sort($doctrineRules);

        return json_encode([
            'evaluatedNodeIds'     => $evaluatedNodeIds,
            'selectedNodeId'       => $trace->selectedNodeId,
            'precedenceReason'     => $trace->precedenceReason,
            'doctrineRulesApplied' => $doctrineRules,
            'rejectionReasons'     => $trace->rejectionReasons,
        ], self::FLAGS);
    }
}
