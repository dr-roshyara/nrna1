<?php

declare(strict_types=1);

namespace App\Http\Controllers\Committee;

use App\Contexts\Membership\Application\Committee\GetCommitteeDashboard;
use App\Contexts\Membership\Domain\Exceptions\CommitteeNotFoundException;
use App\Contexts\Membership\Domain\ValueObjects\CommitteeId;
use App\Contexts\Membership\Infrastructure\Models\CommitteeModel;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use App\Contracts\TenantContextInterface;
use App\Http\Controllers\Controller;
use App\Models\Organisation;
use Inertia\Inertia;
use Inertia\Response;

final class CommitteeDashboardController extends Controller
{
    public function __construct(
        private readonly GetCommitteeDashboard $useCase,
        private readonly TenantContextInterface $tenantContext
    ) {}

    public function show(Organisation $organisation, CommitteeModel $committee): Response
    {
        $tenantId = TenantId::fromString($organisation->id);

        try {
            $dashboard = $this->useCase->execute(
                CommitteeId::fromString($committee->id),
                $tenantId
            );
        } catch (CommitteeNotFoundException) {
            abort(404);
        }

        return Inertia::render('Committee/Dashboard', $dashboard->toArray());
    }
}
