<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Membership\Infrastructure\Organisational;

use App\Contexts\Membership\Infrastructure\Organisational\PgUniqueViolation;
use Illuminate\Database\UniqueConstraintViolationException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * PBDIGIT-264 F-5 (Part 2 plan step 1, AC-1): the key-collision catch in PostgresMemberRepository::append must
 * classify a unique violation by SQLSTATE 23505 and the constraint named on the FIRST line of the driver's error,
 * never by searching the whole message. Laravel's message contains the SQL with bindings, and PostgreSQL's DETAIL
 * line contains key values, so either can carry the constraint's name as data (ratification pack §2 "F-5").
 * Database-free.
 */
final class PgUniqueViolationTest extends TestCase
{
    private const COMMANDS_PKEY = 'org_membership_commands_pkey';

    private static function violation(?array $errorInfo, array $bindings = []): UniqueConstraintViolationException
    {
        $driver = new \PDOException($errorInfo[2] ?? 'driver error');
        $driver->errorInfo = $errorInfo;

        return new UniqueConstraintViolationException('pgsql', 'insert into "org_membership_commands" values (?, ?)', $bindings, $driver);
    }

    private static function pg(string $sqlState, string $constraint, string $detail): array
    {
        return [$sqlState, 7, "ERROR:  duplicate key value violates unique constraint \"{$constraint}\"\nDETAIL:  {$detail}"];
    }

    public function test_a_real_commands_key_collision_is_recognised(): void
    {
        $e = self::violation(self::pg('23505', self::COMMANDS_PKEY, 'Key (organisation_id, idempotency_key)=(9b1e…, k-1) already exists.'));

        $this->assertTrue(PgUniqueViolation::isConstraint($e, self::COMMANDS_PKEY));
    }

    public function test_a_violation_of_another_constraint_whose_detail_and_bindings_name_the_key_is_not_a_key_collision(): void
    {
        // The decoy of the ratification pack: the name appears only as data (DETAIL key value and a bound value).
        $e = self::violation(
            self::pg('23505', 'org_membership_facts_pkey', 'Key (organisation_id, membership_id, seq)=(9b1e…, org_membership_commands_pkey, 1) already exists.'),
            ['org_membership_commands_pkey'],
        );

        $this->assertStringContainsString(self::COMMANDS_PKEY, $e->getMessage(), 'precondition: the old substring test would have matched');
        $this->assertFalse(PgUniqueViolation::isConstraint($e, self::COMMANDS_PKEY));
    }

    public static function notACollision(): array
    {
        return [
            'another SQLSTATE (foreign key)' => [self::pg('23503', self::COMMANDS_PKEY, 'Key is not present.')],
            'a longer constraint name'       => [self::pg('23505', self::COMMANDS_PKEY.'_x', 'Key … already exists.')],
            'a prefix of the name'           => [self::pg('23505', 'org_membership_commands', 'Key … already exists.')],
            'no error info'                  => [null],
            'empty driver message'           => [['23505', 7, '']],
            'no constraint on the first line' => [['23505', 7, "ERROR:  something else\nDETAIL:  unique constraint \"org_membership_commands_pkey\""]],
        ];
    }

    #[DataProvider('notACollision')]
    public function test_anything_else_is_not_a_key_collision(?array $errorInfo): void
    {
        $this->assertFalse(PgUniqueViolation::isConstraint(self::violation($errorInfo), self::COMMANDS_PKEY));
    }

    public function test_the_repository_classifies_the_collision_without_reading_the_exception_message(): void
    {
        $source = (string) file_get_contents(__DIR__.'/../../../../../../app/Contexts/Membership/Infrastructure/Organisational/PostgresMemberRepository.php');

        $this->assertStringContainsString('PgUniqueViolation::isConstraint(', $source);
        $this->assertStringNotContainsString('->getMessage()', $source, 'append() must not search the exception message (F-5)');
    }
}
