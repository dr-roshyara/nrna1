"""G-14 cross-context consistency and simulation study (Committee, Geography, Membership, Assignment). Research only.

Commission: "Cross-Context Consistency and Simulation Study" (2026-10-11). No production code, no repository data.
Each part states its question, its model, and its limits. Exhaustive parts count enumerated cases; the seeded
random part (S2) reports rates under STATED synthetic noise assumptions. Counts are NOT real-world frequencies.

S1 (C3)  Assignment vs Membership over state transitions. Candidate invariant (NOT approved):
         A(p,c,t) => M(p,O(c),t). Rules compared:
           R0 NONE         no relation checked
           R1 CHECK_ONCE   appointment refused unless member at appointment time; later End ignored
           R2 CASCADE      as R1 at appointment, AND End of membership WRITES a vacate (stored)
           R3 DERIVED      effective assignment = held AND member at t (nothing written)
         plus a retroactive Annul of the membership End (the ratified algebra allows it).
S2 (C4/C8) Reconciliation of duplicated legacy assignment representations (A association status, B assignment
         is_active/left_date, C role projection) against a hidden ground truth. Policies:
           P1 LATEST       the most recently updated source wins (status and dates)
           P2 PRIORITY     fixed precedence B > A > C
           P3 EVIDENCE     accept only when all present sources agree; otherwise queue for human adjudication;
                           never overwrite, keep every source row as evidence
S3 (C1/C2/C7) Contradiction detectability: which combinations are detectable by a pure predicate, and which
         predicates cannot be written without a business decision (an approved mapping).
S4 (C6)  Jurisdiction under geography change (unit moved / retired with successor). Semantics compared:
           FOLLOW_ID  live hierarchy by unit id;  SUCCESSOR  follow successor links;  SNAPSHOT  revision pinned
         at decision time. Measures replay stability of past decisions and current coverage after retirement.
Run: python3 -I docs/publicdigit/membership/architecture/models/g14_cross_context_consistency.py
"""
from itertools import product
import json
import random

out = {}

# ---------------------------------------------------------------------------------- S1
EVENTS = ['ADMIT', 'END', 'APPOINT', 'VACATE', 'ANNUL_END']


def run_s1(seq, rule):
    """Returns per-step (member, held_effective) and whether a tenure was lost after reinstatement."""
    member = False
    held = False                     # stored assignment record
    ended_by_end = []                # stored vacates written by CASCADE
    end_live = False                 # is the last END fact live (not annulled)
    states = []
    lost_after_annul = False
    for e in seq:
        if e == 'ADMIT':
            member, end_live = True, False
        elif e == 'END' and member:
            member, end_live = False, True
            if rule == 'R2' and held:
                held = False
                ended_by_end.append(True)
        elif e == 'ANNUL_END' and end_live:
            member, end_live = True, False   # ex tunc: membership was never ended
            if rule == 'R2' and ended_by_end:
                lost_after_annul = True      # the cascaded vacate stays written: tenure lost
        elif e == 'APPOINT':
            if rule in ('R1', 'R2') and not member:
                pass                          # refused
            else:
                held = True
        elif e == 'VACATE':
            held = False
        effective = held and member if rule == 'R3' else held
        states.append((member, effective))
    return states, lost_after_annul


s1 = {r: dict(sequences=0, states_violating_A_implies_M=0, sequences_with_violation=0,
              tenure_lost_after_annulment=0) for r in ('R0', 'R1', 'R2', 'R3')}
for L in range(1, 6):
    for seq in product(EVENTS, repeat=L):
        for r in s1:
            st, lost = run_s1(seq, r)
            v = sum(1 for m, a in st if a and not m)
            s1[r]['sequences'] += 1
            s1[r]['states_violating_A_implies_M'] += v
            s1[r]['sequences_with_violation'] += v > 0
            s1[r]['tenure_lost_after_annulment'] += lost
out['S1_assignment_vs_membership'] = s1

# ---------------------------------------------------------------------------------- S2
def simulate_s2(n, p_lag, p_corrupt, p_missing, seed):
    rng = random.Random(seed)
    res = {p: dict(correct=0, silent_wrong=0, queued=0, evidence_discarded=0) for p in ('P1', 'P2', 'P3')}
    for _ in range(n):
        truth_active = rng.random() < 0.5
        truth_left = None if truth_active else rng.randint(1, 50)
        # each source observes the truth, possibly lagging (stale: shows the opposite status), corrupted or missing
        sources = {}
        for name in ('A', 'B', 'C'):
            if rng.random() < p_missing:
                continue
            stale = rng.random() < p_lag
            active = (not truth_active) if stale else truth_active
            left = None if active else (truth_left if not stale else rng.randint(1, 50))
            if name == 'B' and not active and rng.random() < p_corrupt:
                left = 60 + rng.randint(0, 10)      # C-05 class: a later save rewrote left_date
            sources[name] = dict(active=active, left=left, updated=rng.randint(0, 100))
        if not sources:
            continue

        def score(policy, chosen):
            if chosen is None:
                res[policy]['queued'] += 1
                return
            ok = chosen['active'] == truth_active and (truth_active or chosen['left'] == truth_left)
            res[policy]['correct' if ok else 'silent_wrong'] += 1
            # evidence discarded: a source disagreeing with the chosen one is overwritten by P1/P2
            res[policy]['evidence_discarded'] += sum(
                1 for s in sources.values() if (s['active'], s['left']) != (chosen['active'], chosen['left']))

        score('P1', max(sources.values(), key=lambda s: s['updated']))
        score('P2', next(sources[k] for k in ('B', 'A', 'C') if k in sources))
        vals = {(s['active'], s['left']) for s in sources.values()}
        if len(vals) == 1:
            chosen = next(iter(sources.values()))
            ok = chosen['active'] == truth_active and (truth_active or chosen['left'] == truth_left)
            res['P3']['correct' if ok else 'silent_wrong'] += 1
        else:
            res['P3']['queued'] += 1                 # nothing discarded: all rows kept as evidence
    return res


