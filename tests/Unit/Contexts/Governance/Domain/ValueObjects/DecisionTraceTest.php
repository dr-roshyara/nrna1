<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Governance\Domain\ValueObjects;

use App\Contexts\Governance\Domain\ValueObjects\AuthorityPath;
use App\Contexts\Governance\Domain\ValueObjects\DecisionTrace;
use App\Contexts\Membership\Domain\Committee\ValueObjects\MemberId;
use DateTimeImmutable;
use DomainException;
use PHPUnit\Framework\TestCase;

final class DecisionTraceTest extends TestCase
{
    public function test_it_creates_decision_trace_with_required_fields(): void
    {
        $evaluatedRules = ['RULE_A', 'RULE_B'];
        $matchedClauses = ['CLAUSE_1', 'CLAUSE_3'];
        $rejectedConstraints = ['CONSTRAINT_2'];
        $authorityPath = AuthorityPath::fromArray(['ICC', 'CONTINENT', 'COUNTRY']);
        $adjudicatedBy = MemberId::from('user-president-123');
        $adjudicatedAt = new DateTimeImmutable('2026-05-09 10:00:00');

        $trace = DecisionTrace::from(
            evaluatedRules: $evaluatedRules,
            matchedClauses: $matchedClauses,
            rejectedConstraints: $rejectedConstraints,
            authorityPath: $authorityPath,
            adjudicatedBy: $adjudicatedBy,
            adjudicatedAt: $adjudicatedAt
        );

        $this->assertSame($evaluatedRules, $trace->evaluatedRules());
        $this->assertSame($matchedClauses, $trace->matchedClauses());
        $this->assertSame($rejectedConstraints, $trace->rejectedConstraints());
        $this->assertTrue($trace->authorityPath()->equals($authorityPath));
        $this->assertTrue($trace->adjudicatedBy()->equals($adjudicatedBy));
        $this->assertSame($adjudicatedAt, $trace->adjudicatedAt());
    }

    public function test_evaluated_rules_cannot_be_empty(): void
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('At least one rule must be evaluated');

