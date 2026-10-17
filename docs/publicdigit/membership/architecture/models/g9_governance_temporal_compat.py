"""G-9 Governance temporal compatibility with the ratified fact pattern. Research only.

Commission: cross-domain architecture v1.1 §6.2 (T-1, T-3, T-5, T-6). No production code.
Counts are enumerated histories, NOT probabilities.

Domain: one person, one position, one committee; months t in [0, H). A history is a recording-ordered
list of facts; the recording time of fact i is i (so effectiveAt may lie before or after recording).
Fact kinds: Appoint(e) opens a tenure, Vacate(e) ends it, Dissolve(e) dissolves the committee,
Annul(i) sets fact i aside ex tunc (an Annul may target an Annul, which restores its target).

REFERENCE (derived, the ratified pattern):
   live(f)       <=> no live Annul targets f
   Held(t)       <=> exists live Appoint a, a.e <= t, and no live Vacate v with a.e <= v.e <= t
   Active(t)     <=> no live Dissolve d with d.e <= t
   Effective(t)  =  Held(t) and Active(t)                               (no write on dissolution)
   KnownAt(k)    =  resolution over facts 0..k-1 only
CASCADE  : on recording a Dissolve while Held(d.e) as known, a Vacate(d.e) consequence is STORED;
           annulling the Dissolve does not remove it (the legacy event-handler shape).
STATUS   : a status field set on recording: Appoint makes the holder active from its recording time.

T-1 future-effective appointment : STATUS reports Effective before Appoint.e        (count histories)
T-3 dissolution, then annulled    : CASCADE != REFERENCE                              (count histories)
T-6 counterfactual  Res(F . Annul(c)) == Res(F without c)  for every last-fact Annul (REFERENCE, CASCADE)
T-5 policy versions: a decision pins a version id; IN-PLACE retroactive correction vs FORWARD-ONLY new version;
    replay AS_DECIDED must reproduce the recorded result.
Run: python3 -I docs/publicdigit/membership/architecture/models/g9_governance_temporal_compat.py
"""
from itertools import product
import json

H = 4          # months 0..3
MAXLEN = 4


def alphabet(i):
    acts = [('A', e) for e in range(H)] + [('V', e) for e in range(H)] + [('D', e) for e in range(H)]
    return acts + [('X', j) for j in range(i)]       # X = Annul(j), only earlier facts


def histories():
    def rec(prefix):
        yield list(prefix)
        if len(prefix) == MAXLEN:
            return
        for f in alphabet(len(prefix)):
            prefix.append(f)
            yield from rec(prefix)
            prefix.pop()
    yield from rec([])


def live_set(F):
    live = [True] * len(F)
    # fact i is live iff no LIVE annul targets it; annuls target earlier facts, so evaluate from the end
    for i in range(len(F) - 1, -1, -1):
        live[i] = not any(F[j][0] == 'X' and F[j][1] == i and live[j] for j in range(i + 1, len(F)))
    return live


def resolve(F, stored_vacates=()):
    live = live_set(F)
    A = [f[1] for f, l in zip(F, live) if l and f[0] == 'A']
    V = [f[1] for f, l in zip(F, live) if l and f[0] == 'V'] + list(stored_vacates)
    D = [f[1] for f, l in zip(F, live) if l and f[0] == 'D']
    res = []
    for t in range(H):
        held = any(a <= t and not any(a <= v <= t for v in V) for a in A)
        active = not any(d <= t for d in D)
        res.append(held and active)
    return tuple(res)


def held_at(F, t):
    live = live_set(F)
    A = [f[1] for f, l in zip(F, live) if l and f[0] == 'A']
    V = [f[1] for f, l in zip(F, live) if l and f[0] == 'V']
    return any(a <= t and not any(a <= v <= t for v in V) for a in A)


def cascade(F):
    stored = []
    for i, f in enumerate(F):
        if f[0] == 'D' and held_at(F[:i], f[1]):
            stored.append(f[1])
    return resolve(F, stored)


def status_effective_known(F, k, t):
    """STATUS: an Appoint counts from its RECORDING time; Vacate/Dissolve likewise by recording time."""
    on = False
    for i, f in enumerate(F[:k]):
        if i > t:
            break
        if f[0] == 'A':
            on = True
        elif f[0] in ('V', 'D'):
            on = False
    return on


out = {}
n = t1 = t3 = 0
t6 = {'REFERENCE': [0, 0], 'CASCADE': [0, 0]}      # [checked, violated]
for F in histories():
    n += 1
    ref = resolve(F)
    # T-1: future-effective appointment, knowledge at the end of the history
    for i, f in enumerate(F):
        if f[0] == 'A' and f[1] > i and live_set(F)[i]:
            if any(status_effective_known(F, len(F), t) and not ref[t] for t in range(i, f[1])):
                t1 += 1
                break
    # T-3: any divergence of CASCADE from REFERENCE in histories containing an annulled Dissolve
    live = live_set(F)
    if any(f[0] == 'D' and not live[i] for i, f in enumerate(F)) and cascade(F) != ref:
        t3 += 1
    # T-6: counterfactual theorem for a final Annul(c)
    if (F and F[-1][0] == 'X' and F[F[-1][1]][0] != 'X'
            and not any(f[0] == 'X' and f[1] == F[-1][1] for f in F[:-1])):
        c = F[-1][1]
        # F without c (and without the final Annul); later annul targets are re-indexed
        remap = {i: i - (i > c) for i in range(len(F) - 1) if i != c}
        without = [(f[0], remap[f[1]]) if f[0] == 'X' else f
                   for i, f in enumerate(F[:-1]) if i != c]
        ok_ref = resolve(F) == resolve(without)
        ok_cas = cascade(F) == cascade(without)
        t6['REFERENCE'][0] += 1
        t6['REFERENCE'][1] += (not ok_ref)
        t6['CASCADE'][0] += 1
        t6['CASCADE'][1] += (not ok_cas)
out['histories'] = n
out['T1_status_field_active_before_effective'] = t1
out['T3_cascade_diverges_after_dissolution_annulled'] = t3
out['T6_counterfactual'] = {k: dict(checked=v[0], violated=v[1]) for k, v in t6.items()}

# ---- T-5: policy versions and decision replay ------------------------------------------
# versions: list of (vid, effectiveFrom, permit). Decisions at times t pin the version effective at t.
# A correction at time r targets version vid and flips its value.
viol = {'IN_PLACE': 0, 'FORWARD_ONLY': 0}
cases = 0
for v0, v1 in product((True, False), repeat=2):
    for eff1 in range(1, H):
        for target in (0, 1):                        # the version a later correction targets
            for td in range(H):                      # decision time
                cases += 1
                versions = {0: (0, v0), 1: (eff1, v1)}
                vid = 1 if td >= eff1 else 0
                recorded = versions[vid][1]
                # IN_PLACE: the version content is rewritten; replay reads the current content of vid
                in_place = dict(versions)
                in_place[target] = (in_place[target][0], not in_place[target][1])
                replay_ip = in_place[vid][1]
                # FORWARD_ONLY: a new version id is added; the pinned vid is untouched
                replay_fo = versions[vid][1]
                viol['IN_PLACE'] += (replay_ip != recorded)
                viol['FORWARD_ONLY'] += (replay_fo != recorded)
out['T5_replay_as_decided'] = dict(cases=cases, mismatches=viol)
print(json.dumps(out, indent=1))
