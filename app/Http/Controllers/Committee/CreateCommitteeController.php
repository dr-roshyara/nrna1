<?php

declare(strict_types=1);

namespace App\Http\Controllers\Committee;

use App\Contexts\Geography\Domain\ValueObjects\GeoUnitId;
use App\Contexts\Membership\Application\Committee\UseCases\CreateCommittee\CreateCommitteeCommand;
use App\Contexts\Membership\Application\Committee\UseCases\CreateCommittee\CreateCommitteeHandler;
use App\Contexts\Membership\Domain\Committee\ValueObjects\GovernanceAssignment;
use App\Exceptions\DomainException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Committee\CreateCommitteeRequest;
use Illuminate\Http\JsonResponse;

final class CreateCommitteeController extends Controller
{
    public function __construct(
        private readonly CreateCommitteeHandler $handler,
    ) {}

    public function store(CreateCommitteeRequest $request): JsonResponse
    {
        try {
            $governanceLevel = (int) $request->input('governanceLevel');
            $geoUnitId = GeoUnitId::fromInt((int) $request->input('geoUnitId'));

            // @todo: When geo_level column is added to geo_administrative_units,
            // derive geoLevel from the geo unit lookup: $geoUnit->admin_level
            // For now, use diagonal seed: geoLevel == governanceLevel
            $geoLevel = $governanceLevel;

            $command = new CreateCommitteeCommand(
                name: $request->input('name'),
                assignment: new GovernanceAssignment(
                    governanceLevel: $governanceLevel,
                    geoLevel: $geoLevel,
                    geoUnitId: $geoUnitId,
                ),
            );

            $committeeId = $this->handler->handle($command);

            return response()->json([
                'committeeId' => $committeeId,
            ], 201);
        } catch (DomainException $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 422);
        }
    }
}
