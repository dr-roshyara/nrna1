<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Platform\Domain;

use App\Contexts\Platform\Domain\FoundingApplication;
use App\Contexts\Platform\Domain\ServedOrganisation;
use App\Contexts\Platform\Domain\ServedOrganisationSnapshot;
use App\Contexts\Platform\Domain\PlatformStatus;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use PHPUnit\Framework\TestCase;

/**
 * PBDIGIT-345 task 5a (tactical design §3.6 step 1; D-148, D-174, D-185; plan finding F-T1-1): a registry entry is
 * created `pending` WITH the founding application: the founding evidence, which names the registrar and the initial
 * platform contacts (Identity account ids). At least one contact: F-07 needs one with an active account before
 * `operational`. A contact receives no membership, workspace access or authority (D-193 R-B): it is only recorded.
 */
final class FoundingApplicationTest extends TestCase
{
    private const ID = '0b9e7a52-6c1d-4f7e-9a3b-2d5c8e1f4a60';
    private const REGISTRAR = '7e6d5c4b-3a29-4180-9f8e-7d6c5b4a3921';
    private const CONTACT = '1a2b3c4d-5e6f-4a7b-8c9d-0e1f2a3b4c5d';

    public function test_a_valid_application_names_the_evidence_the_registrar_and_at_least_one_contact(): void
    {
        $a = new FoundingApplication('founding-evidence:minutes-2026-11-01', self::REGISTRAR, [self::CONTACT]);

        $this->assertSame(['founding-evidence:minutes-2026-11-01', self::REGISTRAR, [self::CONTACT]], [$a->evidenceRef, $a->registrarAccount, $a->initialContactAccounts]);
        $this->assertTrue($a->equals(new FoundingApplication('founding-evidence:minutes-2026-11-01', self::REGISTRAR, [self::CONTACT])));
        $this->assertFalse($a->equals(new FoundingApplication('founding-evidence:other', self::REGISTRAR, [self::CONTACT])));
    }

    public function test_an_incomplete_or_malformed_application_is_refused(): void
    {
        $cases = [
            'no evidence' => ['', self::REGISTRAR, [self::CONTACT]],
            'a registrar that is no account id' => ['e', 'alice@example.org', [self::CONTACT]],
            'no initial contact' => ['e', self::REGISTRAR, []],
            'a contact that is no account id' => ['e', self::REGISTRAR, ['bob']],
            'the same contact twice' => ['e', self::REGISTRAR, [self::CONTACT, self::CONTACT]],
        ];
        foreach ($cases as $why => [$evidence, $registrar, $contacts]) {
            try {
                new FoundingApplication($evidence, $registrar, $contacts);
                $this->fail("accepted: {$why}");
            } catch (\InvalidArgumentException) {
                $this->addToAssertionCount(1);
            }
        }
    }

    public function test_a_registry_entry_is_registered_pending_with_its_application_and_never_restored_without_one(): void
    {
        $application = new FoundingApplication('e', self::REGISTRAR, [self::CONTACT]);
        $o = ServedOrganisation::register(TenantId::fromString(self::ID), $application);

        $this->assertSame(PlatformStatus::Pending, $o->status());
        $this->assertTrue($o->foundingApplication()->equals($application));
        $this->assertTrue(ServedOrganisation::fromSnapshot($o->snapshot())->foundingApplication()->equals($application), 'stored and restored');

        $s = $o->snapshot();
        $this->expectException(\UnexpectedValueException::class);
        ServedOrganisation::fromSnapshot(new ServedOrganisationSnapshot($s->id, $s->status, $s->schemaState, $s->locator, $s->grant, $s->counterparty, null));
    }
}
