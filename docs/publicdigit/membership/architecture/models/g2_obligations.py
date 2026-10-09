"""G-2 bounded model: financial obligation vs payment under Membership corrections.

Finite domain: months t in [0,T). One member. Facts (valid time):
  Admit@a (a in [0,T)), optional End@e (a<e<=T-1), and at most one later correction
  recorded at month r: annul the End (r>e) or annul the Admit (r>a). Fee rule: 1 unit
  per month in force (a deliberately simple rule; any rule that is a function of
  in-force months behaves the same way under this model).

Approaches compared against TRUTH = rule applied to the final (corrected) facts:
  STORED : a monthly job at month t writes a charge if the member is in force AS KNOWN
           AT t (legacy shape); End waives unpaid charges (legacy Member::endMembership);
           corrections write nothing (no handler).
  DERIVED: obligation(t) = rule(in_force_final(t)); recomputed on read.
Payments: member pays each charge in the month it is charged (pay=1) or never (pay=0).
Payments are settlement FACTS; they are never altered by membership corrections.
Checked properties:
  P1 obligations equal TRUTH                 (expected: DERIVED yes, STORED no)
  P2 payments invariant under corrections    (both: by construction - asserted)
  P3 balance = obligations - payments may be negative (credit) after corrections:
     count cases; credit must be representable (Finance owns refund decision).
"""
from itertools import product
T = 6

def in_force(a, e, annul_end, annul_admit):
    def f(t):
        if annul_admit: return False
        end = None if (e is None or annul_end) else e
        return a <= t and (end is None or t < end)
    return f

def histories():
    for a in range(T):
        for e in [None] + list(range(a + 1, T)):
            yield (a, e, None, None)
            if e is not None:
                for r in range(e + 1, T): yield (a, e, 'annul_end', r)
            for r in range(a + 1, T): yield (a, e, 'annul_admit', r)

stats = dict(cases=0, stored_wrong=0, derived_wrong=0, credit_derived=0, stored_over=0, stored_under=0)
smallest = None
for (a, e, corr, r), pay in product(histories(), [0, 1]):
    stats['cases'] += 1
    final = in_force(a, e, corr == 'annul_end', corr == 'annul_admit')
    truth = sum(1 for t in range(T) if final(t))
    # STORED: knowledge at month t = facts recorded up to t
    charges = []          # [month, paid, waived]
    for t in range(T):
        known = in_force(a, e if (e is not None and e <= t) else None,
                         corr == 'annul_end' and r <= t, corr == 'annul_admit' and r <= t)
        if known(t):
            charges.append([t, bool(pay), False])
        if e is not None and t == e and not (corr == 'annul_end' and r <= t):
            for c in charges:
                if not c[1]: c[2] = True          # End waives unpaid charges
    stored = sum(1 for c in charges if not c[2])
    payments = sum(1 for c in charges if c[1])
    derived = truth
    if stored != truth:
        stats['stored_wrong'] += 1
        stats['stored_over' if stored > truth else 'stored_under'] += 1
        cand = (T, a, e, corr, r, pay, stored, truth)
        if smallest is None or (e or 0, r or 0) < (smallest[2] or 0, smallest[4] or 0): smallest = cand
    if derived != truth: stats['derived_wrong'] += 1
    if derived - payments < 0: stats['credit_derived'] += 1

print(stats)
print('example (T,a,e,corr,r,pay,stored,truth):', smallest)
assert stats['derived_wrong'] == 0
