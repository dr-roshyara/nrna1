<?php
/**
 * G-11 PostgreSQL write-boundary protocol: executable check against a REAL PostgreSQL server. Research only.
 *
 * Commission: principal review of cross-domain architecture v1.3 (2026-10-10), §2.1 / §2.2 / §2.3.
 * Not application code. Uses its own throwaway schema `wb_*` in the target database.
 *
 * SAFETY (fail closed, checked before connecting):
 *   - DSN from env WB_DSN only; it MUST use a unix-socket host (host=/...) and MUST NOT name nrna_test.
 *   - After connecting: current_database() must equal the DSN dbname and must not be nrna_test.
 * Intended target: a private throwaway cluster (initdb in a scratch directory, listen_addresses='').
 *
 * Method: two real sessions; every interleaving of their steps is executed in order. A step that would block
 * hits lock_timeout, is rolled back to a savepoint and retried after the other session finishes. This is
 * the same as waiting, because READ COMMITTED takes a fresh snapshot per statement.
 *
 * Experiments (counts are enumerated schedules, not probabilities):
 *   E1 revocation vs post   NAIVE | PLAIN_VERSION | FOR_SHARE | SERIALIZABLE_PLAIN
 *   E2 absence (phantom)    ROW_FOR_SHARE (lock the suspension rows found) | SCOPE_TOKEN (lock a scope version row)
 *   E3 two-root lock order  OPPOSITE_ORDER | SORTED_ORDER   (deadlock = both sessions blocked on each other)
 * "Unauthorised" (E1/E2) = the post row is committed and the revoking/suspending commit happened first in real time.
 *
 * Run: WB_DSN='pgsql:host=/path/to/sockdir;dbname=wb' WB_USER=... php docs/publicdigit/membership/architecture/models/g11_pg_write_boundary.php
 */

$dsn = getenv('WB_DSN') ?: '';
$user = getenv('WB_USER') ?: '';
if (!preg_match('/host=\//', $dsn) || preg_match('/dbname=nrna_test(;|$)/', $dsn) || !preg_match('/dbname=([A-Za-z0-9_]+)/', $dsn, $m)) {
    fwrite(STDERR, "REFUSED: WB_DSN must use a unix-socket host and must not target nrna_test\n");
    exit(2);
}
$expectedDb = $m[1];

