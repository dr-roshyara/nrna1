<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Application\Organisational\Ports;

use App\Contexts\Membership\Domain\Organisational\Member;
use App\Contexts\Membership\Domain\Organisational\MemberId;

/** create() either created the Member, or reports the one that already exists for the holder (INV-M). */
final readonly class CreateResult
{
    private function __construct(private ?Member $member, private ?MemberId $existing)
    {
    }

    public static function created(Member $member): self
    {
        return new self($member, null);
    }

    public static function holderExists(MemberId $existing): self
    {
        return new self(null, $existing);
    }

    public function isCreated(): bool
    {
        return $this->member !== null;
    }

    public function member(): Member
    {
        return $this->member ?? throw new \LogicException('No Member was created.');
    }

    public function existingMemberId(): MemberId
    {
        return $this->existing ?? throw new \LogicException('The Member was created.');
    }
}
