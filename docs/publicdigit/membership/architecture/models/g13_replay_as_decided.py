"""G-13 decision replay (AS_DECIDED) under concurrent commits and missing history. Research only.

Commission: "Close the cross-domain architecture verification round" (2026-10-11), Task 5.
No production code. Randomised but seeded (reproducible); counts are generated histories, NOT probabilities.

Model:
  * One Membership scope 'm' (person p, organisation o). Facts: admit, suspend, restore, annul(target seq).
  * Transactions BEGIN at time b and COMMIT at a later time c; several may overlap. A fact's recordedAt = b
    (assigned during the transaction, as in practice), its per-scope seq is assigned AT COMMIT under the scope lock
    (as PostgresMemberRepository does: seq = new version of the CAS update).
  * Decisions are taken at random times on the committed state; the DecisionRecord keeps: read set {m: seq},
    policy version id, algorithm id, t, outcome.
  * Policy versions (forward-only): v1 = permit iff member and not suspended; v2 (activated later) = permit iff member.
Replay strategies:
  SEQ        : facts with seq <= readSet[m]; pinned policy version  -> must equal the recorded outcome
  TIMESTAMP  : facts with recordedAt <= t; pinned policy version   -> shows why timestamps are not a cutoff
  HINDSIGHT  : all facts, pinned policy                            -> expected to differ (not an error)
Missing-history checks: the policy version or a log prefix is pruned with some probability; SEQ replay must return
RECONSTRUCTION_INCOMPLETE, never a value computed from incomplete data.
Run: python3 -I docs/publicdigit/membership/architecture/models/g13_replay_as_decided.py
"""
import json
import random

rng = random.Random(20261011)
N = 20000


def resolve(facts):
    """facts: list of (seq, kind, target) ordered by seq; returns (member, suspended)."""
    annulled = set()
    for seq, kind, target in reversed(facts):                 # an annul is live unless itself annulled
        if kind == 'annul' and seq not in annulled:
            annulled.add(target)
    member = suspended = False
    for seq, kind, target in facts:
        if seq in annulled or kind == 'annul':
            continue
        if kind == 'admit':
            member, suspended = True, False
        elif kind == 'suspend' and member:
            suspended = True
        elif kind == 'restore':
            suspended = False
    return member, suspended


POLICY = {'v1': lambda m, s: m and not s, 'v2': lambda m, s: m}

stats = dict(histories=N, decisions=0,
             SEQ_mismatch=0, TIMESTAMP_mismatch=0, HINDSIGHT_differs=0,
             pruned_cases=0, SEQ_incomplete_reported=0, SEQ_silent_wrong_after_pruning=0)
for _ in range(N):
    # generate overlapping transactions on scope m
    txs = []
    t = 0
    for _ in range(rng.randint(1, 5)):
        b = t + rng.randint(0, 2)
        c = b + rng.randint(1, 4)
        kind = rng.choice(['admit', 'suspend', 'restore', 'annul'])
        txs.append(dict(b=b, c=c, kind=kind))
        t = b
    # commit in commit-time order; seq assigned at commit; ties broken by begin time (deterministic)
    log = []
    for tx in sorted(txs, key=lambda x: (x['c'], x['b'])):
        seq = len(log) + 1
        target = rng.randint(1, seq - 1) if (tx['kind'] == 'annul' and seq > 1) else None
        kind = tx['kind'] if (tx['kind'] != 'annul' or target) else 'admit'
        log.append(dict(seq=seq, kind=kind, target=target, recorded_at=tx['b'], committed_at=tx['c']))
    horizon = max(x['committed_at'] for x in log) + 2
    policy_switch = rng.randint(0, horizon)                     # v2 active from here (forward-only)
    for _ in range(rng.randint(1, 3)):
        td = rng.randint(0, horizon)
        committed = [x for x in log if x['committed_at'] <= td]
        read_seq = max([x['seq'] for x in committed], default=0)
        pol = 'v2' if td >= policy_switch else 'v1'
        m, s = resolve([(x['seq'], x['kind'], x['target']) for x in committed])
        outcome = POLICY[pol](m, s)
        stats['decisions'] += 1
        # SEQ replay
        f_seq = [(x['seq'], x['kind'], x['target']) for x in log if x['seq'] <= read_seq]
        stats['SEQ_mismatch'] += POLICY[pol](*resolve(f_seq)) != outcome
        # TIMESTAMP replay (recordedAt <= t)
        f_ts = [(x['seq'], x['kind'], x['target']) for x in sorted(log, key=lambda x: x['seq']) if x['recorded_at'] <= td]
        stats['TIMESTAMP_mismatch'] += POLICY[pol](*resolve(f_ts)) != outcome
        # HINDSIGHT
        f_all = [(x['seq'], x['kind'], x['target']) for x in log]
        stats['HINDSIGHT_differs'] += POLICY[pol](*resolve(f_all)) != outcome
        # pruning: drop the policy version or truncate the retained log prefix
        if rng.random() < 0.2:
            stats['pruned_cases'] += 1
            retained_policies = {k for k in POLICY if not (k == pol and rng.random() < 0.5)}
            retained_from = rng.randint(1, max(1, read_seq))      # facts with seq < retained_from were pruned
            complete = pol in retained_policies and (retained_from == 1 or read_seq == 0)
            if not complete:
                stats['SEQ_incomplete_reported'] += 1            # contract: RECONSTRUCTION_INCOMPLETE
            else:
                kept = [(x['seq'], x['kind'], x['target']) for x in log if retained_from <= x['seq'] <= read_seq]
                stats['SEQ_silent_wrong_after_pruning'] += POLICY[pol](*resolve(kept)) != outcome
print(json.dumps(stats, indent=1))
