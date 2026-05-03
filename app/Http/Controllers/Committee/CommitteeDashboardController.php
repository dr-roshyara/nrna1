<?php

declare(strict_types=1);

namespace App\Http\Controllers\Committee;

use App\Contexts\Membership\Application\Committee\GetCommitteeDashboard;
use App\Contexts\Membership\Domain\Exceptions\CommitteeNotFoundException;
use App\Contexts\Membership\Domain\ValueObjects\CommitteeId;
use App\Contracts\TenantContextInterface;
use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;

final class CommitteeDashboardController extends Controller
{
    public function __construct(
        private readonly GetCommitteeDashboard $useCase,
        private readonly TenantContextInterface $tenantContext
    ) {}

    public function show(string $committeeId): Response
    {
        $tenantId = $this->tenantContext->currentTenantId();

        try {
            $view = $this->useCase->execute(
                CommitteeId::fromString($committeeId),
                $tenantId
            );
        } catch (CommitteeNotFoundException) {
            abort(404);
        }

        return Inertia::render('Committee/Dashboard', $view->toArray());
    }
}
