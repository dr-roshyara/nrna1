<?php

namespace App\Application\Election\Capabilities;

use App\Domain\Election\Enum\ElectionLifecycleState;
use App\Models\Election;
use App\Models\User;

final readonly class CapabilityContext
{
    public function __construct(
        public Election $election,
        public ?User $user,
        public string $action,
        public array $actionMetadata,
        public ElectionLifecycleState $state,
    ) {}

    public function isSystemAction(): bool
    {
        $requiredRoles = $this->actionMetadata['allowed_roles'] ?? [];
        return in_array('system', $requiredRoles) && count($requiredRoles) === 1;
    }

    public function isUserAuthenticated(): bool
    {
        return $this->user !== null;
    }

    public function actionRequiresRole(string $role): bool
    {
        $requiredRoles = $this->actionMetadata['allowed_roles'] ?? [];
        return in_array($role, $requiredRoles);
    }

    public function isActionAllowedInState(): bool
    {
        $allowedStates = $this->actionMetadata['allowed_states'] ?? [];
        foreach ($allowedStates as $allowedState) {
            if ($this->state->value === $allowedState) {
                return true;
            }
        }
        return false;
    }
}
