"""G-5 committee authority model (commission 20261009_2155 §7-§8). Research only.

Each sub-model enumerates a finite domain, states an independent SPEC, and compares one or two
plausible implementation strategies against it. Counts are enumerated cases, NOT probabilities.
No production code is involved; Membership facts appear only as given membership intervals
(Admit/End semantics; no Annul/Correct), so the kernel filter is not needed here.

M1 delegation vs mandate amendment     (candidate inv.: a delegate cannot exceed the CURRENT mandate)
M2 retroactive tenure end (bitemporal) (candidate inv.: an expired appointment cannot authorise)
M3 quorum: appointed vs effective seats when a seat requires organisational membership (BQ-P2)
M4 recusal / conflict of interest      (decision-procedure variation)
M5 separation of duties (approve vs execute)
Run: python3 -I docs/publicdigit/membership/architecture/models/g5_committee_authority.py
"""
from itertools import product, combinations
T = 5
out = {}

# ---- M1 -------------------------------------------------------------------------------
# Mandate of the committee contains act 'x' until an amendment at ta (None = never removed).
# Delegation of 'x' to a person: created dc, valid [dc, de). Use at u.
def spec_m1(ta, dc, de, u):                      # independent spec: valid delegation AND act in mandate at use
    return dc <= u < de and (ta is None or u < ta)
def create_check(ta, dc, de, u):                 # strategy A: mandate validated once, at delegation creation
    return dc <= u < de and (ta is None or dc < ta)
def use_check(ta, dc, de, u):                    # strategy B: mandate history consulted at use
    mandate_at = [('x' in ({'x'} if (ta is None or t < ta) else set())) for t in range(T)]
    return dc <= u < de and mandate_at[u]
c = dict(cases=0, create_check_exceeds_mandate=0, use_check_disagrees=0); ex = None
for ta, dc in product([None] + list(range(1, T)), range(T)):
    for de, u in product(range(dc + 1, T + 1), range(T)):
        c['cases'] += 1; s = spec_m1(ta, dc, de, u)
        if create_check(ta, dc, de, u) != s:
            c['create_check_exceeds_mandate'] += 1; ex = ex or dict(amended=ta, delegated=(dc, de), used=u)
        if use_check(ta, dc, de, u) != s: c['use_check_disagrees'] += 1
out['M1'] = dict(c, minimal=ex)

# ---- M2 -------------------------------------------------------------------------------
# Tenure [s, e) whose end e is RECORDED at r >= e (late) or never. Action at u, recorded at u.
c = dict(cases=0, valid_as_known_but_not_as_now=0, stored_flag_equals_as_known=0, stored_flag_cannot_answer_as_now=0)
for s, e in product(range(T), range(1, T + 1)):
    if e <= s: continue
    for r in list(range(e, T + 1)) + [None]:
        for u in range(T):
            c['cases'] += 1
            end_known_at_u = r is not None and r <= u
            as_known = s <= u and (not end_known_at_u or u < e)
            as_now = s <= u and (r is None or u < e)
            flag = s <= u and not end_known_at_u           # role flag cleared when the end is recorded
            if as_known and not as_now: c['valid_as_known_but_not_as_now'] += 1
            if flag == as_known: c['stored_flag_equals_as_known'] += 1
            if flag != as_now: c['stored_flag_cannot_answer_as_now'] += 1
out['M2'] = c

# ---- M3 -------------------------------------------------------------------------------
# 3 seats, all appointed for [0,T). Person i's organisational membership ends at m_i (None = never).
# Decision at t; present set P, yes set Y within P. Procedure: quorum = >=2 counted present;
# pass = counted yes > counted present / 2. EFFECTIVE counts only seats whose holder is a member at t.
def passes(P, Y, counted):
    Pc, Yc = [p for p in P if counted(p)], [p for p in Y if counted(p)]
    return len(Pc) >= 2 and len(Yc) * 2 > len(Pc)
c = dict(cases=0, differ=0, appointed_passes_effective_fails=0, effective_passes_appointed_fails=0); ex = None
seats = (0, 1, 2)
for ms in product([None, 1, 3], repeat=3):
    for t in range(T):
        member = lambda p: ms[p] is None or t < ms[p]
        for k in range(4):
            for P in combinations(seats, k):
                for j in range(len(P) + 1):
                    for Y in combinations(P, j):
                        c['cases'] += 1
                        a, e_ = passes(P, Y, lambda p: True), passes(P, Y, member)
                        if a != e_:
                            c['differ'] += 1
                            c['appointed_passes_effective_fails' if a else 'effective_passes_appointed_fails'] += 1
                            ex = ex or dict(membership_ends=ms, t=t, present=P, yes=Y)
out['M3'] = dict(c, minimal=ex)

# ---- M4 -------------------------------------------------------------------------------
# Subject of the decision is seat holder `subj` (or None = outsider). Recusal removes the subject
# from presence and votes. Same procedure as M3, all holders eligible.
c = dict(cases=0, passes_only_without_recusal=0, passes_only_with_recusal=0)
for subj in (None, 0, 1, 2):
    for k in range(4):
        for P in combinations(seats, k):
            for j in range(len(P) + 1):
                for Y in combinations(P, j):
                    c['cases'] += 1
                    no_rec = passes(P, Y, lambda p: True)
                    rec = passes(P, Y, lambda p: p != subj)
                    if no_rec and not rec: c['passes_only_without_recusal'] += 1
                    if rec and not no_rec: c['passes_only_with_recusal'] += 1
out['M4'] = c

# ---- M5 -------------------------------------------------------------------------------
# A payment needs an approval by someone holding 'approve' and an execution by someone holding
# 'execute'. Role assignments over 2 persons. Count assignments where ONE person can do both.
c = dict(role_assignments=0, single_person_can_complete=0)
for roles in product([frozenset(), frozenset({'approve'}), frozenset({'execute'}), frozenset({'approve', 'execute'})], repeat=2):
    c['role_assignments'] += 1
    if any({'approve', 'execute'} <= r for r in roles): c['single_person_can_complete'] += 1
out['M5'] = c

import json; print(json.dumps(out, indent=1, default=str))
