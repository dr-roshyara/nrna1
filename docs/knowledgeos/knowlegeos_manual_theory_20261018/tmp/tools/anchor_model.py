#!/usr/bin/env python3
"""Small-scope model for §9.17 R4 (theory turn 8): when does comparing against an outside reference detect a loss
before an irreversible removal?  Run: python3 -I tmp/tools/anchor_model.py

World (exhaustive over a small scope):
  - entries e1..e3, each written to the SOURCE at a time in {0,1,2,3} or never;
  - the SURVIVOR (copy) is made at time tc in {0..3}: it receives the source's entries written at or before tc;
  - the survivor may then lose one of its entries (a faulty copy step), or not;
  - a reference is taken at ta in {0..3}; it lists the source's entries written at or before ta (a list, so the
    comparison is exact: no hash collision);
  - the check runs at time 4, then the source is removed.
  A LOSS is an entry written to the source by time 4 that the survivor does not hold after removal.

Conditions (one choice each):
  A1 coverage   : the reference is taken after the last write before the removal (ta >= last write time)
  A2 independence: the reference comes from the source at ta, fixed before the check; the rival takes it from
                   the survivor at check time (derived from the checked content)
  A3 operand    : the check compares the SURVIVOR with the reference; the rival compares the SOURCE

Claims checked:
  S  (sufficiency) A1 and A2 and A3  =>  every loss is detected
  N1..N3 (independence / necessity) for each condition, a scenario where the other two hold, it fails, and a loss
         goes undetected
  T  (trivial cases) no writes: no loss, nothing to detect; a refuse-all check (always "differs") detects every loss
     and also blocks every loss-free removal, so detection alone is not the requirement: the check must also PASS
     when nothing is lost (checked: A1-A3 give no false alarm).
"""
import itertools

TIMES = [0, 1, 2, 3]
NEVER = None


def run(writes, tc, drop, ta, indep, operand_survivor):
    source = {e for e, t in writes.items() if t is not NEVER}
    survivor = {e for e, t in writes.items() if t is not NEVER and t <= tc}
    if drop is not None and drop in survivor:
        survivor.discard(drop)
    ref = {e for e, t in writes.items() if t is not NEVER and t <= ta} if indep else set(survivor)
    checked = survivor if operand_survivor else source      # the source as it stands at the check (time 4)
    detected = checked != ref
    loss = source - survivor
    return loss, detected


def scenarios():
    ents = ['e1', 'e2', 'e3']
    for wt in itertools.product([NEVER] + TIMES, repeat=3):
        writes = dict(zip(ents, wt))
        for tc in TIMES:
            for drop in [None] + ents:
                for ta in TIMES:
                    for indep in (True, False):
                        for opv in (True, False):
                            yield writes, tc, drop, ta, indep, opv


def a1(writes, ta):
    last = max([t for t in writes.values() if t is not NEVER], default=-1)
    return ta >= last


def main():
    n = 0
    suff_fail, false_alarm = [], []
    witness = {'A1': None, 'A2': None, 'A3': None}
    for writes, tc, drop, ta, indep, opv in scenarios():
        n += 1
        loss, det = run(writes, tc, drop, ta, indep, opv)
        c = {'A1': a1(writes, ta), 'A2': indep, 'A3': opv}
        if all(c.values()):
            if loss and not det:
                suff_fail.append((writes, tc, drop, ta))
            if not loss and det:
                false_alarm.append((writes, tc, drop, ta))
        for k in c:
            others = all(v for kk, v in c.items() if kk != k)
            if others and not c[k] and loss and not det and witness[k] is None:
                witness[k] = dict(writes=writes, tc=tc, drop=drop, ta=ta, loss=sorted(loss))
    print(f'scenarios: {n}')
    print(f'S  sufficiency (A1 & A2 & A3 => every loss detected): {"HOLDS" if not suff_fail else "FAILS"}'
          f' ({len(suff_fail)} counterexamples)')
    print(f'   no false alarm under A1 & A2 & A3 (a loss-free removal passes): '
          f'{"HOLDS" if not false_alarm else "FAILS"} ({len(false_alarm)})')
    for k in ('A1', 'A2', 'A3'):
        print(f'N  {k} necessary (others hold, {k} fails, loss undetected): '
              f'{"witness " + str(witness[k]) if witness[k] else "NO WITNESS"}')
    # trivial cases
    empty = {'e1': NEVER, 'e2': NEVER, 'e3': NEVER}
    l, d = run(empty, 0, None, 0, True, True)
    print(f'T  no writes: loss {sorted(l)}, detected {d} (nothing to detect)')
    print('T  refuse-all check (always "differs") would detect every loss but block every removal; the model\'s '
          'check is required to pass when nothing is lost, which the no-false-alarm line tests')


if __name__ == '__main__':
    main()
