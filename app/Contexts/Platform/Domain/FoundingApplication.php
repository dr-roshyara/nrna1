<?php

declare(strict_types=1);

namespace App\Contexts\Platform\Domain;

/**
 * The founding application recorded with a `pending` registry entry (tactical design §3.6 step 1; D-148, D-174, D-185):
 * the founding evidence (a reference), the registrar named by it, and the initial PLATFORM CONTACTS (Identity account
 * ids, as UUID strings: Platform imports nothing of Identity's domain). At least one contact: D-193 F-07 needs one with
 * an active account before `operational` (plan finding F-T1-1). A contact receives no membership, workspace access or
 * authority (D-193 R-B): it is recorded, nothing more. The registrar's claim (D-193 F-03) is not issued here.
 */
final readonly class FoundingApplication
{
    private const UUID = '/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/';

    /** @param list<string> $initialContactAccounts */
    public function __construct(
        public string $evidenceRef,
        public string $registrarAccount,
        public array $initialContactAccounts,
    ) {
        if (trim($evidenceRef) === '') {
            throw new \InvalidArgumentException('A founding application cites its founding evidence.');
        }
        if (preg_match(self::UUID, $registrarAccount) !== 1) {
            throw new \InvalidArgumentException('The registrar is named by an account id.');
        }
        if ($initialContactAccounts === []) {
            throw new \InvalidArgumentException('A founding application names at least one initial platform contact (D-193 F-07).');
        }
        foreach ($initialContactAccounts as $contact) {
            if (!is_string($contact) || preg_match(self::UUID, $contact) !== 1) {
                throw new \InvalidArgumentException('An initial platform contact is named by an account id.');
            }
        }
        if (count(array_unique($initialContactAccounts)) !== count($initialContactAccounts)) {
            throw new \InvalidArgumentException('An initial platform contact is named once.');
        }
    }

    public function equals(self $other): bool
    {
        return $this->evidenceRef === $other->evidenceRef && $this->registrarAccount === $other->registrarAccount
            && $this->initialContactAccounts === $other->initialContactAccounts;
    }
}
