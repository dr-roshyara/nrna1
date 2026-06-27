<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Application\CommitteeStructure;

use App\Contexts\Membership\Domain\Committee\CommitteeStructure;

interface ActivateCommitteeStructureUseCase
{
    public function execute(array $command): CommitteeStructure;
}