        DecisionTrace::from(
            evaluatedRules: [],
            matchedClauses: ['CLAUSE_1'],
            rejectedConstraints: [],
            authorityPath: AuthorityPath::fromArray(['ICC', 'COUNTRY']),
            adjudicatedBy: MemberId::from('user-123'),
            adjudicatedAt: new DateTimeImmutable()
        );
    }

    public function test_matched_clauses_can_be_empty(): void
    {
        $trace = DecisionTrace::from(
            evaluatedRules: ['RULE_A'],
            matchedClauses: [],
            rejectedConstraints: [],
            authorityPath: AuthorityPath::fromArray(['ICC']),
            adjudicatedBy: MemberId::from('user-123'),
            adjudicatedAt: new DateTimeImmutable()
        );

        $this->assertEmpty($trace->matchedClauses());
    }

    public function test_rejected_constraints_can_be_empty(): void
    {
        $trace = DecisionTrace::from(
            evaluatedRules: ['RULE_A'],
            matchedClauses: ['CLAUSE_1'],
            rejectedConstraints: [],
            authorityPath: AuthorityPath::fromArray(['ICC']),
            adjudicatedBy: MemberId::from('user-123'),
            adjudicatedAt: new DateTimeImmutable()
        );

        $this->assertEmpty($trace->rejectedConstraints());
    }

    public function test_authority_path_validation_delegated_to_vo(): void
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Delegation path cannot be empty');

        AuthorityPath::fromArray([]);
    }

    public function test_authority_path_must_start_with_icc_delegated_to_vo(): void
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Authority path must start with ICC');

        AuthorityPath::fromArray(['CONTINENT', 'COUNTRY']);
    }

    public function test_adjudicated_by_validation_delegated_to_vo(): void
    {
        $this->expectException(DomainException::class);

        MemberId::from('');
    }

    public function test_it_has_private_constructor(): void
    {
        $reflection = new \ReflectionClass(DecisionTrace::class);
        $constructor = $reflection->getConstructor();

        $this->assertTrue($constructor->isPrivate(),
            'DecisionTrace must have private constructor - use from() factory'
        );
    }

    public function test_it_is_immutable_readonly(): void
    {
        $trace = DecisionTrace::from(
            evaluatedRules: ['RULE_A'],
            matchedClauses: ['CLAUSE_1'],
            rejectedConstraints: [],
            authorityPath: AuthorityPath::fromArray(['ICC', 'COUNTRY']),
            adjudicatedBy: MemberId::from('user-123'),
            adjudicatedAt: new DateTimeImmutable()
        );

        $reflection = new \ReflectionClass($trace);

        foreach ($reflection->getProperties() as $property) {
            $this->assertTrue(
                $property->isReadOnly(),
                sprintf('Property %s must be readonly', $property->getName())
            );
        }
    }

    public function test_value_equality(): void
    {
        $time = new DateTimeImmutable('2026-05-09 10:00:00');

        $a = DecisionTrace::from(
            evaluatedRules: ['RULE_A', 'RULE_B'],
            matchedClauses: ['CLAUSE_1'],
            rejectedConstraints: ['CONSTRAINT_X'],
            authorityPath: AuthorityPath::fromArray(['ICC', 'CONTINENT', 'COUNTRY']),
            adjudicatedBy: MemberId::from('user-president-123'),
            adjudicatedAt: $time
        );

        $b = DecisionTrace::from(
            evaluatedRules: ['RULE_A', 'RULE_B'],
            matchedClauses: ['CLAUSE_1'],
            rejectedConstraints: ['CONSTRAINT_X'],
            authorityPath: AuthorityPath::fromArray(['ICC', 'CONTINENT', 'COUNTRY']),
            adjudicatedBy: MemberId::from('user-president-123'),
            adjudicatedAt: $time
        );

        $this->assertTrue($a->equals($b));
    }

    public function test_value_inequality_different_evaluated_rules(): void
    {
        $time = new DateTimeImmutable('2026-05-09 10:00:00');

        $a = DecisionTrace::from(
            evaluatedRules: ['RULE_A'],
            matchedClauses: ['CLAUSE_1'],
            rejectedConstraints: [],
            authorityPath: AuthorityPath::fromArray(['ICC']),
            adjudicatedBy: MemberId::from('user-123'),
            adjudicatedAt: $time
        );

        $b = DecisionTrace::from(
            evaluatedRules: ['RULE_B'],
            matchedClauses: ['CLAUSE_1'],
            rejectedConstraints: [],
            authorityPath: AuthorityPath::fromArray(['ICC']),
            adjudicatedBy: MemberId::from('user-123'),
            adjudicatedAt: $time
        );

        $this->assertFalse($a->equals($b));
    }

    public function test_value_inequality_different_authority_path(): void
    {
        $time = new DateTimeImmutable('2026-05-09 10:00:00');

        $a = DecisionTrace::from(
            evaluatedRules: ['RULE_A'],
            matchedClauses: [],
            rejectedConstraints: [],
            authorityPath: AuthorityPath::fromArray(['ICC', 'CONTINENT']),
            adjudicatedBy: MemberId::from('user-123'),
            adjudicatedAt: $time
        );

        $b = DecisionTrace::from(
            evaluatedRules: ['RULE_A'],
            matchedClauses: [],
            rejectedConstraints: [],
            authorityPath: AuthorityPath::fromArray(['ICC', 'COUNTRY']),
            adjudicatedBy: MemberId::from('user-123'),
            adjudicatedAt: $time
        );

        $this->assertFalse($a->equals($b));
    }

    public function test_is_forensic_audit_record_only(): void
    {
        $reflection = new \ReflectionClass(DecisionTrace::class);
        $publicMethods = array_filter(
            $reflection->getMethods(\ReflectionMethod::IS_PUBLIC),
            fn($m) => !$m->isStatic() && $m->getName() !== '__construct'
        );

        $allowedMethods = [
            'evaluatedRules',
            'matchedClauses',
            'rejectedConstraints',
            'authorityPath',
            'adjudicatedBy',
            'adjudicatedAt',
            'equals',
        ];

        foreach ($publicMethods as $method) {
            $this->assertContains(
                $method->getName(),
                $allowedMethods,
                sprintf('Method %s not allowed - DecisionTrace is forensic-record-only VO', $method->getName())
            );
        }
    }
}
