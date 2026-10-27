#!/usr/bin/env python3
"""Small-scope model for §9.17 R4 (theory turn 8), version 2, rebuilt at the turn-8 settlement after reviews A and B
(A-01, A-02, A-03; B-02, B-03): version 1 modelled only copy loss, so its "sufficiency" held by construction.
Run: python3 -I tmp/tools/anchor_model.py

World (exhaustive over a small scope). A record rho has members; it moves from carrier OLD to carrier NEW.
  - members m1..m3: each accepted into OLD at a time in {0,1,2,3} (while OLD is designated) or never;
  - a foreign entry f (a non-member, e.g. a write to OLD after it was demoted, or an override write) may lie in OLD;
  - OLD may LOSE one member before the check (a lost update or out-of-band removal; K1 failure on the old carrier);
  - NEW receives OLD's members accepted at or before tc (the carry-across), and may then drop one (a faulty copy);
  - a reference R is taken at ta, from one origin:  'old'  = OLD's content at ta (members and any foreign entry),
                                                     'new'  = NEW's content at the check (derived from the survivor),
                                                     'none' = no outside reference (the casualty itself is used);
  - the check runs at time 4, then OLD is retired. No write falls between the check and the act (simultaneity).
  A LOSS is a MEMBER accepted by time 4 that NEW does not hold after the act.

Gates:
  G1  survivor only    : R  is contained in NEW                         (single operand, A3 as first drafted)
  G2  survivor+casualty: R  is contained in NEW, and OLD is contained in R (two operands; extra content in OLD
                         blocks the act, the conservative classification of R3)
  G0  direct           : OLD contained in NEW (no outside reference; the casualty read at the check)

Claims checked (printed with counts):
  C1  G2 with an outside reference not derived from NEW detects every loss of a member that OLD still holds at the
      check; G0 does the same; G1 misses losses whenever R was taken before a later accepted member (coverage).
  C2  a reference derived from NEW (origin 'new') never detects a loss under G1.
  C3  a member lost from OLD before the reference and before the copy is detected by NO gate (out of reach).
  C5  a member lost from OLD after an outside reference covered it is detected by G2 and missed by G0: the
      outside reference's own contribution.
  C4  a foreign entry in OLD blocks G2 and G0 (conservative: preserved, not promoted); with an exact reference and
      no foreign entry and no loss, every gate passes (no false alarm).
"""
import itertools

M = ['m1', 'm2', 'm3']
T = [0, 1, 2, 3]


def worlds():
    for acc in itertools.product([None] + T, repeat=3):
        for foreign in (False, True):
            for lost_old in [None] + M:
              for late in (False, True):
                for tc in T:
                    for drop_new in [None] + M:
                        for ta in T:
                            for origin in ('old', 'new', 'none'):
                                yield dict(zip(M, acc)), foreign, (lost_old, late), tc, drop_new, ta, origin


def run(acc, foreign, lost, tc, drop_new, ta, origin):
    lost_old, late = lost
    members = {m for m, t in acc.items() if t is not None}
    old = set(members) | ({'f'} if foreign else set())
    if lost_old in old:
        old.discard(lost_old)       # lost before the check and before the copy; 'late' = after the reference was taken
    new = {m for m, t in acc.items() if t is not None and t <= tc and m in old}   # carry-across of OLD's members
    if drop_new in new:
        new.discard(drop_new)
    if origin == 'old':
        ref = {m for m, t in acc.items() if t is not None and t <= ta and (m in old or (late and m == lost_old))} \
            | ({'f'} if foreign else set())
    elif origin == 'new':
        ref = set(new)
    else:
        ref = None
    loss = members - new
    loss_held = loss & old                                  # losses of members OLD still holds at the check
    g1 = None if ref is None else not (ref <= new)
    g2 = None if ref is None else (not (ref <= new)) or (not (old <= ref))
    g0 = not (old <= new)
    return members, loss, loss_held, g1, g2, g0, ref


def main():
    n = 0
    c1_g2_miss = c1_g0_miss = c1_g1_miss = 0
    c2_detect = 0
    c3_any_detect = c3_cases = 0
    c4_foreign_pass = c4_false_alarm = 0
    g1_witness = None
    c5_cases = c5_g2 = c5_g0 = 0
    for w in worlds():
        acc, foreign, (lost_old, late), tc, drop_new, ta, origin = w
        members, loss, held, g1, g2, g0, ref = run(*w)
        n += 1
        if origin == 'old':
            if held and not g2:
                c1_g2_miss += 1
            if held and not g1:
                c1_g1_miss += 1
                if g1_witness is None:
                    g1_witness = dict(accepted=acc, tc=tc, ta=ta, drop_new=drop_new, loss=sorted(held))
        if held and not g0:
            c1_g0_miss += 1
        if origin == 'new' and loss and g1:
            c2_detect += 1
        if (lost_old in members and not late and loss == {lost_old} and not foreign and origin == 'old' and drop_new is None
                and ta >= max(t for t in acc.values() if t is not None)):
            # the only loss is a member lost from OLD before any read, with nothing else to trip a gate:
            # it is in neither carrier nor in a reference taken from OLD, so no gate can see it.
            c3_cases += 1
            if g2 or g0:
                c3_any_detect += 1
        if (lost_old in members and late and loss == {lost_old} and not foreign and origin == 'old'
                and drop_new is None and acc[lost_old] <= ta):
            c5_cases += 1                       # a member lost from OLD after the reference covered it
            c5_g2 += bool(g2)
            c5_g0 += bool(g0)
        if foreign and origin == 'old' and not g2:
            c4_foreign_pass += 1
        if (not foreign and not loss and origin == 'old' and ta >= max([t for t in acc.values() if t is not None],
                                                                        default=-1) and g2):
            c4_false_alarm += 1
    print(f'worlds: {n}')
    print(f'C1  losses of members OLD still holds at the check, undetected:  G2 (outside ref from OLD) {c1_g2_miss} · '
          f'G0 (direct) {c1_g0_miss} · G1 (survivor only) {c1_g1_miss}')
    print(f'    G1 witness (reference taken before a later member): {g1_witness}')
    print(f'C2  survivor-derived reference under G1 detects a loss: {c2_detect} times (expected 0)')
    print(f'C3  members lost from OLD before any read, with no other loss: {c3_cases} cases; '
          f'detected by G2 or G0 in {c3_any_detect} (expected 0: out of reach)')
    print(f'C4  a foreign entry in OLD passes G2: {c4_foreign_pass} times (expected 0: it blocks); '
          f'false alarms with an exact, covering reference and no loss: {c4_false_alarm} (expected 0)')
    print(f'C5  a member lost from OLD after the reference covered it: {c5_cases} cases; detected by G2 {c5_g2}, '
          f'by G0 (casualty read at the check) {c5_g0}')
    print('Scope: three members, one foreign entry, one loss on each carrier at most; no write between check and act; '
          'a reference is a list. A passing check is not a proof beyond this scope.')


if __name__ == '__main__':
    main()
