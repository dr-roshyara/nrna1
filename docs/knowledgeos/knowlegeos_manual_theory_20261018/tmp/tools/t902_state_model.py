"""T9-02 theory turn (the state space), 2026-11-02: form checks only.

Built on ce_model.py (CE*, D, cuts), unchanged and imported. Each check exhibits a small pair of records in D and
compares Q1's reference values (CE*, at cuts and after one appended entry). It tests the FORM of the claims in
section 9.20 (which distinctions a state must keep for Req's core observers); it shows nothing about KnowledgeOS's
content, and a finite check proves no general claim. Labels: every assertion is a property of the exhibited pair.

  M1  an attribute vector per item (standing, provenance, grade) is not sufficient: two records with equal vectors
      diverge after the same appended annulment (DC-3).
  M2  record order is needed: two records with the same entries in a different order give different Q1 answers at a
      cut (H2), although their final vectors are equal.
  M3  act time is needed apart from record order: two records with the same positions, differing only in a
      revocation's act time, give different Q1 answers.
  M4  a stored derived value (R+C) goes stale: standing stored when an adoption is appended differs from CE* after a
      later annulment of its grant, which voids the grant from the start.
  M5  two records that differ only in the recorder field give equal Q1 answers at every cut and after each appended
      entry of a small alphabet; they differ as entries (Q2 returns entries).
  (v2, settlement of the turn: reviewers A, B, C)
  M5b with authentication status signed-as-other, the same pair differs in Q1's marks ('inconsistent recording
      status'): M5 is a statement about Q1's values only (A-06, C F-8).
  M6  two records that differ only in the act time of the root (0 vs -3) or of a grant (1 vs 0) give equal Q1
      values at every cut, item and act time tried, after none or one of five appended entries (A-03): a
      DC-2 pair for Q1's values on entries about no item.
"""
import os
import sys

sys.path.insert(0, os.path.dirname(os.path.abspath(__file__)))
from ce_model import Record, ce_star, ce_star_cut, in_D, marks, G, T, F, U  # noqa: E402

DOM = {('s', 's')}


def R(entries):
    return Record([dict(x) for x in entries], DOM)


ROOT = {'id': 'r', 'kind': 'root', 'actor': 'P', 'scope': 's', 't': 0}


def AD(i, actor, cites, item, t=2, vfrom=0, vto=9):
    return {'id': i, 'kind': 'adopt', 'actor': actor, 'cites': cites, 'item': item, 'scope': 's', 't': t,
            'vfrom': vfrom, 'vto': vto}


def ANN(i, actor, cites, target, t=3):
    return {'id': i, 'kind': 'annul', 'actor': actor, 'cites': cites, 'target': target, 'scope': 's', 't': t}


def REV(i, actor, cites, item, t):
    return {'id': i, 'kind': 'revoke', 'actor': actor, 'cites': cites, 'item': item, 'scope': 's', 't': t}


def q1(rec, item, t=5):
    v = ce_star(rec, item, t)
    return v


def vector(rec, item, t=5):
    """The attribute vector V of the check: standing (CE*), provenance (the adopting actor), grade (no evidence
    kinds are present in these records, so 'not attested' throughout)."""
    actors = sorted({x['actor'] for x in rec.e if x['kind'] == 'adopt' and x['item'] == item})
    return (ce_star(rec, item, t), tuple(actors), 'not attested')


