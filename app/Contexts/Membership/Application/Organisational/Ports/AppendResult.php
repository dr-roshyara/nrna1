<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Application\Organisational\Ports;

use App\Contexts\Membership\Application\Organisational\Appended;

final readonly class AppendResult
{
    private function __construct(private AppendOutcome $outcome, private ?Appended $appended)
    {
    }

    public static function success(Appended $appended): self
    {
        return new self(AppendOutcome::Appended, $appended);
    }

    public static function retried(Appended $original): self
    {
        return new self(AppendOutcome::AlreadyApplied, $original);
    }

    public static function failed(AppendOutcome $outcome): self
    {
        if (in_array($outcome, [AppendOutcome::Appended, AppendOutcome::AlreadyApplied], true)) {
            throw new \InvalidArgumentException('Use success() / retried().');
        }

        return new self($outcome, null);
    }

    public function outcome(): AppendOutcome
    {
        return $this->outcome;
    }

    /** The recorded fact: the new one (Appended) or the original one (AlreadyApplied). */
    public function appended(): Appended
    {
        return $this->appended ?? throw new \LogicException('Nothing was appended: ' . $this->outcome->value);
    }
}
