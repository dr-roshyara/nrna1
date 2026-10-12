<?php

use Illuminate\Database\Connection;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/*
 * PBDIGIT-274 S2 (plan docs/plans/20261011-2302-voter-slug-lifecycle-plan.md, sections 11 and 11.0; G3).
 *
 * Replaces the unique constraint on voter_slugs (election_id, user_id), which also counts soft-deleted rows,
 * with a partial unique index over current rows only (deleted_at IS NULL): at most one current voter slug
 * per voter and election, while replaced slugs stay as soft-deleted history.
 *
 * - PostgreSQL only; anything else is refused (never silently skipped).
 * - up(): own transaction (a savepoint inside the Migrator's), SET LOCAL lock_timeout, explicit
 *   preconditions, DROP CONSTRAINT (never DROP INDEX), CREATE UNIQUE INDEX ... WHERE deleted_at IS NULL.
 * - down(): refuses on schema drift or when any pair has more than one row (soft-deleted included);
 *   never deletes or rewrites rows. Once replacement slugs exist, rollback is unavailable by design.
 *
 * The class is declared once (the file is required by the Migrator and by the tests) and is not final:
 * tests subclass it and override afterDrop() to inject a failure between the drop and the create.
 */
if (! class_exists('ReplaceVoterSlugsPairUniqueWithActivePartialIndex', false)) {
    class ReplaceVoterSlugsPairUniqueWithActivePartialIndex extends Migration
    {
        public $withinTransaction = true;

        public const TABLE = 'voter_slugs';
        public const OLD_CONSTRAINT = 'voter_slugs_election_id_user_id_unique';
        public const NEW_INDEX = 'voter_slugs_active_election_user_unique';
        public const LOCK_TIMEOUT = '5s';
        public const CREATE_INDEX_SQL = 'CREATE UNIQUE INDEX '.self::NEW_INDEX
            .' ON '.self::TABLE.' (election_id, user_id) WHERE deleted_at IS NULL';

        public function up(): void
        {
            $db = $this->postgres();

            $db->transaction(function () use ($db) {
                $db->statement("SET LOCAL lock_timeout = '".self::LOCK_TIMEOUT."'");
                $this->refuseUnless($this->oldConstraintColumns($db) === 'election_id,user_id',
                    'the unique constraint '.self::OLD_CONSTRAINT.' on exactly (election_id, user_id) does not exist');
                $this->refuseUnless(! $this->relationExists($db, self::NEW_INDEX),
                    'a relation named '.self::NEW_INDEX.' already exists');
                $this->refuseUnless(! $this->hasDuplicatePairs($db, currentOnly: true),
                    'some (election_id, user_id) pair has more than one current row');

                Schema::connection($db->getName())->table(self::TABLE, function (Blueprint $table) {
                    $table->dropUnique(self::OLD_CONSTRAINT);
                });
                $this->afterDrop();
                $db->statement(self::CREATE_INDEX_SQL);
            });
        }

        public function down(): void
        {
            $db = $this->postgres();

            $db->transaction(function () use ($db) {
                $db->statement("SET LOCAL lock_timeout = '".self::LOCK_TIMEOUT."'");
                $this->refuseUnless($this->isExpectedPartialIndex($db),
                    'the partial unique index '.self::NEW_INDEX.' is missing or not the expected definition (schema drift)');
                $this->refuseUnless($this->oldConstraintColumns($db) === null && ! $this->relationExists($db, self::OLD_CONSTRAINT),
                    'a relation or constraint named '.self::OLD_CONSTRAINT.' already exists (schema drift)');
                $this->refuseUnless(! $this->hasDuplicatePairs($db, currentOnly: false),
                    'replaced voter slugs exist (a pair has more than one row, soft-deleted included); '
                    .'restoring the old constraint would need deleting history, which this migration never does');

                $db->statement('DROP INDEX '.self::NEW_INDEX);
                Schema::connection($db->getName())->table(self::TABLE, function (Blueprint $table) {
                    $table->unique(['election_id', 'user_id'], self::OLD_CONSTRAINT);
                });
            });
        }

        /** Test seam: runs between dropping the old constraint and creating the new index. No-op in production. */
        protected function afterDrop(): void
        {
        }

        private function postgres(): Connection
        {
            $db = DB::connection($this->getConnection());
            if ($db->getDriverName() !== 'pgsql') {
                throw new RuntimeException('PBDIGIT-274: the voter_slugs partial unique index requires PostgreSQL; refusing on '.$db->getDriverName().'.');
            }

            return $db;
        }

        private function refuseUnless(bool $condition, string $reason): void
        {
            if (! $condition) {
                throw new RuntimeException('PBDIGIT-274 voter_slugs migration refused: '.$reason.'. Nothing was changed.');
            }
        }

        /** "election_id,user_id" when the unique constraint exists on voter_slugs (column order kept), else null. */
        private function oldConstraintColumns(Connection $db): ?string
        {
            $row = $db->selectOne(<<<'SQL'
                SELECT string_agg(a.attname, ',' ORDER BY k.ord) AS cols
                  FROM pg_constraint c
                  JOIN pg_class t ON t.oid = c.conrelid
                  JOIN pg_namespace n ON n.oid = t.relnamespace AND n.nspname = current_schema()
                  CROSS JOIN LATERAL unnest(c.conkey) WITH ORDINALITY AS k(attnum, ord)
                  JOIN pg_attribute a ON a.attrelid = t.oid AND a.attnum = k.attnum
                 WHERE t.relname = ? AND c.conname = ? AND c.contype = 'u'
                 GROUP BY c.oid
                SQL, [self::TABLE, self::OLD_CONSTRAINT]);

            return $row?->cols;
        }

        private function relationExists(Connection $db, string $name): bool
        {
            return $db->selectOne(
                'SELECT 1 AS found FROM pg_class c JOIN pg_namespace n ON n.oid = c.relnamespace
                  WHERE n.nspname = current_schema() AND c.relname = ?', [$name]) !== null;
        }

        private function isExpectedPartialIndex(Connection $db): bool
        {
            $row = $db->selectOne(
                'SELECT pg_get_indexdef(c.oid) AS def FROM pg_class c JOIN pg_namespace n ON n.oid = c.relnamespace
                  WHERE n.nspname = current_schema() AND c.relname = ? AND c.relkind = ?', [self::NEW_INDEX, 'i']);

            return $row !== null && preg_match(
                '/^CREATE UNIQUE INDEX '.self::NEW_INDEX.' ON (\S+\.)?'.self::TABLE
                .' USING btree \(election_id, user_id\) WHERE \(deleted_at IS NULL\)$/',
                $row->def) === 1;
        }

        private function hasDuplicatePairs(Connection $db, bool $currentOnly): bool
        {
            $where = $currentOnly ? 'WHERE deleted_at IS NULL' : '';

            return $db->selectOne(
                "SELECT 1 AS found FROM ".self::TABLE." {$where} GROUP BY election_id, user_id HAVING count(*) > 1 LIMIT 1") !== null;
        }
    }
}

return new ReplaceVoterSlugsPairUniqueWithActivePartialIndex();