s2 = {}
for label, (lag, corrupt, missing) in {'low_noise': (0.05, 0.05, 0.10),
                                       'medium_noise': (0.15, 0.20, 0.20),
                                       'high_noise': (0.30, 0.40, 0.30)}.items():
    s2[label] = dict(assumptions=dict(p_stale_source=lag, p_left_date_rewritten_in_B=corrupt, p_source_missing=missing),
                     **simulate_s2(20000, lag, corrupt, missing, seed=20261011))
out['S2_reconciliation_policies'] = s2

# ---------------------------------------------------------------------------------- S3
TYPES = ['national', 'province', 'district', 'ward', 'wing', 'disciplinary']
UNIT_LEVEL = [None, 'province', 'district', 'ward']
ORG_REL = ['same_org', 'other_org', 'official_shared']
s3 = dict(combinations=0, detectable_without_decision=0, needs_approved_mapping=0, examples={})
for t, lvl, rel in product(TYPES, UNIT_LEVEL, ORG_REL):
    if lvl is None and rel != 'same_org':
        continue                                  # no unit referenced: org relation not applicable
    s3['combinations'] += 1
    # C2/C7: tenant boundary is decidable from data alone (unit.organisation_id vs committee.organisation_id)
    if lvl is not None and rel == 'other_org':
        s3['detectable_without_decision'] += 1
        s3['examples'].setdefault('C2_C7_cross_org_reference', f'{t} -> {lvl} unit of another organisation')
        continue
    # C1: whether type and level match needs an approved type->level mapping (incl. which types are non-geographic)
    s3['needs_approved_mapping'] += 1
    if lvl is not None and t in ('province', 'district', 'ward') and t != lvl:
        s3['examples'].setdefault('C1_type_level_mismatch', f'{t} committee -> {lvl} unit')
    if lvl is None and t in ('province', 'district', 'ward'):
        s3['examples'].setdefault('C1_geographic_type_without_unit', f'{t} committee with no unit')
    if lvl is not None and t in ('wing', 'disciplinary'):
        s3['examples'].setdefault('C1_non_geographic_type_with_unit', f'{t} committee -> {lvl} unit')
out['S3_detectability'] = s3

# ---------------------------------------------------------------------------------- S4
def s4():
    """Places x1, x2 under district d1. Committee c covers d1 (assigned at t0). Changes after decisions:
       MOVE   : x2 is re-parented from d1 to d2 at t2
       RETIRE : d1 is retired at t2 with successor d1'; x1, x2 re-parented to d1'
       Decisions 'does c cover x?' taken at t1 (before the change) and replayed at t3; plus current coverage at t3."""
    results = {}
    for change in ('MOVE', 'RETIRE'):
        live_parent_t1 = {'x1': 'd1', 'x2': 'd1'}
        live_parent_t3 = dict(live_parent_t1)
        retired, successor = set(), {}
        if change == 'MOVE':
            live_parent_t3['x2'] = 'd2'
        else:
            retired.add('d1'); successor['d1'] = "d1'"
            live_parent_t3 = {'x1': "d1'", 'x2': "d1'"}
        snapshot_t1 = dict(live_parent_t1)
        r = {}
        for sem in ('FOLLOW_ID', 'SUCCESSOR', 'SNAPSHOT'):
            def covers(x, parents, at_t3):
                target = 'd1'
                if sem == 'SUCCESSOR' and at_t3 and target in successor:
                    target = successor[target]
                if sem == 'FOLLOW_ID' and at_t3 and target in retired:
                    return False
                return parents[x] == target
            original = {x: covers(x, live_parent_t1, False) for x in ('x1', 'x2')}
            replay_parents = snapshot_t1 if sem == 'SNAPSHOT' else live_parent_t3
            replay = {x: covers(x, replay_parents, sem != 'SNAPSHOT') for x in ('x1', 'x2')}
            current = {x: covers(x, live_parent_t3, True) for x in ('x1', 'x2')}
            r[sem] = dict(replay_changed_past_decisions=sum(original[x] != replay[x] for x in original),
                          current_coverage=sum(current.values()))
        results[change] = r
    return results


out['S4_jurisdiction_under_geography_change'] = s4()
print(json.dumps(out, indent=1))