def main():
    out = []
    # M1
    r1 = [ROOT, G('g1', 'P', ['r'], 'A'), AD('a', 'A', ['g1'], 'k')]
    r2 = [ROOT, G('g2', 'P', ['r'], 'A'), AD('a', 'A', ['g2'], 'k')]
    x = ANN('x', 'P', ['r'], 'g1')
    assert in_D(R(r1))[0] and in_D(R(r2))[0] and in_D(R(r1 + [x]))[0] and in_D(R(r2 + [x]))[0]
    v1, v2 = vector(R(r1), 'k'), vector(R(r2), 'k')
    a1, a2 = q1(R(r1 + [x]), 'k'), q1(R(r2 + [x]), 'k')
    out.append(f'M1 vectors equal: {v1 == v2} ({v1}); after annul(g1): r1 -> {a1}, r2 -> {a2}')
    assert v1 == v2 and a1 == F and a2 == T
    # M2
    r4 = [ROOT, G('g', 'P', ['r'], 'A'), AD('a', 'A', ['g'], 'k', t=2), AD('b', 'A', ['g'], 'k2', t=2)]
    r5 = [ROOT, G('g', 'P', ['r'], 'A'), AD('b', 'A', ['g'], 'k2', t=2), AD('a', 'A', ['g'], 'k', t=2)]
    f4 = (vector(R(r4), 'k'), vector(R(r4), 'k2'))
    f5 = (vector(R(r5), 'k'), vector(R(r5), 'k2'))
    c4 = (ce_star_cut(R(r4), 3, 'k', 5), ce_star_cut(R(r4), 3, 'k2', 5))
    c5 = (ce_star_cut(R(r5), 3, 'k', 5), ce_star_cut(R(r5), 3, 'k2', 5))
    out.append(f'M2 final vectors equal: {f4 == f5}; Q1 at cut 3 (k, k2): r4 -> {c4}, r5 -> {c5}')
    assert f4 == f5 and c4 == (T, F) and c5 == (F, T)
    # M3
    r6 = [ROOT, G('g', 'P', ['r'], 'A'), AD('a', 'A', ['g'], 'k', t=3), REV('v', 'P', ['r'], 'k', t=2)]
    r7 = [ROOT, G('g', 'P', ['r'], 'A'), AD('a', 'A', ['g'], 'k', t=3), REV('v', 'P', ['r'], 'k', t=4)]
    assert in_D(R(r6))[0] and in_D(R(r7))[0]
    b6, b7 = q1(R(r6), 'k'), q1(R(r7), 'k')
    out.append(f'M3 same positions, revocation at act time 2 vs 4: r6 -> {b6}, r7 -> {b7}')
    assert b6 == T and b7 == F
    # M4
    stored = q1(R(r1), 'k')                      # the value a store would keep when 'a' is appended
    later = q1(R(r1 + [x]), 'k')                 # CE* after annul(g1), which voids g1 from the start
    early = ce_star(R(r1 + [x]), 'k', 2)         # and at the adoption's own act time
    out.append(f'M4 stored at append: {stored}; CE* after annul(g1), t=5: {later}; at t=2: {early}')
    assert stored == T and later == F and early == F
    # M5
    r8 = [ROOT, G('g', 'P', ['r'], 'A'), dict(AD('a', 'A', ['g'], 'k'), recorder='A')]
    r9 = [ROOT, G('g', 'P', ['r'], 'A'), dict(AD('a', 'A', ['g'], 'k'), recorder='Z')]
    alphabet = [ANN('x1', 'P', ['r'], 'g'), ANN('x2', 'P', ['r'], 'a'), REV('v1', 'P', ['r'], 'k', 4),
                AD('b', 'A', ['g'], 'k', t=4), G('h', 'P', ['r'], 'B')]
    same = 0
    total = 0
    for e in [None] + alphabet:
        s8 = R(r8 + ([e] if e else []))
        s9 = R(r9 + ([e] if e else []))
        assert in_D(s8)[0] and in_D(s9)[0]
        for item in ('k', 'g'):
            for t in range(0, 7):
                for s in range(1, len(s8.e) + 1):
                    total += 1
                    same += ce_star_cut(s8, s, item, t) == ce_star_cut(s9, s, item, t)
    entries_differ = R(r8).e[2]['recorder'] != R(r9).e[2]['recorder']
    out.append(f'M5 recorder A vs Z: Q1 equal on {same} of {total} (item, t, cut, extension) cases; '
               f'entries differ: {entries_differ}')
    assert same == total and entries_differ
    # M5b (v2)
    m8 = marks(R([ROOT, G('g', 'P', ['r'], 'A'), dict(AD('a', 'A', ['g'], 'k'), recorder='A', auth='signed-as-other')]), 'k', 5)
    m9 = marks(R([ROOT, G('g', 'P', ['r'], 'A'), dict(AD('a', 'A', ['g'], 'k'), recorder='Z', auth='signed-as-other')]), 'k', 5)
    out.append(f'M5b signed-as-other, recorder A vs Z: marks differ: {m8 != m9}')
    assert m8 != m9
    # M6 (v2)
    def base(rt=0, gt=1):
        return [dict(ROOT, t=rt), G('g', 'P', ['r'], 'A', t=gt), AD('a', 'A', ['g'], 'k')]
    alpha6 = [None, ANN('x1', 'P', ['r'], 'g'), REV('v1', 'P', ['r'], 'k', 4), AD('b', 'A', ['g'], 'k', t=4),
              G('h', 'P', ['r'], 'B', t=3), AD('c', 'B', ['h'], 'k', t=5)]
    for name, (p, q) in (('root act time 0 vs -3', (base(0, 1), base(-3, 1))),
                         ('grant act time 1 vs 0', (base(0, 1), base(0, 0)))):
        same6 = tot6 = 0
        for e in alpha6:
            s1, s2 = R(p + ([e] if e else [])), R(q + ([e] if e else []))
            if not (in_D(s1)[0] and in_D(s2)[0]):   # an extension citing an absent grant leaves D: not counted
                assert in_D(s1)[0] == in_D(s2)[0]
                continue
            for item in ('k', 'g'):
                for t in range(-3, 8):
                    for s in range(1, len(s1.e) + 1):
                        tot6 += 1
                        same6 += ce_star_cut(s1, s, item, t) == ce_star_cut(s2, s, item, t)
        out.append(f'M6 {name}: Q1 values equal on {same6} of {tot6} cases')
        assert same6 == tot6
    for line in out:
        print(line)
    print('all assertions passed')


if __name__ == '__main__':
    main()