function conn(string $dsn, string $user, string $expectedDb): PDO {
    $c = new PDO($dsn, $user, null, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    $db = $c->query('select current_database()')->fetchColumn();
    if ($db !== $expectedDb || $db === 'nrna_test') {
        fwrite(STDERR, "REFUSED: connected to unexpected database\n");
        exit(2);
    }
    $c->exec("set lock_timeout = '60ms'");
    $c->exec("set deadlock_timeout = '10s'");      // our own both-blocked detection fires first
    return $c;
}

$A = conn($dsn, $user, $expectedDb);
$B = conn($dsn, $user, $expectedDb);
create_schema($A);
$ONLY = array_filter(explode(',', getenv('WB_ONLY') ?: ''));
$want = fn(string $e) => !$ONLY || in_array($e, $ONLY, true);
function emit(string $k, $v): void { fwrite(STDERR, json_encode([$k => $v]) . "\n"); }

function create_schema(PDO $c): void {
    $c->exec('drop table if exists wb_scope, wb_tenure, wb_suspension, wb_post, wb_tok_mem, wb_tok_gov, wb_tok_com, wb_deleg, wb_membership');
    $c->exec('create table wb_scope (scope text primary key, version bigint not null)');
    $c->exec('create table wb_tenure (person text primary key, active boolean not null)');
    $c->exec('create table wb_suspension (person text not null, org text not null)');
    $c->exec('create table wb_post (id serial primary key, author text not null)');
    $c->exec('create table wb_tok_mem (scope text collate "C" primary key, version bigint not null)');   // Membership-owned tokens
    $c->exec('create table wb_tok_gov (scope text collate "C" primary key, version bigint not null)');   // Governance-owned tokens
    $c->exec('create table wb_tok_com (scope text collate "C" primary key, version bigint not null)');   // Communication-owned tokens
    $c->exec('create table wb_deleg (delegate text primary key, delegator text not null)');
    $c->exec('create table wb_membership (person text not null, org text not null, version bigint not null)'); // E9: aggregate rows (no unique key on purpose, see E9)
}

/** Reset data between schedules (fast: no DDL). Added 2026-10-11 after the DROP/CREATE version timed out on E6. */
function reset_schema(PDO $c): void {
    $c->exec('truncate wb_scope, wb_tenure, wb_suspension, wb_post, wb_tok_mem, wb_tok_gov, wb_tok_com, wb_deleg, wb_membership restart identity');
    $c->exec("insert into wb_scope values ('tenure:p', 0), ('susp:p:o', 0), ('ph:p', 0), ('cm:c1', 0)");
    $c->exec("insert into wb_tenure values ('p', true), ('b', true)");
    $c->exec("insert into wb_tok_mem values ('membership:p:o', 0), ('admissions:o2', 0)");
    $c->exec("insert into wb_tok_gov values ('tenure:p', 0), ('tenure:b', 0), ('deleg:a', 0)");
    $c->exec("insert into wb_tok_com values ('forum:f', 0)");
    $c->exec("insert into wb_deleg values ('a', 'b')");
}

/** All interleavings of two step lists (preserving each list's order). */
function merges(array $a, array $b): array {
    if (!$a) return [$b];
    if (!$b) return [$a];
    $out = [];
    foreach (merges(array_slice($a, 1), $b) as $r) $out[] = array_merge([$a[0]], $r);
    foreach (merges($a, array_slice($b, 1)) as $r) $out[] = array_merge([$b[0]], $r);
    return $out;
}

/**
 * Execute a schedule. Each step: [actor, callable(PDO, array &$state): void, label].
 * Blocking statements raise SQLSTATE 55P03 (lock_not_available); the step is retried later.
 */
function execute(array $schedule, array $conns): array {
    $state = ['commits' => [], 'post_written' => false, 'serialization_failures' => 0, 'deadlock' => false];
    $queue = $schedule;
    $blocked = [];                 // actor => step that hit lock_timeout (retried later = waiting)
    $aborted = [];
    $run = function (array $step) use ($conns, &$state, &$aborted): string {
        [$actor, $fn] = $step;
        $c = $conns[$actor];
        $inTx = $c->inTransaction();
        try {
            if ($inTx) $c->exec('savepoint sp');
            $fn($c, $state);
            if ($inTx && $c->inTransaction()) $c->exec('release savepoint sp');
            return 'ok';
        } catch (PDOException $e) {
            $code = $e->errorInfo[0] ?? '';
            if ($code === '55P03') { $c->exec('rollback to savepoint sp'); return 'blocked'; }
            if ($code === '40001' || $code === '40P01') {
                $state['serialization_failures']++;
                if ($c->inTransaction()) $c->rollBack();
                $aborted[$actor] = true;
                return 'aborted';
            }
            throw $e;
        }
    };
    while ($queue || $blocked) {
        $progressed = false;
        foreach ($blocked as $actor => $step) {              // waiting statements retry first
            $r = $run($step);
            if ($r !== 'blocked') { unset($blocked[$actor]); $progressed = true; }
        }
        $idx = null;
        foreach ($queue as $i => $step) {
            if (!isset($blocked[$step[0]]) && !isset($aborted[$step[0]])) { $idx = $i; break; }
        }
        if ($idx !== null) {
            $step = $queue[$idx];
            array_splice($queue, $idx, 1);
            $r = $run($step);
            if ($r === 'blocked') { $blocked[$step[0]] = $step; } else { $progressed = true; }
        }
        $queue = array_values(array_filter($queue, fn($st) => !isset($aborted[$st[0]])));
        if (!$progressed && $idx === null) { $state['deadlock'] = true; break; }
        if (!$progressed && $idx !== null && count($blocked) === count(array_unique(array_map(fn($st) => $st[0], $schedule)))) {
            $state['deadlock'] = true; break;               // every actor is waiting on another
        }
    }
    foreach ($conns as $c) if ($c->inTransaction()) $c->rollBack();
    return $state;
}

function commit_step(string $actor): array {
    return [$actor, function (PDO $c, array &$s) use ($actor) {
        $c->commit();
        $s['commits'][] = $actor;
    }];
}

// ---------------------------------------------------------------- E1 revocation vs post
function e1_post_steps(string $protocol): array {
    $begin = ['P', function (PDO $c, array &$s) use ($protocol) {
        $c->beginTransaction();
        if ($protocol === 'SERIALIZABLE_PLAIN') $c->exec('set transaction isolation level serializable');
        $s['ok'] = true;
    }];
    $check = ['P', function (PDO $c, array &$s) use ($protocol) {
        if ($protocol === 'NAIVE') {
            $s['ok'] = (bool) $c->query("select active from wb_tenure where person='p'")->fetchColumn();
        } else {
            $sql = "select version from wb_scope where scope='tenure:p'" . ($protocol === 'FOR_SHARE' ? ' for share' : '');
            $s['ok'] = ((int) $c->query($sql)->fetchColumn()) === 0;   // decision was taken at version 0
        }
    }];
    $write = ['P', function (PDO $c, array &$s) {
        if ($s['ok']) { $c->exec("insert into wb_post(author) values ('p')"); $s['post_written'] = true; }
    }];
    return [$begin, $check, $write, commit_step('P')];
}

function e1_revoke_steps(): array {
    return [
        ['G', function (PDO $c, array &$s) { $c->beginTransaction(); }],
        ['G', function (PDO $c, array &$s) {
            $c->exec("update wb_scope set version = version + 1 where scope='tenure:p'");
            $c->exec("update wb_tenure set active = false where person='p'");
        }],
        commit_step('G'),
    ];
}

// ---------------------------------------------------------------- E2 absence / phantom
function e2_post_steps(string $protocol): array {
    return [
        ['P', function (PDO $c, array &$s) { $c->beginTransaction(); $s['ok'] = true; }],
        ['P', function (PDO $c, array &$s) use ($protocol) {
            if ($protocol === 'ROW_FOR_SHARE') {
                $rows = $c->query("select 1 from wb_suspension where person='p' and org='o' for share")->fetchAll();
                $s['ok'] = count($rows) === 0;
            } else {
                $v = (int) $c->query("select version from wb_scope where scope='susp:p:o' for share")->fetchColumn();
                $s['ok'] = $v === 0;
            }
        }],
        ['P', function (PDO $c, array &$s) {
            if ($s['ok']) { $c->exec("insert into wb_post(author) values ('p')"); $s['post_written'] = true; }
        }],
        commit_step('P'),
    ];
}

function e2_suspend_steps(string $protocol): array {
    return [
        ['G', function (PDO $c, array &$s) { $c->beginTransaction(); }],
        ['G', function (PDO $c, array &$s) use ($protocol) {
            if ($protocol === 'SCOPE_TOKEN') $c->exec("update wb_scope set version = version + 1 where scope='susp:p:o'");
            $c->exec("insert into wb_suspension values ('p','o')");
        }],
        commit_step('G'),
    ];
}

// ---------------------------------------------------------------- E3 two-root lock order
function e3_steps(string $actor, array $order): array {
    $steps = [[$actor, function (PDO $c, array &$s) { $c->beginTransaction(); }]];
    foreach ($order as $scope) {
        $steps[] = [$actor, function (PDO $c, array &$s) use ($scope) {
            $c->exec("update wb_scope set version = version + 1 where scope='$scope'");
        }];
    }
    $steps[] = commit_step($actor);
    return $steps;
}

$conns = ['P' => $A, 'G' => $B];
$conns3 = ['P' => $A, 'G' => $B, 'H' => conn($dsn, $user, $expectedDb)];
$out = ['server' => $A->query('show server_version')->fetchColumn(), 'isolation_default' => $A->query('show default_transaction_isolation')->fetchColumn()];

if ($want('E1')) {
foreach (['NAIVE', 'PLAIN_VERSION', 'FOR_SHARE', 'SERIALIZABLE_PLAIN'] as $protocol) {
    $r = ['schedules' => 0, 'unauthorised' => 0, 'posts' => 0, 'serialization_failures' => 0, 'deadlocks' => 0];
    foreach (merges(e1_post_steps($protocol), e1_revoke_steps()) as $schedule) {
        reset_schema($A);
        $st = execute($schedule, $conns);
        $r['schedules']++;
        $written = (int) $A->query('select count(*) from wb_post')->fetchColumn() > 0;
        $r['posts'] += $written;
        $gi = array_search('G', $st['commits'], true);
        $pi = array_search('P', $st['commits'], true);
        if ($written && $gi !== false && $pi !== false && $gi < $pi) $r['unauthorised']++;
        $r['serialization_failures'] += $st['serialization_failures'];
        $r['deadlocks'] += $st['deadlock'];
    }
    $out['E1_revocation_vs_post'][$protocol] = $r;
}
    emit('E1_revocation_vs_post', $out['E1_revocation_vs_post']);
}

if ($want('E2')) {
foreach (['ROW_FOR_SHARE', 'SCOPE_TOKEN'] as $protocol) {
    $r = ['schedules' => 0, 'unauthorised' => 0, 'posts' => 0];
    foreach (merges(e2_post_steps($protocol), e2_suspend_steps($protocol)) as $schedule) {
        reset_schema($A);
        $st = execute($schedule, $conns);
        $r['schedules']++;
        $written = (int) $A->query('select count(*) from wb_post')->fetchColumn() > 0;
        $r['posts'] += $written;
        $gi = array_search('G', $st['commits'], true);
        $pi = array_search('P', $st['commits'], true);
        if ($written && $gi !== false && $pi !== false && $gi < $pi) $r['unauthorised']++;
    }
    $out['E2_absence_phantom'][$protocol] = $r;
}
    emit('E2_absence_phantom', $out['E2_absence_phantom']);
}

if ($want('E3')) {
foreach (['OPPOSITE_ORDER' => [['ph:p', 'cm:c1'], ['cm:c1', 'ph:p']], 'SORTED_ORDER' => [['cm:c1', 'ph:p'], ['cm:c1', 'ph:p']]] as $label => [$o1, $o2]) {
    $r = ['schedules' => 0, 'deadlocks' => 0, 'both_committed' => 0];
    foreach (merges(e3_steps('P', $o1), e3_steps('G', $o2)) as $schedule) {
        reset_schema($A);
        $st = execute($schedule, $conns);
        $r['schedules']++;
        $r['deadlocks'] += $st['deadlock'];
        $r['both_committed'] += count($st['commits']) === 2;
    }
    $out['E3_two_root_lock_order'][$label] = $r;
}
    emit('E3_two_root_lock_order', $out['E3_two_root_lock_order']);
}

// ---------------------------------------------------------------- E4 missing scope token (added 2026-10-11)
// Purpose: a scope token that does not exist yet (lazy creation) re-opens the phantom problem unless the guard
// creates it first. The suspender creates the token with an upsert; the post's decision recorded "absent" (= 0).
function e4_post_steps(string $protocol): array {
    return [
        ['P', function (PDO $c, array &$s) { $c->beginTransaction(); $s['ok'] = true; }],
        ['P', function (PDO $c, array &$s) use ($protocol) {
            if ($protocol === 'LAZY_READ') {
                $v = $c->query("select version from wb_scope where scope='lazy:p:o' for share")->fetchColumn();
                $s['ok'] = ($v === false ? 0 : (int) $v) === 0;
            } else {                                        // GUARD_UPSERT
                $c->exec("insert into wb_scope values ('lazy:p:o', 0) on conflict (scope) do nothing");
                $v = (int) $c->query("select version from wb_scope where scope='lazy:p:o' for share")->fetchColumn();
                $s['ok'] = $v === 0;
            }
        }],
        ['P', function (PDO $c, array &$s) {
            if ($s['ok']) { $c->exec("insert into wb_post(author) values ('p')"); $s['post_written'] = true; }
        }],
        commit_step('P'),
    ];
}

function e4_suspend_steps(): array {
    return [
        ['G', function (PDO $c, array &$s) { $c->beginTransaction(); }],
        ['G', function (PDO $c, array &$s) {
            $c->exec("insert into wb_scope values ('lazy:p:o', 1) on conflict (scope) do update set version = wb_scope.version + 1");
            $c->exec("insert into wb_suspension values ('p','o')");
        }],
        commit_step('G'),
    ];
}

if ($want('E4')) {
foreach (['LAZY_READ', 'GUARD_UPSERT'] as $protocol) {
    $r = ['schedules' => 0, 'unauthorised' => 0, 'posts' => 0, 'deadlocks' => 0];
    foreach (merges(e4_post_steps($protocol), e4_suspend_steps()) as $schedule) {
        reset_schema($A);
        $st = execute($schedule, $conns);
        $r['schedules']++;
        $written = (int) $A->query('select count(*) from wb_post')->fetchColumn() > 0;
        $r['posts'] += $written;
        $r['deadlocks'] += $st['deadlock'];
        $gi = array_search('G', $st['commits'], true);
        $pi = array_search('P', $st['commits'], true);
        if ($written && $gi !== false && $pi !== false && $gi < $pi) $r['unauthorised']++;
    }
    $out['E4_missing_scope_token'][$protocol] = $r;
}
    emit('E4_missing_scope_token', $out['E4_missing_scope_token']);
}

// ---------------------------------------------------------------- E5 lock upgrade (added 2026-10-11)
// Purpose: two appointments that both GUARD the PositionHoldings token (FOR SHARE) and then WRITE it deadlock even
// with a sorted order. Rule candidate: a token the transaction will write is locked FOR UPDATE at acquisition.
function e5_steps(string $actor, string $protocol): array {
    return [
        [$actor, function (PDO $c, array &$s) { $c->beginTransaction(); }],
        [$actor, function (PDO $c, array &$s) use ($protocol) {
            $mode = $protocol === 'SHARE_THEN_UPDATE' ? 'for share' : 'for update';
            $c->query("select version from wb_scope where scope='ph:p' $mode")->fetchAll();
        }],
        [$actor, function (PDO $c, array &$s) {
            $c->exec("update wb_scope set version = version + 1 where scope='ph:p'");
        }],
        commit_step($actor),
    ];
}

if ($want('E5')) {
foreach (['SHARE_THEN_UPDATE', 'FOR_UPDATE_UPFRONT'] as $protocol) {
    $r = ['schedules' => 0, 'deadlocks' => 0, 'both_committed' => 0];
    foreach (merges(e5_steps('P', $protocol), e5_steps('G', $protocol)) as $schedule) {
        reset_schema($A);
        $st = execute($schedule, $conns);
        $r['schedules']++;
        $r['deadlocks'] += $st['deadlock'];
        $r['both_committed'] += count($st['commits']) === 2;
    }
    $out['E5_lock_upgrade'][$protocol] = $r;
}
    emit('E5_lock_upgrade', $out['E5_lock_upgrade']);
}

// ---------------------------------------------------------------- shared helpers for E6-E8 (added 2026-10-11)
function merges3(array $a, array $b, array $c): array {
    $out = [];
    foreach (merges($a, $b) as $ab) foreach (merges($ab, $c) as $abc) $out[serialize(array_map(fn($s) => $s[0] . spl_object_id($s[1]), $abc))] = $abc;
    return array_values($out);
}

function unauthorised_after(array $st, array $revokers, bool $written): bool {
    $pi = array_search('P', $st['commits'], true);
    if (!$written || $pi === false) return false;
    foreach ($revokers as $r) {
        $ri = array_search($r, $st['commits'], true);
        if ($ri !== false && $ri < $pi) return true;
    }
    return false;
}

// ---------------------------------------------------------------- E6 three sessions, two context token tables (FV-1)
// Purpose: canonical order (context rank Membership < Governance, then scope COLLATE "C") across per-context tables,
// with a post that depends on a Membership token AND a Governance token, racing a suspension and a tenure revocation.
function e6_post(string $protocol): array {
    return [
        ['P', function (PDO $c, array &$s) { $c->beginTransaction(); $s['ok'] = true; }],
        ['P', function (PDO $c, array &$s) use ($protocol) {
            $lock = $protocol === 'CANONICAL_FOR_SHARE' ? ' for share' : '';
            $m = (int) $c->query("select version from wb_tok_mem where scope='membership:p:o'$lock")->fetchColumn();
            $g = (int) $c->query("select version from wb_tok_gov where scope='tenure:p'$lock")->fetchColumn();
            $s['ok'] = $m === 0 && $g === 0;
        }],
        ['P', function (PDO $c, array &$s) {
            if ($s['ok']) { $c->exec("insert into wb_post(author) values ('p')"); $s['post_written'] = true; }
        }],
        commit_step('P'),
    ];
}
function e6_bump(string $actor, string $table, string $scope): array {
    return [
        [$actor, function (PDO $c, array &$s) { $c->beginTransaction(); }],
        [$actor, function (PDO $c, array &$s) use ($table, $scope) {
            $c->exec("update $table set version = version + 1 where scope='$scope'");
        }],
        commit_step($actor),
    ];
}
if ($want('E6')) {
foreach (['PLAIN_READ', 'CANONICAL_FOR_SHARE'] as $protocol) {
    $r = ['schedules' => 0, 'unauthorised' => 0, 'posts' => 0, 'deadlocks' => 0];
    foreach (merges3(e6_post($protocol), e6_bump('G', 'wb_tok_gov', 'tenure:p'), e6_bump('H', 'wb_tok_mem', 'membership:p:o')) as $schedule) {
        reset_schema($A);
        $st = execute($schedule, $conns3);
        $r['schedules']++;
        $written = (int) $A->query('select count(*) from wb_post')->fetchColumn() > 0;
        $r['posts'] += $written;
        $r['deadlocks'] += $st['deadlock'];
        $r['unauthorised'] += unauthorised_after($st, ['G', 'H'], $written);
    }
    $out['E6_three_sessions_two_tables'][$protocol] = $r;
}
    emit('E6_three_sessions_two_tables', $out['E6_three_sessions_two_tables']);
}

// ---------------------------------------------------------------- E7 WB-10: decide INSIDE the write transaction (FV-2)
// Purpose: moderation decided inside the transaction, racing an annulment of the moderator's appointment.
// INSIDE_PLAIN: evaluate inside the transaction with a plain read. INSIDE_LOCKED: acquisition phase first
// (tenure token FOR SHARE, forum token FOR UPDATE, canonical order Governance < Communication), then evaluate.
function e7_moderate(string $protocol): array {
    return [
        ['P', function (PDO $c, array &$s) { $c->beginTransaction(); $s['ok'] = true; }],
        ['P', function (PDO $c, array &$s) use ($protocol) {
            if ($protocol === 'INSIDE_LOCKED') {
                $c->query("select version from wb_tok_gov where scope='tenure:p' for share")->fetchAll();
                $c->query("select version from wb_tok_com where scope='forum:f' for update")->fetchAll();
            }
            $s['ok'] = (bool) $c->query("select active from wb_tenure where person='p'")->fetchColumn();  // decide on current facts
        }],
        ['P', function (PDO $c, array &$s) {
            if ($s['ok']) {
                $c->exec("update wb_tok_com set version = version + 1 where scope='forum:f'");
                $c->exec("insert into wb_post(author) values ('moderation-by-p')");
                $s['post_written'] = true;
            }
        }],
        commit_step('P'),
    ];
}
function e7_annul(): array {
    return [
        ['G', function (PDO $c, array &$s) { $c->beginTransaction(); }],
        ['G', function (PDO $c, array &$s) {
            $c->exec("update wb_tok_gov set version = version + 1 where scope='tenure:p'");
            $c->exec("update wb_tenure set active = false where person='p'");
        }],
        commit_step('G'),
    ];
}
if ($want('E7')) {
foreach (['INSIDE_PLAIN', 'INSIDE_LOCKED'] as $protocol) {
    $r = ['schedules' => 0, 'unauthorised' => 0, 'actions' => 0, 'deadlocks' => 0];
    foreach (merges(e7_moderate($protocol), e7_annul()) as $schedule) {
        reset_schema($A);
        $st = execute($schedule, $conns);
        $r['schedules']++;
        $written = (int) $A->query('select count(*) from wb_post')->fetchColumn() > 0;
        $r['actions'] += $written;
        $r['deadlocks'] += $st['deadlock'];
        $r['unauthorised'] += unauthorised_after($st, ['G'], $written);
    }
    $out['E7_decide_inside_vs_annulment'][$protocol] = $r;
}
    emit('E7_decide_inside_vs_annulment', $out['E7_decide_inside_vs_annulment']);
}

// ---------------------------------------------------------------- E8 WB-10 footprint discovery through delegation
// Purpose: actor a acts under a delegation from b; authority needs deleg(a) AND tenure(b). The footprint is only
// known after reading the delegation. PARTIAL: lock deleg:a, read tenure(b) unlocked. EXTEND: after discovering b,
// extend the lock set (tenure:b sorts after deleg:a, so order is kept; otherwise restart with the union).
function e8_act(string $protocol): array {
    return [
        ['P', function (PDO $c, array &$s) { $c->beginTransaction(); $s['ok'] = true; }],
        ['P', function (PDO $c, array &$s) use ($protocol) {
            $c->query("select version from wb_tok_gov where scope='deleg:a' for share")->fetchAll();
            $b = $c->query("select delegator from wb_deleg where delegate='a'")->fetchColumn();
            if ($protocol === 'EXTEND_LOCKS') {
                $c->query("select version from wb_tok_gov where scope='tenure:$b' for share")->fetchAll();
            }
            $s['ok'] = (bool) $c->query("select active from wb_tenure where person='$b'")->fetchColumn();
        }],
        ['P', function (PDO $c, array &$s) {
            if ($s['ok']) { $c->exec("insert into wb_post(author) values ('a-as-delegate')"); $s['post_written'] = true; }
        }],
        commit_step('P'),
    ];
}
function e8_revoke_delegator(): array {
    return [
        ['G', function (PDO $c, array &$s) { $c->beginTransaction(); }],
        ['G', function (PDO $c, array &$s) {
            $c->exec("update wb_tok_gov set version = version + 1 where scope='tenure:b'");
            $c->exec("update wb_tenure set active = false where person='b'");
        }],
        commit_step('G'),
    ];
}
if ($want('E8')) {
foreach (['PARTIAL_LOCKS', 'EXTEND_LOCKS'] as $protocol) {
    $r = ['schedules' => 0, 'unauthorised' => 0, 'actions' => 0, 'deadlocks' => 0];
    foreach (merges(e8_act($protocol), e8_revoke_delegator()) as $schedule) {
        reset_schema($A);
        $st = execute($schedule, $conns);
        $r['schedules']++;
        $written = (int) $A->query('select count(*) from wb_post')->fetchColumn() > 0;
        $r['actions'] += $written;
        $r['deadlocks'] += $st['deadlock'];
        $r['unauthorised'] += unauthorised_after($st, ['G'], $written);
    }
    $out['E8_footprint_discovery_delegation'][$protocol] = $r;
}
    emit('E8_footprint_discovery_delegation', $out['E8_footprint_discovery_delegation']);
}

// ---------------------------------------------------------------- E9 absent aggregate + concurrent first creation (added 2026-10-11)
// Purpose (Task 2 of the closing round): how does a FOREIGN context guard a scope whose aggregate does not exist yet,
// WITHOUT writing into the owner's tables? Rule fixture: "p may act only if p is NOT a member of o2".
// ROW_ONLY     : guard locks the matching membership rows (none exist); creator inserts the row.
// PARENT_TOKEN : the OWNER keeps a parent scope token admissions:o2 (Membership-owned). Creating a membership locks it
//                FOR UPDATE, bumps it, re-checks absence and inserts. A guard locks it FOR SHARE (no insert, no bump) and
//                re-reads absence after the lock. No foreign write is needed.
// E9a: guard vs one creator (2 sessions). E9b: guard vs two concurrent creators (3 sessions); also counts duplicate
//      memberships (INV-M), since wb_membership deliberately has no unique key.
function e9_guard(string $protocol): array {
    return [
        ['P', function (PDO $c, array &$s) { $c->beginTransaction(); $s['ok'] = true; }],
        ['P', function (PDO $c, array &$s) use ($protocol) {
            if ($protocol === 'PARENT_TOKEN') {
                $v = (int) $c->query("select version from wb_tok_mem where scope='admissions:o2' for share")->fetchColumn();
                $absent = count($c->query("select 1 from wb_membership where person='p' and org='o2'")->fetchAll()) === 0;
                $s['ok'] = $v === 0 && $absent;        // decision read set: admissions:o2 @ 0, membership absent
            } else {
                $rows = $c->query("select 1 from wb_membership where person='p' and org='o2' for share")->fetchAll();
                $s['ok'] = count($rows) === 0;
            }
        }],
        ['P', function (PDO $c, array &$s) {
            if ($s['ok']) { $c->exec("insert into wb_post(author) values ('p-not-in-o2')"); $s['post_written'] = true; }
        }],
        commit_step('P'),
    ];
}
function e9_create(string $actor, string $protocol): array {
    return [
        [$actor, function (PDO $c, array &$s) { $c->beginTransaction(); }],
        [$actor, function (PDO $c, array &$s) use ($protocol) {
            if ($protocol === 'PARENT_TOKEN') {
                $c->query("select version from wb_tok_mem where scope='admissions:o2' for update")->fetchAll();
            }
            $exists = count($c->query("select 1 from wb_membership where person='p' and org='o2'" . ($protocol === 'ROW_ONLY' ? ' for share' : ''))->fetchAll()) > 0;
            if (!$exists) {
                if ($protocol === 'PARENT_TOKEN') $c->exec("update wb_tok_mem set version = version + 1 where scope='admissions:o2'");
                $c->exec("insert into wb_membership values ('p', 'o2', 1)");
            }
        }],
        commit_step($actor),
    ];
}
if ($want('E9')) {
    foreach (['ROW_ONLY', 'PARENT_TOKEN'] as $protocol) {
        $r = ['schedules' => 0, 'unauthorised' => 0, 'actions' => 0, 'deadlocks' => 0];
        foreach (merges(e9_guard($protocol), e9_create('G', $protocol)) as $schedule) {
            reset_schema($A);
            $st = execute($schedule, $conns);
            $r['schedules']++;
            $written = (int) $A->query('select count(*) from wb_post')->fetchColumn() > 0;
            $r['actions'] += $written;
            $r['deadlocks'] += $st['deadlock'];
            $r['unauthorised'] += unauthorised_after($st, ['G'], $written);
        }
        $out['E9a_absent_aggregate_guard'][$protocol] = $r;
        emit('E9a_absent_aggregate_guard.' . $protocol, $r);
        $r = ['schedules' => 0, 'unauthorised' => 0, 'duplicate_memberships' => 0, 'deadlocks' => 0];
        $conns3 = $conns3 ?? ['P' => $A, 'G' => $B, 'H' => conn($dsn, $user, $expectedDb)];
        foreach (merges3(e9_guard($protocol), e9_create('G', $protocol), e9_create('H', $protocol)) as $schedule) {
            reset_schema($A);
            $st = execute($schedule, $conns3);
            $r['schedules']++;
            $written = (int) $A->query('select count(*) from wb_post')->fetchColumn() > 0;
            $r['deadlocks'] += $st['deadlock'];
            $r['unauthorised'] += unauthorised_after($st, ['G', 'H'], $written);
            $r['duplicate_memberships'] += ((int) $A->query("select count(*) from wb_membership where person='p' and org='o2'")->fetchColumn()) > 1;
        }
        $out['E9b_three_sessions_concurrent_creation'][$protocol] = $r;
        emit('E9b_three_sessions_concurrent_creation.' . $protocol, $r);
    }
    emit('E9a_absent_aggregate_guard', $out['E9a_absent_aggregate_guard']);
    emit('E9b_three_sessions_concurrent_creation', $out['E9b_three_sessions_concurrent_creation']);
}

$A->exec('drop table if exists wb_scope, wb_tenure, wb_suspension, wb_post, wb_tok_mem, wb_tok_gov, wb_tok_com, wb_deleg, wb_membership');
echo json_encode($out, JSON_PRETTY_PRINT), "\n";
