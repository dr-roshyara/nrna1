<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Identity;

use App\Contexts\Identity\Application\IssueReference;
use App\Contexts\Identity\Application\Ports\IssuedReferences;
use App\Contexts\Identity\Domain\LocalEntryRef;
use App\Contexts\Identity\Domain\OrganisationPersonRef;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use PHPUnit\Framework\TestCase;

/**
 * PBDIGIT-349 step I-1 (I-TDD-02 §4 IssuedReference, §12 C-I1; P-I2 = L1, Principal 2026-11-15):
 * - an organisation's recorded person (local entry E) gets ONE opaque PersonRef R: issuing again for (O, E) returns
 *   the same R (INV-REF-2);
 * - R is issued to exactly one organisation (INV-REF-1): the same entry id in another organisation gets another R;
 * - R is opaque (32 lower-case hex) and carries no data; an entry reference is opaque and never empty.
 */
final class IssueReferenceTest extends TestCase
{
    private const O1 = '11111111-1111-4111-8111-111111111111';
    private const O2 = '22222222-2222-4222-8222-222222222222';

    /** An in-memory port with the contract's meaning (the PostgreSQL adapter is proven separately). */
    private static function references(): IssuedReferences
    {
        return new class implements IssuedReferences {
            /** @var array<string, string> "O|E" => R */
            private array $byEntry = [];
            /** @var array<string, string> R => O */
            private array $owner = [];
            private int $n = 0;

            public function issue(TenantId $organisation, LocalEntryRef $entry): OrganisationPersonRef
            {
                $key = $organisation->value() . '|' . $entry->value();
                if (!isset($this->byEntry[$key])) {
                    $r = sprintf('%032x', ++$this->n);
                    $this->byEntry[$key] = $r;
                    $this->owner[$r] = $organisation->value();
                }

                return OrganisationPersonRef::fromString($this->byEntry[$key]);
            }

            public function isIssuedTo(OrganisationPersonRef $ref, TenantId $organisation): bool
            {
                return ($this->owner[$ref->value()] ?? null) === $organisation->value();
            }
        };
    }

    public function test_issuing_again_for_the_same_entry_returns_the_same_reference(): void
    {
        $issue = new IssueReference(self::references());
        $first = $issue->issue(TenantId::fromString(self::O1), LocalEntryRef::fromString('entry-1'));

        $this->assertTrue($first->equals($issue->issue(TenantId::fromString(self::O1), LocalEntryRef::fromString('entry-1'))));
        $this->assertFalse($first->equals($issue->issue(TenantId::fromString(self::O1), LocalEntryRef::fromString('entry-2'))));
    }

    public function test_a_reference_belongs_to_exactly_one_organisation(): void
    {
        $refs = self::references();
        $issue = new IssueReference($refs);
        $inO1 = $issue->issue(TenantId::fromString(self::O1), LocalEntryRef::fromString('same-id'));
        $inO2 = $issue->issue(TenantId::fromString(self::O2), LocalEntryRef::fromString('same-id'));

        $this->assertFalse($inO1->equals($inO2), 'the same entry id in another organisation is another person reference');
        $this->assertTrue($refs->isIssuedTo($inO1, TenantId::fromString(self::O1)));
        $this->assertFalse($refs->isIssuedTo($inO1, TenantId::fromString(self::O2)));
    }

    public function test_a_person_reference_is_opaque_hex(): void
    {
        $this->assertSame(str_repeat('a', 32), OrganisationPersonRef::fromString(str_repeat('a', 32))->value());
        foreach (['', str_repeat('A', 32), str_repeat('a', 31), 'person@example.test', str_repeat('g', 32)] as $bad) {
            try {
                OrganisationPersonRef::fromString($bad);
                $this->fail("accepted '{$bad}'");
            } catch (\InvalidArgumentException) {
                $this->addToAssertionCount(1);
            }
        }
    }

    public function test_an_entry_reference_is_opaque_and_never_empty(): void
    {
        $this->assertSame('entry-1', LocalEntryRef::fromString('entry-1')->value());
        foreach (['', ' ', 'has space', "tab\t", str_repeat('x', 129)] as $bad) {
            try {
                LocalEntryRef::fromString($bad);
                $this->fail("accepted '{$bad}'");
            } catch (\InvalidArgumentException) {
                $this->addToAssertionCount(1);
            }
        }
    }
}
