<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Platform\Domain;

use App\Contexts\Platform\Domain\CounterpartyVerification;
use App\Contexts\Platform\Domain\DatabaseLocator;
use App\Contexts\Platform\Domain\OperationalReadiness;
use App\Contexts\Platform\Domain\ServiceOperationGrant;
use PHPUnit\Framework\TestCase;

/**
 * PBDIGIT-345 task 1: the Platform registry's value objects (D-193 Part 4, §3.3; D-175; delta §3.7, §3.9, R11 F-07).
 * Database-free. Run: vendor/bin/phpunit --no-configuration --bootstrap vendor/autoload.php tests/Unit/Contexts/Platform
 */
final class PlatformValueObjectsTest extends TestCase
{
    private const FP = 'a3fe00f28f8fe70c388891cbf36042f428bc69a70d1efdf9d24f3cc8e129fb18';

    public function test_a_service_operation_grant_keeps_the_evidence_of_section_3_3(): void
    {
        $g = new ServiceOperationGrant('terms-2026-11', new \DateTimeImmutable('2026-11-12T10:00:00Z'), 'account:applicant-1', [self::FP]);

        $this->assertSame('terms-2026-11', $g->termsVersion);
        $this->assertSame('2026-11-12T10:00:00+00:00', $g->acceptedAt->format(DATE_ATOM));
        $this->assertSame('account:applicant-1', $g->acceptedBy);
        $this->assertSame([self::FP], $g->foundingDocumentFingerprints);
    }

    /** @return iterable<string, array{string, string, list<string>}> */
    public static function incompleteGrants(): iterable
    {
        yield 'no terms version' => ['', 'account:a', [self::FP]];
        yield 'no accepting person' => ['t1', ' ', [self::FP]];
        yield 'no founding document' => ['t1', 'account:a', []];
        yield 'a fingerprint that is not SHA-256' => ['t1', 'account:a', ['abc']];
    }

    /** @dataProvider incompleteGrants */
    #[\PHPUnit\Framework\Attributes\DataProvider('incompleteGrants')]
    public function test_an_incomplete_grant_is_refused(string $terms, string $by, array $fingerprints): void
    {
        $this->expectException(\InvalidArgumentException::class);

        new ServiceOperationGrant($terms, new \DateTimeImmutable('2026-11-12'), $by, $fingerprints);
    }

    public function test_a_counterparty_verification_needs_its_evidence(): void
    {
        $this->assertSame('agreement:2026-11-12-001', (new CounterpartyVerification('agreement:2026-11-12-001'))->evidenceRef);

        $this->expectException(\InvalidArgumentException::class);
        new CounterpartyVerification('  ');
    }

    public function test_a_locator_names_cluster_and_database_and_holds_only_a_credential_reference(): void
    {
        $l = new DatabaseLocator('cluster-1', 'org_9f1c', 'secret-store:org_9f1c');

        $this->assertSame(['cluster-1', 'org_9f1c', 'secret-store:org_9f1c'], [$l->cluster, $l->database, $l->credentialRef]);
        $this->assertTrue($l->equals(new DatabaseLocator('cluster-1', 'org_9f1c', 'secret-store:org_9f1c')));
    }

    /** @return iterable<string, array{string, string, string}> */
    public static function badLocators(): iterable
    {
        yield 'no cluster' => ['', 'org_a', 'ref:a'];
        yield 'database not a plain identifier' => ['c1', 'org-a; DROP', 'ref:a'];
        yield 'no credential reference' => ['c1', 'org_a', ''];
    }

    /** @dataProvider badLocators */
    #[\PHPUnit\Framework\Attributes\DataProvider('badLocators')]
    public function test_a_malformed_locator_is_refused(string $cluster, string $database, string $credentialRef): void
    {
        $this->expectException(\InvalidArgumentException::class);

        new DatabaseLocator($cluster, $database, $credentialRef);
    }

    public function test_readiness_is_complete_only_when_every_l1_fact_holds_and_a_contact_exists(): void
    {
        $this->assertTrue((new OperationalReadiness(true, true, true, 1))->isComplete());
        $this->assertFalse((new OperationalReadiness(false, true, true, 1))->isComplete(), 'ledger schema missing');
        $this->assertFalse((new OperationalReadiness(true, false, true, 1))->isComplete(), 'identity row missing or different');
        $this->assertFalse((new OperationalReadiness(true, true, false, 1))->isComplete(), 'ledger clock row missing');
        $this->assertFalse((new OperationalReadiness(true, true, true, 0))->isComplete(), 'no active-account contact (F-07)');
    }

    public function test_readiness_refuses_a_negative_contact_count(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        new OperationalReadiness(true, true, true, -1);
    }
}
